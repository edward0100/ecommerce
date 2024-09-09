<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found!";
    exit();
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// If product already in the cart, increase quantity
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    // Add new product to cart
    $_SESSION['cart'][$product_id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1
    ];
}

header("Location: view_cart.php");