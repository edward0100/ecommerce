<?php
session_start();
include '../config/db.php'; 
include '../includes/navbar.php';

if (!isset($_SESSION['vendor_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $vendor_id = $_SESSION['vendor_id']; 

    // Image validation and upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $image_name = basename($image['name']);
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];
        $image_type = mime_content_type($image_tmp_name);

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($image_type, $allowed_types)) {
            echo "<div class='alert alert-danger'>Invalid image format. Only JPG, WEBP, PNG, and GIF are allowed.</div>";
        } elseif ($image_size > 2 * 1024 * 1024) { 
            echo "<div class='alert alert-danger'>Image size must not exceed 2MB.</div>";
        } else {
            $upload_dir = '../uploads/';
            $upload_file = $upload_dir . $image_name;

            if (move_uploaded_file($image_tmp_name, $upload_file)) {
                // Insert product into database
                $stmt = $conn->prepare("INSERT INTO products (name, description, price, quantity, image, vendor_id) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdiis", $name, $description, $price, $quantity, $image_name, $vendor_id);

                if ($stmt->execute()) {
                    // Redirect to vendor dashboard with product ID to view it
                    $product_id = $stmt->insert_id; // Get the last inserted product ID
                    header("Location: vendor_dashboard.php?product_id=" . $product_id . "&success=1");
                    exit;
                } else {
                    echo "<div class='alert alert-danger'>Error adding product: " . $stmt->error . "</div>";
                }
                $stmt->close();
            } else {
                echo "<div class='alert alert-danger'>Failed to upload image. Please try again.</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Please select a valid image file.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Vendor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Add Product</h2>

        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>

            <div class="form-group">
                <label for="description">Product Description</label>
                <textarea name="description" class="form-control" id="description" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" name="price" class="form-control" id="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control" id="quantity" required>
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" name="image" class="form-control-file" id="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>