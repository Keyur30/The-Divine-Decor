<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin-login.php");
    exit;
}

if(!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('connect.php');

// Fetch admin details using prepared statement
$admin_id = $_SESSION['admin_id'];
$query = "SELECT * FROM admin WHERE aid = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $admin_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Profile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <?php include './includes/links.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper"></div>

  <?php include 'includes/header.php'; ?>
  <?php include 'includes/sidebar.php'; ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Admin Profile</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="text-center">
                      <?php 
                      $upload_dir = 'images/admin/';
                      // Create directory if it doesn't exist
                      if (!file_exists($upload_dir)) {
                          mkdir($upload_dir, 0777, true);
                      }
                      
                      $profile_pic = !empty($admin['adminProfile']) ? $admin['adminProfile'] : '';
                      $profile_path = $upload_dir . $profile_pic;
                      
                      if(!empty($profile_pic) && file_exists($profile_path)): ?>
                        <img src="<?php echo htmlspecialchars($profile_path); ?>" 
                             class="img-fluid rounded-circle" 
                             alt="Admin Profile"
                             style="width: 200px; height: 200px; object-fit: cover; border: 3px solid #007bff;">
                      <?php else: ?>
                        <div class="default-profile-icon" style="width: 200px; height: 200px; margin: 0 auto; background: linear-gradient(135deg, #007bff, #0056b3); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <i class="fas fa-user-tie" style="font-size: 85px; color: #ffffff;"></i>
                        </div>
                      <?php endif; ?>
                      
                      <div class="mt-3">
                        <p class="text-muted">Current Profile: <?php echo !empty($profile_pic) ? htmlspecialchars($profile_pic) : 'No profile picture set'; ?></p>
                      </div>

                      <!-- Add profile picture upload form -->
                      <form action="update_profile_picture.php" method="POST" enctype="multipart/form-data" class="mt-3">
                        <div class="form-group">
                          <input type="file" class="form-control-file" name="profile_picture" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mt-2">Update Profile Picture</button>
                      </form>
                      
                      <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger mt-3"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                      <?php endif; ?>
                      
                      <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success mt-3"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <h3>Personal Information</h3>
                    <table class="table">
                      <tr>
                        <th style="width: 30%">First Name</th>
                        <td><?php echo htmlspecialchars($admin['FirstName']); ?></td>
                      </tr>
                      <tr>
                        <th>Last Name</th>
                        <td><?php echo htmlspecialchars($admin['LastName']); ?></td>
                      </tr>
                      <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($admin['Email']); ?></td>
                      </tr>
                      <tr>
                        <th>Gender</th>
                        <td><?php echo htmlspecialchars($admin['Gender']); ?></td>
                      </tr>
                      <tr>
                        <th>Contact Number</th>
                        <td><?php echo htmlspecialchars($admin['Cellno']); ?></td>
                      </tr>
                      <tr>
                        <th>Address</th>
                        <td><?php echo htmlspecialchars($admin['Address']); ?></td>
                      </tr>
                      <tr>
                        <th>State</th>
                        <td><?php echo htmlspecialchars($admin['State']); ?></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
