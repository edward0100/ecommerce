<?php
include '../config/db.php'; 
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$product_id]);

    header("Location: vendor_dashboard.php?message=Product deleted successfully!");
    exit();
} else {
    header("Location: vendor_dashboard.php?message=Invalid product ID.");
    exit();
}
