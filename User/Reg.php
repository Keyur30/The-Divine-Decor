<?php 
session_start();

// include("server.php")

    $name = "";
    $email = "";  
    $phone = "";
    $password = "";
    $cpassword = "";

     // Database connection
     $host = 'localhost:3307'; // Replace with your MySQL host  /////change this to your server port number
     $dbname = 'customer';
     $user = 'root'; 
     $db_password = ''; // Replace with your MySQL password

    $connection = mysqli_connect($host, $user, $db_password);

    // Check connection
    // if($connection == false){ /* MySQL gives value 1 to the successful connection and 0 to the failed one */
    //     echo 'Connection failed!';
    // }
    // echo "Connected successfully";

    $db_select = mysqli_select_db($connection, $dbname);
    if($db_select === false){ /* Same thing for the selection of the database */
    echo 'Database selection failed!';
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = array();
        
        // receive all input values from the form
        $name = mysqli_real_escape_string($connection, $_POST['name'] ?? '');
        $email = mysqli_real_escape_string($connection, $_POST['email'] ?? '');
        $phone = mysqli_real_escape_string($connection, $_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $cpassword = $_POST['cpassword'] ?? '';

        // Check if email already exists
        $email_check_query = "SELECT * FROM customer WHERE Email='$email' LIMIT 1";
        $result = mysqli_query($connection, $email_check_query);
        $user = mysqli_fetch_assoc($result);
        
        if ($user) {
            $errors[] = "Email already exists";
        }

        // Validate password strength
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        if (!preg_match("#[0-9]+#", $password)) {
            $errors[] = "Password must include at least one number";
        }
        if (!preg_match("#[A-Z]+#", $password)) {
            $errors[] = "Password must include at least one uppercase letter";
        }

        // Validate phone number format
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $errors[] = "Invalid phone number format";
        }

        // Check password confirmation
        if ($password != $cpassword) {
            $errors[] = "Passwords do not match";
        }

        if (empty($errors)) {
            // Removed md5 hashing
            $query = "INSERT INTO customer (C_name, Email, Contact_no, Password) VALUES ('$name', '$email', '$phone', '$password')";
            $result = mysqli_query($connection, $query);
            
            if ($result) {
                $cid = mysqli_insert_id($connection);
                $_SESSION["loggedin"] = true;
                $_SESSION["cid"] = $cid;
                $_SESSION["email"] = $email;
                $_SESSION["name"] = $name;
                header('location: index.php');
                exit();
            } else {
                $errors[] = "Registration failed: " . mysqli_error($connection);
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/Register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
    <div class="content">
        <div class="text">Sign Up</div>
        <?php if(isset($errors)): ?>
            <div class="error-messages">
                <?php foreach($errors as $error): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="registrationForm">
            <div class="field">
                <input type="text" name="name" required>        <!---->
                <span class="fas fa-user" aria-hidden="true"></span>
                <label>Name</label>
            </div>
            <div class="field">
                <input type="email" name="email" required>      <!---->
                <span class="fas fa-envelope" aria-hidden="true"></span>
                <label>Email</label>
            </div>
            <div class="field">
                <input type="text" name="phone" id="phone" pattern="[0-9]{10}" maxlength="10" required>
                <span class="fa fa-phone" aria-hidden="true"></span>
                <label>Phone</label>
            </div>
            <div class="field">
                <input type="password" name="password" id="password" required>      <!---->
                <span class="fas fa-lock" aria-hidden="true"></span>
                <span class="fas fa-eye" id="togglePassword" style="position: absolute; right: 15px; cursor: pointer;"></span>
                <label>Password</label>
            </div>
            <div class="field">
                <input type="password" name="cpassword" id="c-password" required>      <!---->
                <span class="fas fa-lock" aria-hidden="true"></span>
                <span class="fas fa-eye" id="toggleCPassword" style="position: absolute; right: 15px; cursor: pointer;"></span>
                <label>Confirm Password</label>
            </div>
            <button type="submit" name="sign_up">Sign Up</button>         <!---->
            <div  class="sign-up">
                Existing member.&nbsp
                <a href="login.php" class="link" >Sign in</a>
            </div>
            <div id="message" class="mt-3"></div>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const toggleCPassword = document.querySelector('#toggleCPassword');
        const password = document.querySelector('#password');
        const cpassword = document.querySelector('#c-password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        toggleCPassword.addEventListener('click', function (e) {
            const type = cpassword.getAttribute('type') === 'password' ? 'text' : 'password';
            cpassword.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Add phone number validation
        document.getElementById('phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if(this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });

        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('c-password').value;
            const messageDiv = document.getElementById('message');

            // Add phone validation
            if(phone.length !== 10 || !/^[0-9]{10}$/.test(phone)) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Phone number must be exactly 10 digits</div>';
                return;
            }

            // Client-side password validation
            if (password.length < 8) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Password must be at least 8 characters long</div>';
                return;
            }
            if (!/[0-9]/.test(password)) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Password must include at least one number</div>';
                return;
            }
            if (!/[A-Z]/.test(password)) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Password must include at least one uppercase letter</div>';
                return;
            }
            if (password !== confirmPassword) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Passwords do not match!</div>';
                return;
            }

            this.submit();
        });
    </script>
</body>
</html>