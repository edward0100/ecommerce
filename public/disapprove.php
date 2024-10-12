<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    $query = "UPDATE orders SET status = 'disapproved' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    header("Location: orders.php?message=Order disapproved successfully!");
    exit();
} else {
    header("Location: orders.php?message=Invalid request!");
    exit();
}

