<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

if(!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('connect.php');
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
?>

<style>
.small-box {
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
    border-radius: 4px;
}

.small-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.small-box .icon {
    position: absolute;
    right: 15px;
    top: 20px;
    transition: all 0.3s ease;
    opacity: 0.2;
}

.small-box:hover .icon {
    transform: scale(1.1) rotate(5deg);
    opacity: 0.4;
}

.small-box .inner h3 {
    transition: all 0.3s ease;
}

.small-box:hover .inner h3 {
    transform: scale(1.05);
}

.small-box-footer {
    transition: all 0.3s ease;
}

.small-box:hover .small-box-footer {
    background: rgba(0,0,0,0.1);
}

.small-box.bg-info { box-shadow: 0 3px 10px rgba(0,123,255,0.2); }
.small-box.bg-success { box-shadow: 0 3px 10px rgba(40,167,69,0.2); }
.small-box.bg-warning { box-shadow: 0 3px 10px rgba(255,193,7,0.2); }
.small-box.bg-danger { box-shadow: 0 3px 10px rgba(220,53,69,0.2); }

.content-header {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php $query="select count(order_id) from `order`";
                 $result = $conn->query($query);
                 $rowCount = $result->fetch_row()[0]; 
                 echo  $rowCount;
                ?></h3>

                <p>New Orders</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="order.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php $query="select count(pid) from product";
                 $result = $conn->query($query);
                 $rowCount = $result->fetch_row()[0]; 
                 echo  $rowCount;
                ?><h3>

                <p>Products</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="product.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php $query="select count(Cid) from customer";
                 $result = $conn->query($query);
                 $rowCount = $result->fetch_row()[0]; 
                 echo  $rowCount;
                ?></h3>

                <p>User Registrations</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="registerd.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php $query="select count(feedback_id) from feedback";
                 $result = $conn->query($query);
                 $rowCount = $result->fetch_row()[0]; 
                 echo  $rowCount;
                ?></h3>

                <p>Feedbacks</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="feedback.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div><!-- /.row -->
        
        <!-- Charts Row -->
        <div class="row mt-4">
            <!-- Monthly Orders Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Monthly Orders</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="orderChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Top Products Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Top Products</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="productChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Revenue Analysis -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Revenue Analysis</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Customer Growth -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customer Growth</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add this after your existing CSS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart Initialization Script -->
<script>
// Monthly Orders Chart
<?php
$monthlyOrders = $conn->query("
    SELECT 
        DATE_FORMAT(order_date, '%M %Y') as month,
        COUNT(*) as order_count,
        SUM(order_amount) as revenue
    FROM `order`
    WHERE order_date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
    GROUP BY YEAR(order_date), MONTH(order_date)
    ORDER BY order_date ASC
");

$months = [];
$orderCounts = [];
$revenues = [];
while($row = $monthlyOrders->fetch_assoc()) {
    $months[] = $row['month'];
    $orderCounts[] = $row['order_count'];
    $revenues[] = $row['revenue'];
}
?>

new Chart(document.getElementById('orderChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: 'Monthly Orders',
            data: <?php echo json_encode($orderCounts); ?>,
            borderColor: 'rgba(0, 123, 255, 1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Top Products Chart
<?php
$topProducts = $conn->query("
    SELECT 
        p.p_name,
        COUNT(o.pid) as order_count,
        SUM(o.order_amount) as total_revenue,
        p.quantity as stock_level
    FROM `order` o
    JOIN product p ON o.pid = p.pid
    WHERE o.order_date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
    GROUP BY o.pid, p.p_name, p.quantity
    ORDER BY order_count DESC
    LIMIT 5
");

$products = [];
$productData = [];
while($row = $topProducts->fetch_assoc()) {
    $products[] = $row['p_name'];
    $productData[] = [
        'orders' => $row['order_count'],
        'revenue' => $row['total_revenue'],
        'stock' => $row['stock_level']
    ];
}
?>

new Chart(document.getElementById('productChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($products); ?>,
        datasets: [{
            label: 'Orders',
            data: <?php echo json_encode(array_column($productData, 'orders')); ?>,
            backgroundColor: 'rgba(40, 167, 69, 0.8)',
            yAxisID: 'y'
        }, {
            label: 'Stock Level',
            data: <?php echo json_encode(array_column($productData, 'stock')); ?>,
            type: 'line',
            borderColor: 'rgba(220, 53, 69, 1)',
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Orders'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Stock Level'
                },
                grid: {
                    drawOnChartArea: false
                }
            }
        }
    }
});

// Revenue Analysis Chart
<?php
$revenueData = $conn->query("
    SELECT 
        DATE_FORMAT(order_date, '%d %b') as date,
        SUM(order_amount) as total_revenue,
        COUNT(*) as order_count,
        AVG(order_amount) as avg_order_value
    FROM `order`
    WHERE order_date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
    GROUP BY DATE(order_date)
    ORDER BY order_date ASC
");

$dates = [];
$revenues = [];
$avgOrderValues = [];
while($row = $revenueData->fetch_assoc()) {
    $dates[] = $row['date'];
    $revenues[] = round($row['total_revenue'], 2);
    $avgOrderValues[] = round($row['avg_order_value'], 2);
}
?>

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
            label: 'Daily Revenue',
            data: <?php echo json_encode($revenues); ?>,
            borderColor: 'rgba(220, 53, 69, 1)',
            fill: true,
            tension: 0.1
        }, {
            label: 'Average Order Value',
            data: <?php echo json_encode($avgOrderValues); ?>,
            borderColor: 'rgba(40, 167, 69, 1)',
            borderDash: [5, 5],
            fill: false,
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': $' + context.parsed.y;
                    }
                }
            }
        }
    }
});

// Customer Growth Chart
<?php
$customerData = $conn->query("
    WITH RECURSIVE date_range AS (
        SELECT DATE_SUB(CURRENT_DATE, INTERVAL 11 MONTH) AS date
        UNION ALL
        SELECT DATE_ADD(date, INTERVAL 1 MONTH)
        FROM date_range
        WHERE date < CURRENT_DATE
    )
    SELECT 
        DATE_FORMAT(dr.date, '%M %Y') as month,
        COALESCE(COUNT(DISTINCT o.cid), 0) as active_customers,
        COALESCE(COUNT(f.feedback_id), 0) as feedback_count
    FROM date_range dr
    LEFT JOIN `order` o ON DATE_FORMAT(dr.date, '%Y-%m') = DATE_FORMAT(o.order_date, '%Y-%m')
    LEFT JOIN feedback f ON DATE_FORMAT(dr.date, '%Y-%m') = DATE_FORMAT(f.feedback_date, '%Y-%m')
    GROUP BY dr.date
    ORDER BY dr.date ASC
");

$growthMonths = [];
$activeCustomers = [];
$feedbackCounts = [];
while($row = $customerData->fetch_assoc()) {
    $growthMonths[] = $row['month'];
    $activeCustomers[] = $row['active_customers'];
    $feedbackCounts[] = $row['feedback_count'];
}
?>

new Chart(document.getElementById('customerChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($growthMonths); ?>,
        datasets: [{
            label: 'Active Customers',
            data: <?php echo json_encode($activeCustomers); ?>,
            backgroundColor: 'rgba(255, 193, 7, 0.8)'
        }, {
            label: 'Feedback Received',
            data: <?php echo json_encode($feedbackCounts); ?>,
            backgroundColor: 'rgba(23, 162, 184, 0.8)'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

<?php include('script.php');?>

<?php
include('includes/footer.php');
?>