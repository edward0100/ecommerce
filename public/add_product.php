<?php
include '../includes/navbar.php';
require '../config/db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vendor') {
    header("Location: login.php");
    exit();
}


$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    $target_dir = "../uploads/"; 
    $target_file = $target_dir . basename($_FILES["product_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    if (!is_numeric($price) || $price < 0) {
        $error_message = "Please enter a valid price in dollars.";
        $uploadOk = 0;
    }


    if ($_FILES["product_picture"]["size"] > 5000000) {
        $error_message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }


    if ($uploadOk == 0) {
        $error_message = "Your file was not uploaded. " . $error_message;
    } else {

        if (move_uploaded_file($_FILES["product_picture"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, quantity, image, vendor_id) VALUES (:name, :description, :price, :quantity, :image, :vendor_id)");
            $stmt->execute([
                'name' => $product_name,
                'description' => $description,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $target_file,
                'vendor_id' => $_SESSION['user_id']
            ]);

            $success_message = "Product added successfully!";
            header("Location: vendor_dashboard.php?message=" . urlencode($success_message));
            exit();
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; 
        }
        .add-product-container {
            margin-top: 100px;
            max-width: 600px;
            margin: auto;
        }
        .error-message {
            color: red;
        }
        .success-message {
            color: green;
        }
    </style>
</head>
<body>

<div class="container add-product-container">
    <h2 class="text-center">Add Product</h2>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger error-message" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php elseif (!empty($success_message)): ?>
        <div class="alert alert-success success-message" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter product name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" placeholder="Enter product description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price (in dollars)</label>
            <input type="number" name="price" id="price" class="form-control" placeholder="Enter product price" required step="0.01" min="0">
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Enter product quantity" required min="1">
        </div>
        <div class="form-group">
            <label for="product_picture">Product Picture</label>
            <input type="file" name="product_picture" id="product_picture" class="form-control-file" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Add Product</button>
    </form>

    <p class="text-center mt-3">Back to <a href="vendor_dashboard.php">Dashboard</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
