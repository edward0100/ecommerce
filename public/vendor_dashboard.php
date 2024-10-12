<?php
include '../config/db.php'; 
include '../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM products WHERE vendor_id = ?"; 
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; 
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            padding: 10px;
            text-align: center;
            width: 300px; 
            height: 400px; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
        }
        .product-card img {
            max-width: 100%;
            max-height: 150px; 
            height: auto;
            margin-bottom: 10px; 
        }
        .product-card p {
            font-size: 0.9em;
            flex-grow: 1; 
        }
        .button-container {
            display: flex; 
            justify-content: space-between; 
            margin-top: 10px; 
        }
        .success-message {
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center my-4">Your Products</h2>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success success-message" role="alert">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <div class="text-center mb-4">
        <a href="add_product.php" class="btn btn-success">Add New Product</a>
    </div>

    <div class="product-container">

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center" role="alert">
            No products available. Start by adding new products!
        </div>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($product['quantity']); ?></p>
                <div class="button-container">
                    <a href="edit_product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-warning">Edit</a>
                    <a href="javascript:void(0);" class="btn btn-danger" onclick="confirmDelete(<?php echo htmlspecialchars($product['id']); ?>)">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function confirmDelete(productId) {
    if (confirm("Are you sure you want to delete this product?")) {
        window.location.href = "delete_product.php?id=" + productId;
    }
}
</script>
</body>
</html>

<?php
$pdo = null; 
?>
