<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';

if (!isset($_SESSION['vendor_id'])) {
    header("Location: login.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'];
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;
$success = isset($_GET['success']) && $_GET['success'] == 1 ? true : false;

if ($success && $product_id) {
    echo "<div class='alert alert-success mt-3'>Product added successfully! <a href='product_details.php?id=$product_id' class='alert-link'>View Product</a></div>";
}

$stmt = $conn->prepare("SELECT * FROM products WHERE vendor_id = ?");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .product-card {
            margin-bottom: 20px;
        }
        .product-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .new-product-btn {
            margin-bottom: 20px;
        }
        .card-actions {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="my-4">Vendor Dashboard</h2>

    <a href="add_product.php" class="btn btn-primary new-product-btn">Add New Product</a>

    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($product = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card product-card">
                    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top product-img" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text">Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <p class="card-text">Quantity: <?php echo $product['quantity']; ?></p>

                        <div class="card-actions">
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-info btn-sm">View</a>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No products found. Start by adding a new product.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>