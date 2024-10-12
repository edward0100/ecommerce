<?php
session_start();
require '../config/db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);  
    $quantity = intval($_POST['quantity']);      

    if ($product_id <= 0 || $quantity <= 0) {
        $_SESSION['error_message'] = "Invalid product ID or quantity.";
        header("Location: customer_dashboard.php");
        exit();
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $query = "SELECT p.name, p.price, p.image, p.quantity, u.username 
                  FROM products p 
                  JOIN users u ON p.vendor_id = u.id 
                  WHERE p.id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            if ($product['quantity'] >= $quantity) {
                $_SESSION['cart'][$product_id] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $quantity,
                    'vendor_username' => $product['username'] 
                ];
            } else {
                $_SESSION['error_message'] = "Not enough stock available.";
                header("Location: customer_dashboard.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Product not found.";
            header("Location: customer_dashboard.php");
            exit();
        }
    }

    $_SESSION['success_message'] = "Product added to cart successfully!";
    header("Location: customer_dashboard.php");
    exit();

} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: customer_dashboard.php");
    exit();
}
