<?php
require '../config/db.php'; 
include '../includes/navbar.php';  
include '../includes/footer.php'; 

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo "<h3>Product not found</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; 
        }
        .product-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .product-image {
            flex: 1;
            max-width: 50%;
        }
        .product-details {
            flex: 1;
            padding: 20px;
        }
        .product-image img {
            max-width: 100%;
            height: auto;
        }
        .btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container product-container">
    <div class="product-image">
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
    <div class="product-details">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <p><strong>Quantity Available:</strong> <?php echo $product['quantity']; ?></p>

        <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>" class="form-control" style="width: 100px;">
            <button type="submit" class="btn btn-primary mt-2">Add to Cart</button>
        </form>
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
