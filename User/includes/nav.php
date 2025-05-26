<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    .dropdown-item:hover {
  background-color:rgba(59, 93, 80, 0.88);  ;
  color: #ffffff;
}
</style>
<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
        <a class="navbar-brand" href="index.php">The Divine Decor<span>.</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item <?= (basename($_SERVER['PHP_SELF']) == 'shop.php') ? 'active' : '' ?>">
                    <a class="nav-link" href="shop.php">Shop</a>
                </li>
                <li class="nav-item <?= (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : '' ?>">
                    <a class="nav-link" href="about.php">About us</a>
                </li>
                
            </ul>

            <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="images/user.svg" alt="User">
                            <?php echo htmlspecialchars($_SESSION["name"]); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                            <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a class="nav-link" href="login.php"><img src="images/user.svg"></a></li>
                <?php endif; ?>
                <li>
                    <a class="nav-link position-relative" href="cart.php">
                        <img src="images/cart.svg">
                        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= count($_SESSION['cart']) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Header/Navigation -->
