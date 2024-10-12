<?php
include '../includes/navbar.php';
require '../config/db.php';  

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$cart_items = $_SESSION['cart'] ?? [];
$total_price = 0;

$username = $_SESSION['username'] ?? '';

if (empty($username)) {
    echo "<div class='alert alert-danger'>Error: User information is missing.</div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_items = $_POST['cart_items'] ?? [];
    $query = "INSERT INTO cart (user_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    try {
        $pdo->beginTransaction(); 

        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare($query);
            $stmt->execute([$_SESSION['user_id'], $item['product_id'], $item['quantity'], $item['price']]);
        }

        $pdo->commit(); 
        header("Location: checkout.php"); 
        exit();

    } catch (Exception $e) {
        $pdo->rollBack(); 
        echo "<div class='alert alert-danger'>Error: Could not add items to the cart. Please try again.</div>";
        error_log($e->getMessage());
    }
}
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
            background-color: #f0f8ff; 
        }
        .cart-container {
            margin-top: 20px;
        }
        .cart-item img {
            max-width: 100px;
            height: auto;
        }
        .remove-btn {
            color: red;
        }
    </style>
</head>
<body>

<div class="container cart-container">
    <h2 class="text-center my-4">Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info text-center" role="alert">
            Your cart is empty.
        </div>
    <?php else: ?>
        <form action="" method="POST"> 
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $product_id => $item): ?>
                        <tr class="cart-item">
                            <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" name="cart_items[<?php echo $product_id; ?>][quantity]" value="<?php echo $item['quantity']; ?>" min="1" required>
                            </td>
                            <td>
                                <a href="remove_from_cart.php?id=<?php echo $product_id; ?>" class="remove-btn">Remove</a>
                            </td>
                        </tr>
                        <input type="hidden" name="cart_items[<?php echo $product_id; ?>][product_id]" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="cart_items[<?php echo $product_id; ?>][name]" value="<?php echo htmlspecialchars($item['name']); ?>">
                        <input type="hidden" name="cart_items[<?php echo $product_id; ?>][price]" value="<?php echo $item['price']; ?>">
                    <?php $total_price += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-center my-4">
                <h4>Total Price: $<?php echo number_format($total_price, 2); ?></h4>
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <button type="submit" class="btn btn-success btn-lg">Proceed to Checkout</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$pdo = null;
?>
