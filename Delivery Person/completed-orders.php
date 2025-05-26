<?php
session_start();
if(!isset($_SESSION['auth']) || $_SESSION['auth'] != true) {
    header('Location: login.php');
    exit();
}
include('../config/dbcon.php');
include('includes/header.php');

$delivery_id = $_SESSION['delivery_id'];
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Completed Orders</h1>
    <div class="card mb-4">
        <div class="card-header">
            <h4>Order List</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order Details ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Quantity</th>
                        <th>Order Date</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT od.order_details_id, o.order_date, c.C_name, c.Address,
                           od.quantity, od.p_price as order_amount, o.order_status,
                           p.payment_status
                           FROM order_details od
                           JOIN `order` o ON o.order_details_id = od.order_details_id 
                           JOIN customer c ON o.cid = c.Cid 
                           LEFT JOIN payment p ON o.order_id = p.order_id
                           WHERE od.delivery_person_id = '$delivery_id' 
                           AND o.order_status = 'Completed'
                           GROUP BY od.order_details_id
                           ORDER BY o.order_date DESC";
                    $result = mysqli_query($con, $sql);
                    
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td>#<?= $row['order_details_id'] ?></td>
                                <td><?= $row['C_name'] ?></td>
                                <td><?= $row['Address'] ?></td>
                                <td>₹<?= number_format($row['order_amount'], 2) ?></td>
                                <td><?= $row['quantity'] ?></td>
                                <td><?= date('d M Y', strtotime($row['order_date'])) ?></td>
                                <td><?= $row['payment_status'] ?></td>
                                <td>
                                    <?php if($row['payment_status'] == 'Pending'): ?>
                                    <form action="update_payment.php" method="POST">
                                        <input type="hidden" name="order_details_id" value="<?= $row['order_details_id'] ?>">
                                        <button type="submit" name="mark_paid" class="btn btn-success btn-sm">
                                            Mark as Paid
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="8" class="text-center">No Completed Orders Found</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
