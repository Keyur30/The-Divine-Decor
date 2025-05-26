<?php
session_start();
include('../config/dbcon.php');

// Initialize variables
$email = $pass = "";
$email_err = $pass_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $pass_err = "Please enter your password.";
    } else {
        $pass = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($pass_err)) {
        $sql = "SELECT delivery_person_id, Email, Password, Name FROM delivery_person WHERE Email = ?";
        
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;
            
            if ($stmt->execute()) {
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $email, $password, $name);
                    if ($stmt->fetch()) {
                        if ($pass === $password) {
                            $_SESSION['auth'] = true;
                            $_SESSION['delivery_id'] = $id;
                            $_SESSION['auth_user'] = [
                                'name' => $name,
                                'email' => $email
                            ];
                            header("location: index.php");
                            exit();
                        } else {
                            $pass_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="css/loginn.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
      <style>
         .error-container {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            color: #721c24;
         }
      </style>
   </head>
   <body>
      <div class="content">
         <div class="text">
            Delivery Person Login
         </div>
         <?php
         // Display any session messages
         if(isset($_SESSION['message'])) {
             echo '<div class="error-container">'.$_SESSION['message'].'</div>';
             unset($_SESSION['message']);
         }
         // Display validation errors
         if(!empty($email_err) || !empty($pass_err)) {
             echo '<div class="error-container">';
             if(!empty($email_err)) echo $email_err . "<br>";
             if(!empty($pass_err)) echo $pass_err;
             echo '</div>';
         }
         ?>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="field">
               <input type="text" name="email" required value="<?php echo $email; ?>">
               <span class="fas fa-user"></span>
               <label>Email</label>
            </div>
            <div class="field">
               <input type="password" name="password" required>
               <span class="fas fa-lock"></span>
               <label>Password</label>
            </div>
            <button type="submit">Sign in</button>
         </form>
      </div>
   </body>
</html>
