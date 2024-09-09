<?php
include '../config/db.php'; 
include '../includes/navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing - E-Commerce</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Styling the grid to be responsive and aligned */
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center; /* Center items */
        }
        .product-card {
            width: 100%;
            max-width: 300px; /* Limit the width of cards */
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease; /* Animation on hover */
        }
        .product-card:hover {
            transform: translateY(-10px); /* Lift effect on hover */
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-card .card-body {
            padding: 15px;
        }
        .product-card h5 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .product-card p {
            font-size: 16px;
            color: #333;
        }
        .product-card .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .product-card .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        /* Responsive design */
        @media (min-width: 768px) {
            .product-card {
                width: 30%; /* Display 3 cards per row on medium screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4 text-center">Product Listing</h2>

        <!-- Search Bar -->
        <form class="form-inline mb-4 justify-content-center" method="GET" action="product_listing.php">
            <input class="form-control mr-2" type="search" placeholder="Search for products" name="search" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

        <!-- Display Products or Search Results -->
        <?php
        $search = '';
        $query = "SELECT * FROM products";

        // Check if there's a search query
        if (isset($_GET['search'])) {
            $search = mysqli_real_escape_string($conn, $_GET['search']);
            
            // Modify the query if the search term is not empty
            if (!empty($search)) {
                $query .= " WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
            }
        }

        // Execute the query
        $result = mysqli_query($conn, $query);

        // Check if any products are found
        if (mysqli_num_rows($result) > 0): ?>
            <div class="product-grid">
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card card">
                        <img class="card-img-top" src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?><p class="text-center text-muted">No products found<?php if ($search) echo " for '$search'"; ?>.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>