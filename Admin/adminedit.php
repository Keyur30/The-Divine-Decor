<?php
include('config.php');
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
include('connect.php');
?>
<div class="content-wrapper">
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Admin</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Edit Admin</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <?php
  if(isset($_SESSION['status']))
  {
    echo"<h4>".$_SESSION['status']."</h4>";
    unset($_SESSION['status']);
  }

?>

<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12"></div>

    
    <div class="card">
              <div class="card-header">
                <h3 class="card-title">Edit Admin
                </h3>
                <a href="admin.php"   class="btn btn-danger float-right" >Back</a>
              </div>
              <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                    <form action="code.php" method="POST" enctype="multipart/form-data">
                    
      <div class="modal-body">
        <?php
        if(isset($_GET['aid']))
        {
            $admin_id=$_GET['aid'];
            $query="SELECT * FROM admin WHERE aid='$admin_id' LIMIT 1";
            $query_run=mysqli_query($conn,$query);
            if(mysqli_num_rows($query_run)>0)
            {
                foreach($query_run as $row)
                {
                        
                    ?>
<input type="hidden" name="admin_id" value="<?php echo $row['aid'] ?>">
<div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" name="email" value="<?php echo $row['Email']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="FirstName">First Name</label>
                    <input type="text" name="firstname" value="<?php echo $row['FirstName']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="LastName">Last Name</label>
                    <input type="text" name="lastname" value="<?php echo $row['LastName']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="Gender">Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="Male" <?php echo ($row['Gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($row['Gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($row['Gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Address">Address</label>
                    <textarea name="address" class="form-control" required><?php echo $row['Address']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="State">State</label>
                    <input type="text" name="state" value="<?php echo $row['State']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="Cellno">Cell Number</label>
                    <input type="text" name="cellno" value="<?php echo $row['Cellno']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="adminProfile">Profile Image</label>
                    <?php if(!empty($row['adminProfile'])): ?>
                        <img src="images/admin/<?php echo $row['adminProfile']; ?>" alt="Current Profile" width="100"><br>
                    <?php endif; ?>
                    <input type="file" name="adminProfile" class="form-control" accept="image/*">
                    <input type="hidden" name="old_image" value="<?php echo $row['adminProfile']; ?>">
                    <small class="text-muted">Allowed file types: jpg, jpeg, png, gif</small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="changePassword" name="change_password">
                        <label class="custom-control-label" for="changePassword">Change Password</label>
                    </div>
                </div>

                <div id="passwordFields" style="display: none;">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" name="current_password" class="form-control" id="currentPassword">
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" name="new_password" class="form-control" id="newPassword">
                        <small class="text-muted">Password must be at least 8 characters long</small>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" id="confirmPassword">
                    </div>
                </div>
                    <?php
                }
            }
            else
            {
                echo "<h4>No Record Found.</h4>";
            }
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="submit" name="updateadmin" class="btn btn-info">Update</button>
      </div>
    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>

<script>
document.getElementById('changePassword').addEventListener('change', function() {
    document.getElementById('passwordFields').style.display = this.checked ? 'block' : 'none';
    const passwordInputs = document.querySelectorAll('#passwordFields input');
    passwordInputs.forEach(input => {
        input.required = this.checked;
    });
});
</script>

<?php include('script.php');?>
<?php include('includes/footer.php');?>