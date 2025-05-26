<?php
session_start();
include 'connect.php';

// Check if composer autoloader exists
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
    die('Please install dependencies using Composer');
}

// Include email configuration
if (file_exists('config/email_config.php')) {
    require 'config/email_config.php';
} else {
    die('Email configuration file missing');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$email = $email_err = $success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = trim($_POST["email"]);
        
        // Check if email exists
        $sql = "SELECT Cid FROM customer WHERE Email = ?";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    // Generate temporary password
                    $temp_password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
                    
                    // Update password in database (without hashing)
                    $update_sql = "UPDATE customer SET Password = ? WHERE Email = ?";
                    if ($update_stmt = $con->prepare($update_sql)) {
                        $update_stmt->bind_param("ss", $temp_password, $email);
                        if ($update_stmt->execute()) {
                            // Configure PHPMailer
                            $mail = new PHPMailer(true);
                            try {
                                // Debug mode
                                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                                $mail->SMTPDebug = 0;
                                
                                // Server settings
                                $mail->isSMTP();
                                $mail->Host = SMTP_HOST;
                                $mail->SMTPAuth = true;
                                $mail->Username = SMTP_USERNAME;
                                $mail->Password = SMTP_PASSWORD;
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                $mail->Port = SMTP_PORT;

                                // Recipients
                                $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
                                $mail->addAddress($email);

                                // Content
                                $mail->isHTML(true);
                                $mail->Subject = 'Temporary Password';
                                $mail->Body = "Your temporary password is: <b>" . $temp_password . "</b><br><br>Please login and change your password immediately.";

                                $mail->send();
                                $success_msg = "A temporary password has been sent to your email.";
                            } catch (Exception $e) {
                                $email_err = "Error sending email. Mailer Error: {$mail->ErrorInfo}";
                            }
                        }
                        $update_stmt->close();
                    }
                } else {
                    $email_err = "No account found with that email address.";
                }
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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/loginn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
    <div class="content">
        <div class="text">Reset Password</div>
        
        <?php if(!empty($success_msg)): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="field">
                <input type="email" name="email" required value="<?php echo $email; ?>">
                <span class="fas fa-envelope"></span>
                <label>Email</label>
                <span class="text-danger"><?php echo $email_err; ?></span>
            </div>
            <button type="submit">Send Reset Link</button>
            <div class="sign-up">
                Remember password? <a href="login.php">Login here</a>
            </div>
        </form>
    </div>
</body>
</html>
