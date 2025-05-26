<?php
session_start();
include 'connect.php';

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = $token_err = "";

if (!isset($_GET['token'])) {
    header("location: login.php");
    exit;
}

$token = $_GET['token'];
$token_hash = hash('sha256', $token);

// Verify token
$sql = "SELECT Cid, Email FROM customer WHERE reset_token = ? AND reset_token_expiry > NOW()";
if ($stmt = $con->prepare($sql)) {
    $stmt->bind_param("s", $token_hash);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows != 1) {
            $token_err = "Invalid or expired reset link.";
        }
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($token_err)) {
    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";     
    } elseif (strlen(trim($_POST["new_password"])) < 8) {
        $new_password_err = "Password must have at least 8 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Passwords did not match.";
        }
    }
    
    if (empty($new_password_err) && empty($confirm_password_err)) {
        $sql = "UPDATE customer SET Password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
        if ($stmt = $con->prepare($sql)) {
            $param_password = md5($new_password); // Use your password hashing method
            $stmt->bind_param("ss", $param_password, $token_hash);
            if ($stmt->execute()) {
                header("location: login.php?password_reset=success");
                exit();
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/loginn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
    <div class="content">
        <div class="text">Reset Password</div>
        
        <?php if(!empty($token_err)): ?>
            <div class="alert alert-danger"><?php echo $token_err; ?></div>
        <?php else: ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?token=' . htmlspecialchars($token); ?>" method="post">
                <div class="field">
                    <input type="password" name="new_password" id="new_password" required>
                    <span class="fas fa-lock"></span>
                    <label>New Password</label>
                    <span class="toggle-password fas fa-eye" onclick="togglePassword('new_password')"></span>
                    <span class="text-danger"><?php echo $new_password_err; ?></span>
                </div>
                <div class="field">
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <span class="fas fa-lock"></span>
                    <label>Confirm Password</label>
                    <span class="toggle-password fas fa-eye" onclick="togglePassword('confirm_password')"></span>
                    <span class="text-danger"><?php echo $confirm_password_err; ?></span>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
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
</body>
</html>
