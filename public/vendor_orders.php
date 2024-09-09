<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
    header("Location: login.php");
    exit();
}

$vendor_id = $_SESSION['user_id'];

// Update order status if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();

    header("Location: vendor_orders.php");
    exit();
}

// Fetch vendor orders
$query = "
    SELECT o.id, o.customer_id, o.total_price, o.status, oi.quantity, oi.price, p.name 
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE p.vendor_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<script src="js/scripts.js"></script>
<head>
    <title>Vendor Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Vendor Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orders->fetch_assoc()): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['customer_id']; ?></td>
                <td><?php echo $order['name']; ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td>$<?php echo $order['total_price']; ?></td>
                <td><?php echo $order['status']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status" class="form-control">
                            <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Shipped" <?php echo $order['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="Delivered" <?php echo $order['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                        </select>
                        <button type="submit" class="btn btn-primary mt-2">Update Status</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>