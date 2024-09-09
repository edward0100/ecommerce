<?php
session_start();
include '../config/db.php'; 

// Ensure the user is logged in as a vendor
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'vendor') {
    header("Location: login.php");
    exit();
}

// Check if the product ID is set in the URL
if (!isset($_GET['id'])) {
    echo "No product ID provided.";
    exit();
}

$product_id = $_GET['id'];

// Ensure the vendor owns the product they're trying to delete
$query = "SELECT * FROM products WHERE id = ? AND vendor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found or you're not authorized to delete this product.";
    exit();
}

// Delete the product image from the server if it exists
$image_path = '../uploads/' . $product['image'];
if (file_exists($image_path)) {
    unlink($image_path);  // Delete the file
}

// Delete the product from the database
$delete_query = "DELETE FROM products WHERE id = ? AND vendor_id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("ii", $product_id, $_SESSION['user_id']);

if ($delete_stmt->execute()) {
    echo "<div class='alert alert-success'>Product deleted successfully.</div>";
    header("Location: vendor_dashboard.php");
    exit();
} else {
    echo "<div class='alert alert-danger'>Error deleting product: " . $delete_stmt->error . "</div>";
}

$delete_stmt->close();
$stmt->close();
