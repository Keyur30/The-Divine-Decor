<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'connect.php';
include('functions/myfunctions.php');

$sub_category_id = isset($_GET['subcategory']) ? $_GET['subcategory'] : null;

if(isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Check if subcategory exists and has products
    if($sub_category_id) {
        $sub_category_products = getProductsBySubCategory($sub_category_id);
        if(mysqli_num_rows($sub_category_products) == 0) {
            echo "<div class='container mt-5'><div class='alert alert-warning'>No products available in this sub-category</div></div>";
            exit();
        }
    }
    
    $product = getProductById($product_id);
    
    if($product && mysqli_num_rows($product) > 0) {
        $item = mysqli_fetch_assoc($product);
        
        // Check if product is out of stock
        if($item['quantity'] <= 0) {
            echo "<div class='container mt-5'><div class='alert alert-warning'>Sorry, this product is currently out of stock.</div></div>";
            exit();
        }
        
        // If subcategory filter is set and doesn't match, redirect
        if($sub_category_id && $item['sub_category_id'] != $sub_category_id) {
            header("Location: shop.php");
            exit();
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $item['p_name'] ?> - The Divine Decor</title>
    
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="css/tiny-slider.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
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
        
        /* Add new product container styles */
        .product-container {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 15px;
            padding: 30px;
            background: #fff;
            margin: 20px 0;
            transition: box-shadow 0.3s ease;
        }
        
        .product-container:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }
        
        .product-image {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        /* Add share link styles */
        .share-links a {
            font-size: 20px;
            margin-right: 15px;
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background:rgba(249, 249, 249, 0.87);
            box-shadow: inset 0 0 0 0.4px rgba(16, 4, 4, 0.42);
        }
        
        .share-links a:hover {
            transform: translateY(-3px);
            background: #3b5d50;
            color: white !important;
        }
	</style>
</head>
<body>
    <?php include 'includes/nav.php'; ?>
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center product-container">
                <div class="col-md-6">
                    <img class="card-img-top mb-5 mb-md-0 product-image" 
                         src="../gallery/<?= $item['p_image'] ?>" 
                         alt="<?= $item['p_name'] ?>"
                         style="width: 100%; height: 500px; object-fit: contain;">
                </div>
                <div class="col-md-6">
                    <h1 class="display-5 fw-bolder"><?= $item['p_name'] ?></h1>
                    <div class="fs-5 mb-5">
                        <span>₹<?= $item['p_price'] ?></span>
                    </div>
                    <p class="lead"><?= $item['p_description'] ?></p>
                    
                    <form id="addToCartForm" class="d-flex">
                        <input type="hidden" name="pid" value="<?= $item['pid'] ?>">
                        <input class="form-control text-center me-3" 
                               type="number" name="quantity" value="1" min="1" max="<?= $item['quantity'] ?>"
                               style="max-width: 5rem">

                        <!-- add to cart start -->
                        <button class="btn btn-outline-dark flex-shrink-0" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Add to cart
                        </button>
                        <!-- add to cart end -->
                    </form>

                    <div id="alertMessage" class="mt-3" style="display: none;"></div>

                    <div class="mt-4">
                        <h6>Share:</h6>
                        <?php
                        $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $share_text = urlencode("Check out {$item['p_name']} on The Divine Decor!");
                        $image_url = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/gallery/{$item['p_image']}");
                        ?>
                        <div class="d-flex gap-2 share-links">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $current_url ?>" 
                               target="_blank" class="text-dark">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?= $share_text ?>&url=<?= $current_url ?>" 
                               target="_blank" class="text-dark">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://pinterest.com/pin/create/button/?url=<?= $current_url ?>&media=<?= $image_url ?>&description=<?= $share_text ?>" 
                               target="_blank" class="text-dark">
                                <i class="fab fa-pinterest"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?= $share_text ?>%20<?= $current_url ?>" 
                               target="_blank" class="text-dark">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products Section -->
    <section class="py-5 bg-light">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="fw-bolder mb-4">Related Products</h2>
            <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-2 row-cols-xl-4 justify-content-center">
                <?php
                // Use subcategory filter if available
                $related_products = $sub_category_id 
                    ? getProductsBySubCategory($sub_category_id, $item['pid'])
                    : getRelatedProducts($item['pid'], $item['sub_category_id']);

                if(mysqli_num_rows($related_products) > 0) {
                    while($related_item = mysqli_fetch_assoc($related_products)) {
                        ?>
                        <div class="col mb-5">
                            <div class="card h-100">
                                <img class="card-img-top" 
                                     src="../gallery/<?= $related_item['p_image'] ?>" 
                                     alt="<?= $related_item['p_name'] ?>"
                                     style="height: 300px; object-fit: contain; width: 100%;">
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <h5 class="fw-bolder"><?= $related_item['p_name'] ?></h5>
                                        <div class="price-wrap mb-3">
                                            <span class="price">₹<?= $related_item['p_price'] ?></span>
                                        </div>
                                        <a class="btn btn-outline-dark mt-auto" 
                                           href="product.php?id=<?= $related_item['pid'] ?>">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-center'>No related products found</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Include Feedback Section -->
    <?php include 'feedback.php'; ?>

    <?php require_once('includes/footer.php'); ?>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/tiny-slider.js"></script>
    <script src="js/custom.js"></script>
    <script>
        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const quantity = parseInt(formData.get('quantity'));
            const maxQuantity = parseInt(this.querySelector('input[name="quantity"]').getAttribute('max'));
            
            if(quantity > maxQuantity) {
                alert('Selected quantity exceeds available stock!');
                return;
            }
            
            fetch('functions/handlecart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alertDiv = document.getElementById('alertMessage');
                alertDiv.style.display = 'block';
                alertDiv.className = `alert alert-${data.status === 'success' ? 'success' : 'danger'}`;
                alertDiv.textContent = data.message;
                
                if(data.status === 'success') {
                    // Update cart count in navbar if you have a cart counter element
                    const cartBadge = document.querySelector('.cart-count');
                    if(cartBadge) {
                        cartBadge.textContent = data.cartCount;
                    }
                    
                    // Redirect to cart page after successful addition
                    setTimeout(() => {
                        window.location.href = 'cart.php';
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
<?php
    } else {
        echo "Product not found";
    }
} else {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Invalid request</div></div>";
}
?>
