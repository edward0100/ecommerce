<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html>
<script src="js/scripts.js"></script>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Your Cart</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($cart)): ?>
                <tr>
                    <td colspan="5">Your cart is empty!</td>
                </tr>
            <?php else: ?>
                <?php foreach ($cart as $id => $product): ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td>$<?php echo $product['price']; ?></td>
                    <td><?php echo $product['quantity']; ?></td>
                    <td>$<?php echo $product['price'] * $product['quantity']; ?></td>
                    <td>
                        <a href="remove_from_cart.php?id=<?php echo $id; ?>" class="btn btn-danger">Remove</a>
                    </td>
                </tr>
                <?php $total += $product['price'] * $product['quantity']; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <h3>Total: $<?php echo $total; ?></h3>
    <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
</div>
</body>
</html>