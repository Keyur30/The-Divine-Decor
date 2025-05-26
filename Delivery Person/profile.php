<?php 
session_start();
if(!isset($_SESSION['auth']) || $_SESSION['auth'] != true) {
    header('Location: login.php');
    exit();
}
include('../config/dbcon.php');
include('includes/header.php');

$delivery_id = $_SESSION['delivery_id'];
$delivery_query = "SELECT * FROM delivery_person WHERE delivery_person_id = '$delivery_id'";
$delivery_result = mysqli_query($con, $delivery_query);
$delivery_data = mysqli_fetch_assoc($delivery_result);
// Uncomment next line temporarily to debug
// var_dump($delivery_data);
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Profile</h1>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Personal Information</h4>
                    <form action="code.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="<?= $delivery_data['Name'] ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="<?= $delivery_data['Email'] ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="<?= $delivery_data['Contact_no'] ?>" class="form-control">
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Update Password</h4>
                    <form action="code.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
