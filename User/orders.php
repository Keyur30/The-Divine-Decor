<?php
// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, if not redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include 'connect.php';
include('functions/myfunctions.php');
include 'includes/header.php';

$customer_id = $_SESSION['cid'];

// Check if the table exists
$table_check = $con->query("SHOW TABLES LIKE 'order'");
if($table_check->num_rows == 0) {
    die("Error: Orders table does not exist. Please contact administrator.");
}

// Use prepared statements to prevent SQL injection
$query = "SELECT DISTINCT od.order_details_id, od.p_price as total_amount, o.quantity, o.order_date,
          o.order_id, o.order_status
          FROM `order` o 
          JOIN order_details od ON o.order_details_id = od.order_details_id 
          WHERE o.cid = ? 
          GROUP BY od.order_details_id
          ORDER BY o.order_date DESC";
$stmt = $con->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Query failed: " . $con->error);
}
?>
<?php if (isset($_SESSION['message'])) { ?>
    <div class="alert alert-info"><?= $_SESSION['message']; ?></div>
    <?php unset($_SESSION['message']); ?>
<?php } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Orders - The Divine Decor</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/nav.php'; ?>

    <div class="container my-5">
        <h2 class="text-center mb-4">My Orders</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Order Details ID</th>
                        <!-- <th>Product Price</th> -->
                        <th>Quantity</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>#<?= $row['order_details_id'] ?></td>
                                <!-- <td>₹<= number_format($row['p_price'], 2) ?></td> -->
                                <td><?= $row['quantity'] ?></td>
                                <td><?= date("d M Y", strtotime($row['order_date'])) ?></td>
                                <td>₹<?= number_format($row['total_amount'], 2) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="order_details.php?order_id=<?= $row['order_id'] ?>" class="btn btn-primary btn-sm">View Details</a>
                                        <?php if ($row['order_status'] == 'Pending') { ?>
                                            <button class="btn btn-danger btn-sm ms-2 cancel-order" data-order-id="<?= $row['order_id'] ?>">Cancel Order</button>
                                        <?php } ?>
                                        <?php if ($row['order_status'] == 'Completed') { ?>
                                            <a href="generate_invoice.php?order_id=<?= $row['order_id'] ?>" class="btn btn-secondary btn-sm ms-2">Invoice</a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No orders found</td></tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- <php include('includes/footer.php'); ?> -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.cancel-order').forEach(button => {
                button.addEventListener('click', function() {
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
                                location.reload();
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
            });
        });
    </script>
</body>
</html>
