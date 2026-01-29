<?php
session_start();

// Redirect if no order information
if(!isset($_SESSION['last_order'])) {
    header('Location: shop.php');
    exit();
}
?>
<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
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
		<title>The Divine Decor </title>
		<style>
            .custom-navbar {
                padding-top: 15px !important;
                padding-bottom: 15px !important;
            }
            .custom-navbar .nav-link {
                padding-top: 5px !important;
                padding-bottom: 5px !important;
            }
            .custom-navbar .navbar-brand {
                padding: 0 !important;
            }
            .custom-navbar img {
                width: 20px;
                height: auto;
            }
            .hero {
                padding: 30px 0;
                padding-bottom: 20px;
            }
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
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

		<div class="untree_co-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center pt-5">
          <span class="display-3 thankyou-icon text-primary">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cart-check mb-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M11.354 5.646a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L8 8.293l2.646-2.647a.5.5 0 0 1 .708 0z"/>
              <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
            </svg>
          </span>
          <h2 class="display-3 text-black">Thank you!</h2>
          <p class="lead mb-5">Your order was successfully completed.</p>
          
          <div class="order-details bg-light p-4 mb-5" style="max-width: 600px; margin: 0 auto;">
            <h4 class="mb-4">Order Details</h4>
            <p><strong>Order ID:</strong> #<?php echo isset($_SESSION['last_order']['order_details_id']) ? $_SESSION['last_order']['order_details_id'] : 'N/A'; ?></p>
            <p><strong>Order Date:</strong> <?php 
                if(isset($_SESSION['last_order']['order_date'])) {
                    echo date('d M Y', strtotime($_SESSION['last_order']['order_date']));
                } else {
                    echo 'N/A';
                }
            ?></p>
            <p><strong>Contact:</strong> <?php echo !empty($_SESSION['last_order']['contact']) ? $_SESSION['last_order']['contact'] : 'N/A'; ?></p>
            <p><strong>Delivery Address:</strong> <?php echo !empty($_SESSION['last_order']['address']) ? $_SESSION['last_order']['address'] : 'N/A'; ?></p>
            
            <div class="order-items mb-4">
                <h5 class="mb-3">Items Ordered</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($_SESSION['last_order']['items'] as $item): ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="order-summary">
                <div class="d-flex justify-content-between mb-2">
                    <span><strong>Total:</strong></span>
                    <span><strong>₹<?php echo number_format($_SESSION['last_order']['total_amount'], 2); ?></strong></span>
                </div>
            </div>
          </div>
          
          <p><a href="shop.php" class="btn btn-sm btn-outline-black">Back to shop</a></p>
        </div>
      </div>
    </div>
  </div>

<?php 
// Clean up session after displaying order details
unset($_SESSION['last_order']);
?>

		<!-- Start Footer Section -->
	    <?php include 'includes/footer.php'; ?>
		<!-- End Footer Section -->	


		<script src="js/bootstrap.bundle.min.js"></script>
		<script src="js/tiny-slider.js"></script>
		<script src="js/custom.js"></script>
	</body>

</html>
