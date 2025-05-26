<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include 'connect.php';
include('functions/myfunctions.php');
include 'includes/header.php';

if(!isset($_GET['order_id'])) {
    header("location: orders.php");
    exit;
}

$order_id = $_GET['order_id'];
$customer_id = $_SESSION['cid'];

// Calculate the total amount for all products in the order
$total_query = "SELECT SUM(p.p_price * o.quantity) as total_amount
                FROM `order` o 
                JOIN product p ON o.pid = p.pid 
                WHERE o.order_details_id = (
                    SELECT order_details_id 
                    FROM `order` 
                    WHERE order_id = ? AND cid = ?
                )";

$total_stmt = $con->prepare($total_query);
$total_stmt->bind_param("ii", $order_id, $customer_id);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_amount = $total_row['total_amount'];

// Updated query to get all products from the same order_details_id
$query = "SELECT o.*, p.p_name, p.p_price, p.p_image, p.p_description 
          FROM `order` o 
          JOIN product p ON o.pid = p.pid 
          WHERE o.order_details_id = (
              SELECT order_details_id 
              FROM `order` 
              WHERE order_id = ? AND cid = ?
          )";

$stmt = $con->prepare($query);
$stmt->bind_param("ii", $order_id, $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0) {
    header("location: orders.php");
    exit;
}

// Get the first row for order information
$first_order = $result->fetch_assoc();
$result->data_seek(0); // Reset pointer to get all products again
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Details - The Divine Decor</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/nav.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Order #<?= $first_order['order_id'] ?></h4>
                        <span class="badge <?= ($first_order['order_status'] == 'Completed') ? 'bg-success' : 'bg-warning'; ?> fs-6">
                            <?= $first_order['order_status'] ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2">Order Information</h5>
                                <p><strong>Order Date:</strong> <?= date("d M Y", strtotime($first_order['order_date'])) ?></p>
                                <p><strong>Contact Number:</strong> <?= $first_order['Contact_no'] ?></p>
                                <p><strong>Delivery Address:</strong> <?= $first_order['address'] ?></p>
                                <p><strong>Total Amount:</strong> ₹<?= number_format($total_amount, 2) ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2">Products</h5>
                                <?php while ($product = $result->fetch_assoc()): ?>
                                <div class="product-details mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <?php
                                            $image_path = "../gallery/" . $product['p_image'];
                                            if (!empty($product['p_image']) && file_exists($image_path)) {
                                                $img_src = $image_path;
                                            } else {
                                                $img_src = "assets/images/no-image.jpg"; // Create a default "no image" placeholder
                                            }
                                            ?>
                                            <img src="<?= $img_src ?>" class="img-fluid rounded" alt="<?= $product['p_name'] ?>">
                                        </div>
                                        <div class="col-md-9">
                                            <h6><?= $product['p_name'] ?></h6>
                                            <p class="mb-1"><strong>Price:</strong> ₹<?= number_format($product['p_price'], 2) ?></p>
                                            <p class="mb-1"><strong>Quantity:</strong> <?= $product['quantity'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <?php if ($first_order['order_status'] == 'Pending'): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Your order is being processed. We'll update you once it's confirmed.
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
                        <?php if ($first_order['order_status'] == 'Pending'): ?>
                        <button class="btn btn-danger float-end cancel-order" data-order-id="<?= $first_order['order_id'] ?>">
                            Cancel Order
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cancelBtn = document.querySelector('.cancel-order');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                if(confirm('Are you sure you want to cancel this order?')) {
                    const orderId = this.dataset.orderId;
                    
                    fetch('functions/cancel_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'order_id=' + orderId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.status === 'success') {
                            window.location.href = 'orders.php';
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while cancelling the order');
                    });
                }
            });
        }
    });
    </script>
</body>
</html>