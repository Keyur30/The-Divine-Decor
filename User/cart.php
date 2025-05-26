<?php
session_start();
include 'connect.php';  // Add database connection
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
                padding: 30px 0;  /* Reduced hero section padding */
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
								<p style="font-size:40px; font-weight:bold; color:#ffffff;">Cart</p>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

		<div class="untree_co-section before-footer-section">
            <div class="container">
              <div class="row mb-5">
                <form class="col-md-12" method="post">
                  <div class="site-blocks-table">
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="product-thumbnail">Image</th>
                          <th class="product-name">Product</th>
                          <th class="product-price">Price</th>
                          <th class="product-quantity">Quantity</th>
                          <th class="product-total">Total</th>
                          <th class="product-remove">Remove</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $total = 0;
                        if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            foreach($_SESSION['cart'] as $pid => $item) {
                                // Get current stock from database
                                $query = "SELECT quantity FROM product WHERE pid = ?";
                                $stmt = mysqli_prepare($con, $query);
                                mysqli_stmt_bind_param($stmt, "i", $pid);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                $product = mysqli_fetch_assoc($result);
                                
                                $maxQuantity = $product['quantity'] + 1;
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                        ?>
                        <tr>
                          <td class="product-thumbnail">
                            <img src="../gallery/<?= htmlspecialchars($item['image']) ?>" 
                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                 class="img-fluid" 
                                 style="max-width: 100px;">
                          </td>
                          <td class="product-name">
                            <h2 class="h5 text-black"><?= htmlspecialchars($item['name']) ?></h2>
                          </td>
                          <td>₹<span class="product-unit-price"><?= number_format($item['price'], 2) ?></span></td>
                          <td>
                            <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">
                              <div class="input-group-prepend">
                                <button class="btn btn-outline-black decrease" type="button" data-pid="<?= $pid ?>">&minus;</button>
                              </div>
                              <input type="text" class="form-control text-center quantity-amount" 
                                     value="<?= $item['quantity'] ?>" 
                                     data-max="<?= $maxQuantity ?>" readonly>
                              <div class="input-group-append">
                                <button class="btn btn-outline-black increase" type="button" 
                                        data-pid="<?= $pid ?>" 
                                        <?= ($item['quantity'] > $maxQuantity) ? 'disabled' : '' ?>>
                                        &plus;
                                </button>
                              </div>
                            </div>
                            <?php if($maxQuantity < $item['quantity']): ?>
                                <small class="text-danger">Only <?= $maxQuantity ?> items available</small>
                            <?php endif; ?>
                          </td>
                          <td>₹<span class="product-subtotal" data-pid="<?= $pid ?>"><?= number_format($subtotal, 2) ?></span></td>
                          <td><a href="#" class="btn btn-black btn-sm remove-item" data-pid="<?= $pid ?>">X</a></td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center">Your cart is empty</td></tr>';
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </form>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="row mb-5">
                    <!-- <div class="col-md-6 mb-3 mb-md-0">
                      <button class="btn btn-black btn-sm btn-block update-cart">Update Cart</button> !-- Update cart button**************************************->
                    </div> -->
                    <div class="col-md-6">
                      <button class="btn btn-outline-black btn-sm btn-block" onclick="window.location='shop.php'">Continue Shopping</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 pl-5">
                  <div class="row justify-content-end">
                    <div class="col-md-7">
                      <div class="row">
                        <div class="col-md-12 text-right border-bottom mb-5">
                          <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                        </div>
                      </div>
                      <div class="row mb-5">
                        <div class="col-md-6">
                          <span class="text-black">Total</span>
                        </div>
                        <div class="col-md-6 text-right">
                          <strong class="text-black cart-total">₹<?= number_format($total, 2) ?></strong>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                            <button class="btn btn-black btn-lg py-3 btn-block" onclick="window.location='checkout.php'">Proceed To Checkout</button>
                          <?php else: ?>
                            <div class="alert alert-warning" role="alert">
                              Please <a href="login.php" class="alert-link">login</a> to proceed with checkout.
                            </div>
                            <button class="btn btn-black btn-lg py-3 btn-block" onclick="window.location='login.php'">Login to Checkout</button>
                          <?php endif; ?>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
		

		<!-- Start Footer Section -->
    <?php include 'includes/footer.php'; ?>

		<!-- End Footer Section -->	


		<script src="js/bootstrap.bundle.min.js"></script>
		<script src="js/tiny-slider.js"></script>
		<script src="js/custom.js"></script>
        <script>
            document.querySelectorAll('.increase, .decrease').forEach(button => {
                button.addEventListener('click', function() {
                    const pid = this.dataset.pid;
                    const action = this.classList.contains('increase') ? 'increase' : 'decrease';
                    const row = this.closest('tr');
                    const quantityInput = row.querySelector('.quantity-amount');
                    const maxQuantity = parseInt(quantityInput.dataset.max);
                    const currentQuantity = parseInt(quantityInput.value);

                    // Silently prevent increase if at max quantity
                    if (action === 'increase' && currentQuantity >= maxQuantity) {
                        this.disabled = true;
                        return;
                    }

                    updateCart(pid, action);
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pid = this.dataset.pid;
                    updateCart(pid, 'remove');
                });
            });

            function updateCart(pid, action) {
                fetch('functions/updatecart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `pid=${pid}&action=${action}`
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        if (action === 'remove') {
                            location.reload();
                            return;
                        }
                        
                        // Update quantity and check max
                        const row = document.querySelector(`[data-pid="${pid}"]`).closest('tr');
                        const quantityInput = row.querySelector('.quantity-amount');
                        const increaseButton = row.querySelector('.increase');
                        
                        quantityInput.value = data.newQuantity;
                        
                        // Disable increase button if at max quantity
                        if (data.newQuantity >= data.maxQuantity) {
                            increaseButton.disabled = true;
                        } else {
                            increaseButton.disabled = false;
                        }
                        
                        // Update subtotal for this product
                        const subtotalElement = row.querySelector('.product-subtotal');
                        subtotalElement.textContent = data.newSubtotal;
                        
                        // Update cart total
                        document.querySelector('.cart-total').textContent = `₹${data.newTotal}`;
                        
                        // Update cart count if exists
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.cartCount;
                        }
                    } else if(data.status === 'error') {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        </script>
	</body>

</html>
