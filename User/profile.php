<?php
session_start();
include 'connect.php';

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Get user details
$sql = "SELECT * FROM customer WHERE Cid = ?";
if($stmt = mysqli_prepare($con, $sql)) { // Changed $conn to $con
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["cid"]);
    if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    } else {
        // Handle query execution error
        echo "Error executing query. Please try again.";
        exit;
    }
} else {
    // Handle prepare statement error
    echo "Database error. Please try again.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - The Divine Decor</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
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
                            <a href="profile.php" class="list-group-item active">My Profile</a>
                            <a href="orders.php" class="list-group-item">My Orders</a>
                            <a href="update_pass.php" class="list-group-item">Update Password</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">My Profile</h3>
                        <form method="post" action="update_profile.php">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['C_name']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['Email']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['Contact_no']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-control" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?= $user['Gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= $user['Gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= $user['Gender'] == 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3"><?= htmlspecialchars($user['Address']) ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
