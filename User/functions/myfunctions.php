<?php
include('connect.php');

function getAll($table)
{
    global $con;
    $query = "SELECT * FROM $table";
    return mysqli_query($con, $query);
}

function getById($table, $id)
{
    global $con;
    $query = "SELECT * FROM $table WHERE id = $id";
    return mysqli_query($con, $query);
}

function getAllActive($table)
{
    global $con;
    $query = "SELECT * FROM $table";
    return mysqli_query($con, $query);
}

function getSubCategories($category_id) {
    global $con;
    $query = "SELECT * FROM sub_category WHERE category_id = '$category_id'";
    return mysqli_query($con, $query);
}

function getProductsByCategory($category_id, $subcategory_id = null) {
    global $con;
    if($subcategory_id) {
        $query = "SELECT * FROM product WHERE sub_category_id = '$subcategory_id'";
    } else {
        // Here 'p' is an alias for 'product' table
        $query = "SELECT p.* FROM product p 
                  INNER JOIN sub_category s ON p.sub_category_id = s.sub_category_id 
                  WHERE s.category_id = '$category_id'";
    }
    return mysqli_query($con, $query);
}

function getProductById($product_id) {
    global $con;
    // Here 'p' is an alias for 'product' table
    $query = "SELECT p.*, c.category_name, sc.sub_category_name, sc.sub_category_id
              FROM product p 
              LEFT JOIN sub_category sc ON p.sub_category_id = sc.sub_category_id
              LEFT JOIN category c ON sc.category_id = c.category_id
              WHERE p.pid = '$product_id'";
    return mysqli_query($con, $query);
}

function getRelatedProducts($product_id, $sub_category_id, $limit = 4) {
    global $con;
    // Here 'p' is an alias for 'product' table
    $query = "SELECT p.*, sc.sub_category_name 
              FROM product p
              INNER JOIN sub_category sc ON p.sub_category_id = sc.sub_category_id
              WHERE p.sub_category_id = '$sub_category_id' 
              AND p.pid != '$product_id'
              LIMIT $limit";
    return mysqli_query($con, $query);
}

function getProductsBySubCategory($sub_category_id, $exclude_product_id = null) {
    global $con;
    $query = "SELECT p.*, sc.sub_category_name 
              FROM product p
              INNER JOIN sub_category sc ON p.sub_category_id = sc.sub_category_id
              WHERE p.sub_category_id = '$sub_category_id'";
    
    if($exclude_product_id) {
        $query .= " AND p.pid != '$exclude_product_id'";
    }
    
    $result = mysqli_query($con, $query);
    return $result;
}

function getFeaturedProducts()
{
    global $con;
    $query = "SELECT * FROM products WHERE status='0' AND trending='1' LIMIT 8";
    return mysqli_query($con, $query);
}

function getMostPurchasedProducts()
{
    global $con;
    $query = "SELECT p.*, COUNT(o.pid) as purchase_count 
              FROM product p 
              LEFT JOIN `order` o ON p.pid = o.pid 
              GROUP BY p.pid 
              ORDER BY purchase_count DESC 
              LIMIT 8";
    return mysqli_query($con, $query);
}

function redirect($url, $message)
{
    $_SESSION['message'] = $message;
    header('Location: ' .$url);
    exit(0);
}

?>