<?php
session_start();

// Database connection
$servername = "localhost:3307"; // Usually localhost
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "customer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$email = $pass = "";
$email_err = $pass_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]); // Set email first
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Then validate it
            $email_err = "Please enter a valid email address.";
        }
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $pass_err = "Please enter your password.";
    } else {
        $pass = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($pass_err)) {
        // Prepare a select statement
        $sql = "SELECT Cid, Email, Password FROM customer WHERE Email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();
                
                // Check if email exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $email, $password);
                    if ($stmt->fetch()) {
                        // Direct comparison without hashing
                        if ($pass === $password) {
                            // Password is correct, get customer name
                            $name_sql = "SELECT C_name FROM customer WHERE Cid = ?";
                            if ($name_stmt = $conn->prepare($name_sql)) {
                                $name_stmt->bind_param("i", $id);
                                $name_stmt->execute();
                                $name_result = $name_stmt->get_result();
                                $customer = $name_result->fetch_assoc();
                                
                                // Start a new session
                                session_start();
                                
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["cid"] = $id;
                                $_SESSION["email"] = $email;
                                $_SESSION["name"] = $customer['C_name'];
                                
                                // Redirect user to index page
                                header("location: index.php");
                                exit();
                            }
                        } else {
                            $pass_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <link rel="stylesheet" href="css/loginn.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
   </head>
   <body>
      <div class="content">
         <div class="text">
            Login Form
         </div>
         <?php if(!empty($email_err) || !empty($pass_err)): ?>
            <div class="error-messages">
               <?php 
                  if(!empty($email_err)) echo "<div class='error'>{$email_err}</div>";
                  if(!empty($pass_err)) echo "<div class='error'>{$pass_err}</div>";
               ?>
            </div>
         <?php endif; ?>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="field">
               <input type="text" name="email" required value="<?php echo $email; ?>">
               <span class="fas fa-user"></span>
               <label>Email</label>
            </div>
            <div class="field">
               <input type="password" name="password" id="password" required>
               <span class="fas fa-lock"></span>
               <label>Password</label>
               <span class="toggle-password fas fa-eye" onclick="togglePassword('password')"></span>
            </div>
            <div class="forgot-pass">
               <a href="forgot_password.php">Forgot Password?</a>
            </div>
            <button type="submit" name="login">Sign in</button>
            <div class="sign">
               Not a member?
               <a href="Reg.php" style="text-decoration :none ;color:#3498db">Signup Now</a>
            </div>
         </form>
      </div>
   </body>
   <script>
      function togglePassword(inputId) {
         const input = document.getElementById(inputId);
         const toggleIcon = input.nextElementSibling.nextElementSibling.nextElementSibling;
         
         if (input.type === "password") {
            input.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
         } else {
            input.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
         }
      }
   </script>
</html>