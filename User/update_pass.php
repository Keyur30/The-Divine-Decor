<?php
session_start();
require 'connect.php';

// Update session check to match profile.php
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password - without hashing
    $sql = "SELECT Password FROM customer WHERE Cid = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["cid"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    
    if ($old_password === $user['Password']) { // Direct comparison since passwords aren't hashed
        if ($new_password === $confirm_password) {
            // Update password without hashing
            $update_sql = "UPDATE customer SET Password = ? WHERE Cid = ?";
            $update_stmt = mysqli_prepare($con, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "si", $new_password, $_SESSION["cid"]);
            
            if (mysqli_stmt_execute($update_stmt)) {
                $message = "Password updated successfully!";
            } else {
                $error = "Error updating password.";
            }
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update Password - The Divine Decor</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .password-field {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
        }
    </style>
</head>
<body>
    <?php include 'includes/nav.php'; ?>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Account Menu</h5>
                        <div class="list-group">
                            <a href="profile.php" class="list-group-item">My Profile</a>
                            <a href="orders.php" class="list-group-item">My Orders</a>
                            <a href="update_pass.php" class="list-group-item active">Update Password</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Update Password</h3>
                        <?php if ($message): ?>
                            <div class="alert alert-success"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <div class="password-field">
                                    <input type="password" name="old_password" class="form-control" required>
                                    <i class="toggle-password fas fa-eye" onclick="togglePassword(this)"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <div class="password-field">
                                    <input type="password" name="new_password" class="form-control" required>
                                    <i class="toggle-password fas fa-eye" onclick="togglePassword(this)"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <div class="password-field">
                                    <input type="password" name="confirm_password" class="form-control" required>
                                    <i class="toggle-password fas fa-eye" onclick="togglePassword(this)"></i>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(button) {
            const input = button.previousElementSibling;
            if (input.type === "password") {
                input.type = "text";
                button.classList.remove("fa-eye");
                button.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                button.classList.remove("fa-eye-slash");
                button.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
