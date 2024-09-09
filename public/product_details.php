<?php
session_start();
include '../config/db.php'; 
include '../includes/navbar.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product selected.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $cart_item = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1, 
        'image' => $product['image']
    ];

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $cart_item['id']) {
            $item['quantity'] += 1; 
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = $cart_item;
    }

    header("Location: customer_dashboard.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['name']; ?> - Product Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .product-image {
            max-height: 400px;
            object-fit: cover;
        }
        .product-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .add-to-cart-btn {
            font-size: 1.2rem;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="product-image">
                <img src="uploads/<?php echo $product['image']; ?>" class="img-fluid" alt="<?php echo $product['name']; ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="product-details">
                <h2><?php echo $product['name']; ?></h2>
                <h4 class="text-success">$<?php echo number_format($product['price'], 2); ?></h4>
                <p><?php echo $product['description']; ?></p>
                
                <form action="" method="POST">
                    <button type="submit" class="btn btn-primary add-to-cart-btn">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>