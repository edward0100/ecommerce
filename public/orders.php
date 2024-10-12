<?php
include '../config/db.php'; 
include '../includes/navbar.php'; 
include '../includes/footer.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'approve_order') {
    $order_id = $_POST['id'];

    $stmt = $pdo->prepare("SELECT quantity, product_id FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        $quantity = $order['quantity'];
        $product_id = $order['product_id'];

        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
            $stmt->execute([$order_id]);

            $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
            $stmt->execute([$quantity, $product_id]);

            $pdo->commit();
            header("Location: vendor_dashboard.php");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Failed to approve order: ' . $e->getMessage()]);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit();
    }
}

$query = "SELECT o.id, p.name AS product_name, u.username AS username, u.email AS user_email, o.quantity AS quantity, o.status, p.price, p.id AS product_id
          FROM orders AS o
          JOIN products AS p ON o.product_id = p.id
          JOIN users AS u ON o.user_id = u.id
          WHERE p.vendor_id = ?"; 

$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['user_id']]); 

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; 
        }
        .table-container {
            margin-top: 20px;
        }
        .btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center my-4">Your Orders</h2>

    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Username</th>
                    <th>User Email</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                            <td class="quantity-display"><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td class="status-display"><?php echo htmlspecialchars($order['status']); ?></td>
                            <td>$<?php echo number_format($order['price'] * $order['quantity'], 2); ?></td>
                            <td>
                                <?php if ($order['status'] === 'pending'): ?>
                                    <form action="" method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="approve_order">
                                        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </form>
                                    <a href="disapprove_order.php?id=<?php echo $order['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to disapprove this order?')">Disapprove</a>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$pdo = null; 
?>
