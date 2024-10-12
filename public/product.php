<?php
include '../includes/navbar.php';
require '../config/db.php'; // Include your database connection

// Initialize search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch products based on search query
$query = "SELECT * FROM products WHERE name LIKE ?";
$searchTerm = "%" . $search . "%"; // Use wildcards for the search
$stmt = $pdo->prepare($query);
$stmt->execute([$searchTerm]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; /* Light blue background */
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
            margin: 15px;
            padding: 15px;
            text-align: center;
            width: 300px; /* Adjust width as necessary */
            height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-card img {
            max-width: 100%;
            height: 200px; /* Fixed height for images */
        }
        .button-container {
            display: flex;
            flex-direction: column; 
            align-items: center; 
            margin-top: 15px;
        }
        .button-container .btn {
            width: 100%; 
            height: 40px; 
            margin-bottom: 10px; 
            border-radius: 5px; 
            font-weight: bold; 
        }
        .button-container .btn-primary {
            background-color: #007bff; 
            border-color: #007bff; 
        }
        .button-container .btn-info {
            background-color: #17a2b8; 
            border-color: #17a2b8; 
        }
        .button-container .btn:hover {
            opacity: 0.9; 
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center my-4">Available Products</h2>

    <form method="GET" class="text-center mb-4">
        <input type="text" name="search" class="form-control" placeholder="Search products by name" value="<?php echo htmlspecialchars($search); ?>" style="width: 300px; display: inline-block;">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div class="product-container">

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center" role="alert">
            No products available.
        </div>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <?php if ($product['quantity'] > 0): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                    <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                    <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>

                    <div class="button-container">
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                            <input type="hidden" name="quantity" value="1"> 
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>

                        <a href="product_details.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-info">View Details</a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
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
