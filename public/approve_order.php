<?php
session_start();
include '../config/db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$order_id = $_POST['id'];
$quantity = $_POST['quantity'];
$product_id = $_POST['product_id'];

$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
    $stmt->execute([$order_id]);

    $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
    $stmt->execute([$quantity, $product_id]);

    $pdo->commit();

    $stmt = $pdo->prepare("SELECT quantity FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $new_quantity = $stmt->fetchColumn();

    echo json_encode(['success' => true, 'new_quantity' => $new_quantity]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Failed to approve order: ' . $e->getMessage()]);
}
