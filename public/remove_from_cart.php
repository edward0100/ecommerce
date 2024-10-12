<?php
session_start();

// Check if the user is logged in and if the product ID is set
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer' || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$product_id = intval($_GET['id']); 

if (isset($_SESSION['cart'][$product_id])) {

    unset($_SESSION['cart'][$product_id]);

    $_SESSION['success_message'] = "Product removed from cart successfully!";
} else {
    $_SESSION['error_message'] = "Product not found in cart.";
}

header("Location: customer_dashboard.php");
exit();
