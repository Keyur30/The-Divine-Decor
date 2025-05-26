<?php
session_start();
include 'connect.php';
include ('functions/myfunctions.php');
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="favicon.png">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/tiny-slider.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <title>The Divine Decor</title>
  <style>
    .custom-navbar {
        padding-top: 15px !important;
        padding-bottom: 15px !important;
    }
    /* Copy other navbar-related styles from shop.php if needed */
  </style>
</head>

<body>
    <?php include 'includes/nav.php'; ?>

    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Luxury Home <span class="d-block">Furnishings</span></h1>
                        <p class="mb-4">Transform your living spaces with our expertly curated collection of premium furniture and home decor. Experience comfort, elegance, and style in every piece.</p>
                        <p><a href="shop.php" class="btn btn-secondary me-2">Shop Now</a><a href="" class="btn btn-white-outline">Explore</a></p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        <img src="images/couch.png" class="img-fluid" style="border-radius: 10px; margin-top: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <!-- Start Most Purchased Products Section -->
    <div class="product-section">
        <div class="container">
            <div class="row mb-5 justify-content-center">
                <div class="col-md-7 text-center">
                    <h2 class="section-title">Most Purchased Products</h2>
                    <p class="lead">Customer Favorites - Our Best Selling Items</p>
                </div>
            </div>

            <div class="row">
                <?php 
                $popular_products = getMostPurchasedProducts();
                if(mysqli_num_rows($popular_products) > 0) {
                    $counter = 0;
                    while($item = mysqli_fetch_assoc($popular_products)) {
                        if($counter == 0) {
                            // First item - show description column
                            ?>
                            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                                <h2 class="mb-4">Customer Favorites</h2>
                                <p class="mb-4">Discover our most popular items that customers love. These pieces have been chosen time and time again for their quality and style.</p>
                                <p><a href="shop.php" class="btn">View All Products</a></p>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-12 col-md-4 col-lg-3 mb-5">
                            <a class="product-item" href="product.php?id=<?= $item['pid']; ?>">
                                <img src="../gallery/<?= $item['p_image']; ?>" class="img-fluid product-thumbnail">
                                <h3 class="product-title"><?= $item['p_name']; ?></h3>
                                <strong class="product-price">₹<?= $item['p_price']; ?></strong>
                            </a>
                            <p class="text-center mt-2">
                                <a href="product.php?id=<?= $item['pid']; ?>" class="btn btn-sm btn-primary">Learn More</a>
                            </p>
                        </div>
                        <?php
                        $counter++;
                    }
                } else {
                    echo "<div class='col-12 text-center'><p>No products found</p></div>";
                }
                ?>
            </div>
        </div>
    </div>
    <!-- End Most Purchased Products Section -->
<!-- Start We Help Section -->
<div class="we-help-section">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-lg-7 mb-5 mb-lg-0">
						<div class="imgs-grid">
							<div class="grid grid-1"><img src="images/img-grid-1.jpg" alt="Untree.co"></div>
							<div class="grid grid-2"><img src="images/img-grid-2.jpg" alt="Untree.co"></div>
							<div class="grid grid-3"><img src="images/img-grid-3.jpg" alt="Untree.co"></div>
						</div>
					</div>
					<div class="col-lg-5 ps-lg-5">
						<h2 class="section-title mb-4">Crafting Beautiful Spaces Together</h2>
						<p>From contemporary designs to timeless classics, we help you create the perfect ambiance for your home. Our expert team ensures every piece tells your unique story.</p>

						<ul class="list-unstyled custom-list my-4">
							<li>Professional interior design consultation</li>
							<li>Custom furniture solutions</li>
							<li>Premium quality materials</li>
							<li>Nationwide delivery service</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- End We Help Section -->

		
    <!-- Start Blog Section -->
    
    <!-- End Blog Section -->

    
    <?php include 'includes/footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/tiny-slider.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>