<?php
session_start();
include '../config/db.php'; 
include '../includes/navbar.php';

// Ensure the user is logged in as a vendor
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'vendor') {
    header("Location: login.php");
    exit();
}

// Check if the product ID is set in the URL
if (!isset($_GET['id'])) {
    echo "No product ID provided.";
    exit();
}

$product_id = $_GET['id'];

// Get product details
$query = "SELECT * FROM products WHERE id = ? AND vendor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found or you're not authorized to edit this product.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    // If there's a new image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $image_name = basename($image['name']);
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];
        $image_type = mime_content_type($image_tmp_name);

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($image_type, $allowed_types)) {
            echo "<div class='alert alert-danger'>Invalid image format. Only JPG, PNG, and GIF are allowed.</div>";
        } elseif ($image_size > 2 * 1024 * 1024) {
            echo "<div class='alert alert-danger'>Image size must not exceed 2MB.</div>";
        } else {
            $upload_dir = '../uploads/';
            $upload_file = $upload_dir . $image_name;
            if (move_uploaded_file($image_tmp_name, $upload_file)) {
                $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, image = ? WHERE id = ? AND vendor_id = ?");
                $stmt->bind_param("ssdisii", $name, $description, $price, $quantity, $image_name, $product_id, $_SESSION['user_id']);
            } else {
                echo "<div class='alert alert-danger'>Failed to upload image. Please try again.</div>";
            }
        }
    } else {
        // Update without changing the image
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ? WHERE id = ? AND vendor_id = ?");
        $stmt->bind_param("ssdiii", $name, $description, $price, $quantity, $product_id, $_SESSION['user_id']);
    }

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Product updated successfully.</div>";
        header("Location: vendor_dashboard.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating product: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product - Vendor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Edit Product</h2>

        <form action="edit_product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" class="form-control" id="name" value="<?php echo $product['name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Product Description</label>
                <textarea name="description" class="form-control" id="description" rows="3" required><?php echo $product['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" name="price" class="form-control" id="price" step="0.01" value="<?php echo $product['price']; ?>" required>
            </div><div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control" id="quantity" value="<?php echo $product['quantity']; ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Product Image (optional)</label>
                <input type="file" name="image" class="form-control-file" id="image">
                <p>Current Image: <img src="../uploads/<?php echo $product['image']; ?>" width="100" height="100"></p>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>