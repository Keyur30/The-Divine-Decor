<?php
session_start();
include('../connect.php');
include('myfunctions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pid']) && isset($_POST['quantity'])) {
        $pid = $_POST['pid'];
        $quantity = (int)$_POST['quantity'];
        
        // Verify product exists and has enough quantity
        $product = getProductById($pid);
        if ($product && mysqli_num_rows($product) > 0) {
            $productData = mysqli_fetch_assoc($product);
            
            if ($productData['quantity'] >= $quantity) {
                // Initialize cart if it doesn't exist
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = array();
                }
                
                // Check if product already exists in cart
                if (isset($_SESSION['cart'][$pid])) {
                    // Update quantity
                    $_SESSION['cart'][$pid]['quantity'] += $quantity;
                } else {
                    // Add new product to cart
                    $_SESSION['cart'][$pid] = array(
                        'pid' => $pid,
                        'name' => $productData['p_name'],
                        'price' => $productData['p_price'],
                        'image' => $productData['p_image'],
                        'quantity' => $quantity
                    );
                }
                
                $response = array(
                    'status' => 'success',
                    'message' => 'Product added to cart successfully',
                    'cartCount' => count($_SESSION['cart'])
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Requested quantity not available'
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Product not found'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Invalid request'
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
