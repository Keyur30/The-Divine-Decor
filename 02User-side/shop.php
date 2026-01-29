<?php
// Include the database connection file
include 'connect.php';
include ('functions/myfunctions.php');

// function getAll($table) {
//     global $conn;
//     $query = "SELECT * FROM $table";
//     $result = mysqli_query($conn, $query);
//     return $result;
// }
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
            .product-thumbnail {
                width: 100%;
                height: 300px; /* Fixed height */
                object-fit: cover; /* Maintain aspect ratio and cover container */
                border-radius: 8px; /* Optional: rounded corners */
            }
            .product-item {
                display: block;
                text-align: center;
                text-decoration: none;
                margin-bottom: 15px;
            }
            /* Updated category navigation styles */
            .category-nav {
                background-color: #3b5d50;
                padding: 10px 0;
            }
            .category-btn {
                color: #ffffff;
                background-color: transparent;
                border: 1px solid rgba(255, 255, 255, 0.3);
                margin: 3px;
                padding: 5px 15px;
                font-size: 0.9rem;
                min-width: 100px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                transition: all 0.3s ease;
            }
            .category-btn:hover {
                background-color: #ffffff;
                color: #3b5d50;
                border-color: #ffffff;
            }
            .category-wrapper {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 5px;
                max-width: 1200px;
                margin: 0 auto;
            }
            /* Add navbar height adjustments */
            .custom-navbar {
                padding-top: 15px !important;  /* Reduced from 20px */
                padding-bottom: 15px !important; /* Reduced from 50px */
            }
            .custom-navbar .nav-link {
                padding-top: 5px !important;
                padding-bottom: 5px !important;
            }
            .custom-navbar .navbar-brand {
                padding: 0 !important;
            }
            .custom-navbar img {
                width: 20px; /* Reduce icon size */
                height: auto;
            }
            .dropdown-menu {
                background-color: #ffffff;
                border: none;
                box-shadow: 0 2px 15px rgba(0,0,0,0.1);
                min-width: 200px;
            }
            .dropdown-item {
                color: #3b5d50;
                padding: 8px 20px;
                font-size: 0.9rem;
            }
            .dropdown-item:hover {
                background-color: #3b5d50;
                color: #ffffff;
            }
        </style>
	</head>

	<body>
		<?php include 'includes/nav.php'; ?>

		<!-- Start Category Navigation -->
		<div class="category-nav">
		    <div class="container">
		        <div class="row">
		            <div class="col-md-12">
		                <div class="category-wrapper">
		                    <a href="shop.php" class="btn category-btn">All</a>
		                    <?php
		                    $categories = getAll("category");
		                    if(mysqli_num_rows($categories) > 0) {
		                        foreach($categories as $category) {
		                            ?>
		                            <div class="dropdown d-inline-block">
		                                <button class="btn category-btn dropdown-toggle" type="button" 
		                                        id="cat<?= $category['category_id'] ?>" 
		                                        data-bs-toggle="dropdown" 
		                                        aria-expanded="false">
		                                    <?= $category['category_name'] ?>
		                                </button>
		                                <ul class="dropdown-menu" aria-labelledby="cat<?= $category['category_id'] ?>">
		                                    <li>
		                                        <a class="dropdown-item" href="shop.php?category=<?= $category['category_id'] ?>">
		                                            All <?= $category['category_name'] ?>
		                                        </a>
		                                    </li>
		                                    <li><hr class="dropdown-divider"></li>
		                                    <?php
		                                    $subcategories = getSubCategories($category['category_id']);
		                                    if(mysqli_num_rows($subcategories) > 0) {
		                                        while($subcat = mysqli_fetch_assoc($subcategories)) {
		                                            ?>
		                                            <li>
		                                                <a class="dropdown-item" 
		                                                   href="shop.php?category=<?= $category['category_id'] ?>&subcategory=<?= $subcat['sub_category_id'] ?>">
		                                                    <?= $subcat['sub_category_name'] ?>
		                                                </a>
		                                            </li>
		                                            <?php
		                                        }
		                                    }
		                                    ?>
		                                </ul>
		                            </div>
		                            <?php
		                        }
		                    }
		                    ?>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		<!-- End Category Navigation -->

	
		<!-- End Hero Section -->

		
<!-- display ing product in shop -->
		<div class="untree_co-section product-section before-footer-section">
		    <div class="container">
		      	<div class="row">
				<?php
					if(isset($_GET['category'])) {
						$category_id = $_GET['category'];
						if(isset($_GET['subcategory'])) {
							$subcategory_id = $_GET['subcategory'];
							$products = getProductsBySubCategory($subcategory_id);
						} else {
							$products = getProductsByCategory($category_id);
						}
					} else {
						$products = getAllActive("product");
					}

					if(mysqli_num_rows($products) > 0)
					{
						while($item = mysqli_fetch_assoc($products))
						{
				?>
							<div class="col-12 col-md-4 col-lg-3 mb-5">
								<a class="product-item" href="product.php?id=<?= $item['pid'] ?>">
									<img src="../gallery/<?= $item['p_image'] ?>" class="img-fluid product-thumbnail">
									<h3 class="product-title"><?= $item['p_name'] ?></h3>
									<strong class="product-price">₹<?= $item['p_price'] ?></strong>
									<span class="icon-cross add-to-cart" data-pid="<?= $item['pid'] ?>">
										<img src="images/cross.svg" class="img-fluid">
									</span>
								</a>
							</div>
				<?php
						}
					}
					else
					{
						echo "<div class='col-12'><h4>No products found</h4></div>";
					}
				?>

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
		document.querySelectorAll('.icon-cross').forEach(button => {
		    button.addEventListener('click', function(e) {
		        e.preventDefault(); // Prevent product link navigation
		        const pid = this.dataset.pid;
		        
		        fetch('functions/handlecart.php', {
		            method: 'POST',
		            headers: {
		                'Content-Type': 'application/x-www-form-urlencoded',
		            },
		            body: `pid=${pid}&quantity=1`
		        })
		        .then(response => response.json())
		        .then(data => {
		            if(data.status === 'success') {
		                // Optional: Show success message
		                //alert('Product added to cart!');
						
		                
		                // Update cart count if you have a cart counter element
		                const cartCount = document.querySelector('.cart-count');
		                if (cartCount) {
		                    cartCount.textContent = data.cartCount;
		                }
		            } else {
		                alert(data.message);
		            }
		        })
		        .catch(error => console.error('Error:', error));
		    });
		});
		</script>
	</body>

</html>
