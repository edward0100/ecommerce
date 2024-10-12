<?php
session_start();
require '../config/db.php';  

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['cart_items']) && !empty($_POST['cart_items'])) {
    $user_id = $_SESSION['user_id'];
    $cart_items = $_POST['cart_items'];

    $pdo->beginTransaction();

    try {

        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->execute([$user_id, $item['product_id'], $item['quantity'], $item['price']]);
        }

        $pdo->commit();

        $_SESSION['success_message'] = "Products successfully added to cart!";
        header("Location: checkout.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();

        error_log($e->getMessage());
        $_SESSION['error_message'] = "Failed to add products to cart. Please try again.";
        header("Location: customer_dashboard.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "No items in cart.";
    header("Location: customer_dashboard.php");
    exit();
}
