<?php
session_start();
include '../config/db.php'; 
include '../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit;
}

// Display Cart Items
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .dashboard-header {
            text-align: center;
            padding: 50px 0;
            background: #007bff;
            color: white;
        }
        .cart-table {
            margin-top: 30px;
        }
        .cart-table th, .cart-table td {
            text-align: center;
            vertical-align: middle;
        }
        .cart-empty {
            text-align: center;
            margin-top: 50px;
        }
        .cart-total {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .checkout-btn {
            font-size: 1.2rem;
            padding: 10px 20px;
        }
    </style>
</head>
<body>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1>Welcome to Your Dashboard</h1>
        <p class="lead">Here is your shopping cart and recent activity</p>
    </div>

    <div class="container">
        <!-- Cart Section -->
        <h3 class="my-4">Your Cart</h3>

        <?php if (count($cart_items) > 0): ?>
            <table class="table table-bordered table-hover cart-table">
                <thead class="thead-dark">
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    foreach ($cart_items as $item): 
                        $total = $item['price'] * $item['quantity'];
                        $grand_total += $total;
                    ?>
                    <tr>
                        <td><img src="uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="img-fluid" style="max-width: 80px;"></td>
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($total, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Total and Checkout -->
            <div class="text-right mt-4">
                <p class="cart-total">Grand Total: $<?php echo number_format($grand_total, 2); ?></p>
                <a href="checkout.php" class="btn btn-success checkout-btn">Proceed to Checkout</a>
            </div>

        <?php else: ?>
            <p class="cart-empty">Your cart is currently empty.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>