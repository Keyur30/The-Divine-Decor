<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../connect.php';

function checkProductQuantity($pid) {
    global $con;
    $query = "SELECT quantity FROM product WHERE pid = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $pid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
        return $row['quantity'];
    }
    return 0;
}

// Add function to check stock quantity
function getAvailableStock($pid) {
    global $con;
    $query = "SELECT quantity FROM product WHERE pid = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $pid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
        return intval($row['quantity']);
    }
    return 0;
}

if(isset($_POST['action']) && isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $action = $_POST['action'];
    
    if(isset($_SESSION['cart'][$pid])) {
        $availableStock = getAvailableStock($pid);
        
        switch($action) {
            case 'increase':
                if($_SESSION['cart'][$pid]['quantity'] >= $availableStock) {
                    $response = [
                        'status' => 'success',
                        'newQuantity' => $_SESSION['cart'][$pid]['quantity'],
                        'maxQuantity' => $availableStock,
                        'newSubtotal' => number_format($_SESSION['cart'][$pid]['price'] * $_SESSION['cart'][$pid]['quantity'], 2),
                        'newTotal' => number_format(calculateTotal(), 2),
                        'cartCount' => count($_SESSION['cart'])
                    ];
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }
                $_SESSION['cart'][$pid]['quantity']++;
                break;
            case 'decrease':
                if($_SESSION['cart'][$pid]['quantity'] > 1) {
                    $_SESSION['cart'][$pid]['quantity']--;
                }
                break;
            case 'remove':
                unset($_SESSION['cart'][$pid]);
                break;
        }
        
        // Calculate new values
        $item = $_SESSION['cart'][$pid] ?? null;
        $subtotal = $item ? $item['price'] * $item['quantity'] : 0;
        $total = calculateTotal();
        
        $response = [
            'status' => 'success',
            'newQuantity' => $item ? $item['quantity'] : 0,
            'newSubtotal' => number_format($subtotal, 2),
            'newTotal' => number_format($total, 2),
            'cartCount' => count($_SESSION['cart']),
            'maxQuantity' => $availableStock
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Product not found in cart'
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

function calculateTotal() {
    $total = 0;
    if(isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}
?>
