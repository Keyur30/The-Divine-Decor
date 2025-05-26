<?php

session_start();
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
            <h1 class="m-0">Sub-category</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Edit Sub-category</li>
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
                <h3 class="card-title">Edit Sub-category
                </h3>
                <a href="subcategory.php"   class="btn btn-danger float-right" >Back</a>
              </div>
              <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                    <form action="subcategory_code.php" method="POST">
                    
      <div class="modal-body">
        <?php
        if(isset($_GET['sub_category_id']))
        {
            $sub_category_id=$_GET['sub_category_id'];
            $query="SELECT * FROM sub_category WHERE sub_category_id='$sub_category_id' LIMIT 1";
            $query_run=mysqli_query($conn,$query);
            if(mysqli_num_rows($query_run)>0)
            {
                foreach($query_run as $row)
                {
                        
                    ?>
<input type="hidden" name="sub_category_id" value="<?php echo $row['sub_category_id'] ?>">
<div class="form-group">
                      <div class="form-group">
                          <label for="category">Category Name</label>
                          <select name="category_id" class="form-control">
                              <?php
                              $category_query = "SELECT * FROM category";
                              $category_result = mysqli_query($conn, $category_query);
                              if(mysqli_num_rows($category_result) > 0) {
                                  foreach($category_result as $category) {
                                      $selected = ($category['category_id'] == $row['category_id']) ? 'selected' : '';
                                      ?>
                                      <option value="<?= $category['category_id']; ?>" <?= $selected ?>><?= $category['category_name']; ?></option>
                                      <?php
                                  }
                              }
                              ?>
                          </select>
                      </div>
                      <div class="form-group">
                          <label for="Name">Sub-category Name</label>
                          <input type="text" name="sub_category_name" value="<?php echo $row['sub_category_name'] ?>" class="form-control" placeholder="Sub-category Name">
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
        
        <button type="submit" name="updatesubcategory" class="btn btn-info">Update</button>
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
<?php include('script.php');?>
<?php
include('includes/footer.php');
?>