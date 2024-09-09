<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];

$query = "
    SELECT o.id, o.total_price, o.status, oi.quantity, oi.price, p.name 
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.customer_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<script src="js/scripts.js"></script>
<head>
    <title>Your Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Your Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orders->fetch_assoc()): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['name']; ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td>$<?php echo $order['price']; ?></td>
                <td>$<?php echo $order['total_price']; ?></td>
                <td><?php echo $order['status']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>