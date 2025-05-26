<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Add database connection
$conn = mysqli_connect("localhost:3307", "root", "", "customer");

// Initialize admin_name variable
$admin_name = 'Guest';

// Fetch admin name if admin is logged in
// if(isset($_SESSION['aid'])) {
//     $aid = $_SESSION['aid'];
//     $query = "SELECT FirstName, LastName FROM admin WHERE aid = '$aid'";
//     $result = mysqli_query($conn, $query);
//     if($row = mysqli_fetch_assoc($result)) {
//         $admin_name = $row['FirstName'] . ' ' . $row['LastName'];
//     }
// }
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- <a href="index3.html" class="brand-link">
      <img src="assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> -->
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3">
        <div class="info" style="width: 100%; padding: 6px;">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item" style="border-bottom: 1px solid #4f5962;">
              <a href="profile.php" class="nav-link" style="padding: 10px;">
                <i class="nav-icon fa-solid fa-user" style="font-size: 1.1rem; margin-right: 10px;"></i>
                <p style="font-size: 1rem;">Profile</p>
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="nav-icon fa-solid fa-gauge"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
          <a href="#" class="nav-link">
              <i class="nav-icon fa-solid fa-list"></i>  <!-- Updated icon -->
              <p>
                Category
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="category.php" class="nav-link">
                  <i class="fa-regular fa-circle nav-icon"></i>  <!-- Updated icon -->
                  <p>Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="subcategory.php" class="nav-link">
                  <i class="fa-regular fa-circle nav-icon"></i>  <!-- Updated icon -->
                  <p>Sub-category</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="product.php" class="nav-link">
              <i class="nav-icon fa-solid fa-box"></i>  <!-- Updated icon -->
              <p>
                Product
              </p>
            </a>
           
            </li>
            <li class="nav-item">
            <a href="order.php" class="nav-link">
              <i class="nav-icon fa-solid fa-shopping-cart"></i>
              <p>Order Details</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="payment.php" class="nav-link">
              <i class="nav-icon fa-solid fa-credit-card"></i>
              <p>Payment Details</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="feedback.php" class="nav-link">
              <i class="nav-icon fa-solid fa-comments"></i>
              <p>Feedback Details</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="report.php" class="nav-link">
              <i class="nav-icon fa-solid fa-chart-bar"></i>
              <p>Reports</p>
            </a>
            </li>
         
            <li class="nav-header">Settings <i class="fa-solid fa-gear"></i></li>  <!-- Updated icon -->
           
            <li class="nav-item">
            <a href="admin.php" class="nav-link">
                <i class="nav-icon fa-solid fa-user-shield"></i>  <!-- Updated icon -->
                <p>
                    Admin
                </p>
            </a>
            </li>
            <li class="nav-item">
            <a href="registerd.php" class="nav-link">
                <i class="nav-icon fa-solid fa-users"></i>
                <p>
                    Registered Users
                </p>
            </a>
            </li>
            <li class="nav-item">
            <a href="deliveryperson.php" class="nav-link">
                <i class="nav-icon fa-solid fa-truck"></i>
                <p>
                    Delivery-Person
                </p>
            </a>
            </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>