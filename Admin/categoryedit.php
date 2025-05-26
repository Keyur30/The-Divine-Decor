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
            <h1 class="m-0">Category</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Edit Category</li>
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
                <h3 class="card-title">Edit Category
                </h3>
                <a href="category.php"   class="btn btn-danger float-right" >Back</a>
              </div>
              <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                    <form action="category_code.php" method="POST">
                    
      <div class="modal-body">
        <?php
        if(isset($_GET['category_id']))
        {
            $category_id=$_GET['category_id'];
            $query="SELECT * FROM category WHERE category_id='$category_id' LIMIT 1";
            $query_run=mysqli_query($conn,$query);
            if(mysqli_num_rows($query_run)>0)
            {
                foreach($query_run as $row)
                {
                        
                    ?>
<input type="hidden" name="category_id" value="<?php echo $row['category_id'] ?>">
<div class="form-group">

                      
                     <div class="form-group">
                 <label for="Name">Category Name</label>
                    <input type="text" name="name" value="<?php echo $row['category_name'] ?>" class="form-control" placeholder="Category Name">
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
        
        <button type="submit" name="updatecategory" class="btn btn-info">Update</button>
      </div>
      </form>
      </div>
      </div>
      </div>
                    </div>
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