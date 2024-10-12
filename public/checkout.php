<?php
include '../config/db.php'; 
include '../includes/navbar.php';  

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT c.product_id, p.name AS product_name, p.price, c.quantity, u.username AS vendor_username 
          FROM cart AS c
          JOIN products AS p ON c.product_id = p.id
          JOIN users AS u ON p.vendor_id = u.id
          WHERE c.user_id = ?";

$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);

if ($stmt->rowCount() === 0) {
    echo "<h3>No products in the cart. Please add products first from the customer dashboard.</h3>";
    exit();
}

$cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
foreach ($cart_products as $product) {
    $total_price += $product['price'] * $product['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_query = "INSERT INTO orders (user_id, product_id, quantity, total_price, payment_method, payment_details) VALUES (?, ?, ?, ?, ?, ?)";
    $delete_cart_query = "DELETE FROM cart WHERE user_id = ?";

    try {
        $pdo->beginTransaction();
        
        foreach ($cart_products as $product) {
            $stmt_order = $pdo->prepare($order_query);
            $stmt_order->execute([
                $user_id,
                $product['product_id'],
                $product['quantity'],
                $total_price, 
                $_POST['payment_method'],
                $_POST['payment_details']
            ]);
        }

        $stmt_delete_cart = $pdo->prepare($delete_cart_query);
        $stmt_delete_cart->execute([$user_id]);

        $pdo->commit();
        
        $_SESSION['success_message'] = "Order placed successfully!";
        
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        error_log($e->getMessage()); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; 
        }
        .table-container {
            margin-top: 20px;
        }
        .btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center my-4">Checkout</h2>

    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Vendor Username</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo htmlspecialchars($product['vendor_username']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <form action="" method="POST"> 
        <h4 class="mt-4">Payment Information</h4>
          <div class="form-group">
            <label for="payment_method">Select Payment Method:</label>
            <select name="payment_method" id="payment_method" class="form-control" required>
                <option value="credit_card">Credit Card</option>
                <option value="mobile_wallet">Mobile Wallet</option>
            </select>
        </div>

        <div class="form-group">
            <label for="payment_details">Enter Card Number or Mobile Wallet Number:</label>
            <input type="text" name="payment_details" id="payment_details" class="form-control" required>
        </div>
        <input type="hidden" name="cart_items" value='<?php echo json_encode($cart_products); ?>'>

        <button type="submit" class="btn btn-primary">Finish Order</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$pdo = null; 
?>
