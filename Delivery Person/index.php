<?php 
session_start();
if(!isset($_SESSION['auth']) || $_SESSION['auth'] != true) {
    header('Location: login.php');
    exit();
}
include('../config/dbcon.php');
include('includes/header.php');

// Get delivery person details
$delivery_id = $_SESSION['delivery_id'];
$delivery_query = "SELECT * FROM delivery_person WHERE delivery_person_id = '$delivery_id'";
$delivery_result = mysqli_query($con, $delivery_query);
$delivery_data = mysqli_fetch_assoc($delivery_result);
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Welcome, <?= $delivery_data['Name'] ?></h1>
    <div class="row">
        <!-- Today's Deliveries -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h5>Today's Deliveries</h5>
                    <?php
                    $today = date('Y-m-d');
                    $sql = "SELECT COUNT(*) as count FROM `order` o 
                           JOIN order_details od ON o.order_details_id = od.order_details_id 
                           WHERE od.delivery_person_id = '$delivery_id' 
                           AND DATE(o.order_date) = '$today'";
                    $result = mysqli_query($con, $sql);
                    $count = mysqli_fetch_assoc($result)['count'];
                    ?>
                    <h2><?= $count ?></h2>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h5>Pending Orders</h5>
                    <?php
                    $sql = "SELECT COUNT(*) as count FROM `order` o 
                           JOIN order_details od ON o.order_details_id = od.order_details_id 
                           WHERE od.delivery_person_id = '$delivery_id' 
                           AND o.order_status='Pending'";
                    $result = mysqli_query($con, $sql);
                    $count = mysqli_fetch_assoc($result)['count'];
                    ?>
                    <h2><?= $count ?></h2>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h5>Completed Orders</h5>
                    <?php
                    $sql = "SELECT COUNT(*) as count FROM `order` o 
                           JOIN order_details od ON o.order_details_id = od.order_details_id 
                           WHERE od.delivery_person_id = '$delivery_id' 
                           AND o.order_status='Completed'";
                    $result = mysqli_query($con, $sql);
                    $count = mysqli_fetch_assoc($result)['count'];
                    ?>
                    <h2><?= $count ?></h2>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <?php
                    $sql = "SELECT COUNT(*) as count FROM `order` o 
                           JOIN order_details od ON o.order_details_id = od.order_details_id 
                           WHERE od.delivery_person_id = '$delivery_id'";
                    $result = mysqli_query($con, $sql);
                    $count = mysqli_fetch_assoc($result)['count'];
                    ?>
                    <h2><?= $count ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Recent Orders</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order Details ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT DISTINCT od.order_details_id, o.order_date, c.C_name, 
                                   od.quantity, od.p_price as order_amount, o.order_status
                                   FROM order_details od
                                   JOIN `order` o ON o.order_details_id = od.order_details_id 
                                   JOIN customer c ON o.cid = c.Cid 
                                   WHERE od.delivery_person_id = '$delivery_id' 
                                   GROUP BY od.order_details_id
                                   ORDER BY o.order_date DESC LIMIT 5";
                            $result = mysqli_query($con, $sql);
                            while($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td>#<?= $row['order_details_id'] ?></td>
                                <td><?= $row['C_name'] ?></td>
                                <td>₹<?= number_format($row['order_amount'], 2) ?></td>
                                <td><?= $row['quantity'] ?></td>
                                <td>
                                    <span class="badge <?= ($row['order_status'] == 'Completed') ? 'bg-success' : 'bg-warning' ?>">
                                        <?= htmlspecialchars($row['order_status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($row['order_date'])) ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
