<?php 
include '../includes/navbar.php'; 
include '../config/db.php';
?>

<!DOCTYPE html>
<html>
<script src="../public/js/scripts.js"></script>
<head>
    <title>Home - E-Commerce</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <style>
        
        .categories-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .category-card {
            flex: 1 1 calc(33.33% - 20px);
            margin-bottom: 20px;
        }

        
        .footer {
            position: relative;
            bottom: 0;
            width: 100%;
            background-color: #343a40;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <header class="jumbotron my-4 text-center">
            <h1 class="display-4">Welcome to Wham E-Commerce Store!</h1>
            <p class="lead">Find the best products at the best prices.</p>
            <a href="product_listing.php" class="btn btn-primary btn-lg">Shop Now!</a>
        </header>

        
        <div class="categories-wrapper">
            <div class="card category-card">
                <img class="card-img-top" src="https://imgs.search.brave.com/tzt-wjjMy0ks024xHdFL98e6ZrUzLaELLAeuh7GsyEc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvOTU1/NjQxNDg4L3Bob3Rv/L2Nsb3RoZXMtc2hv/cC1jb3N0dW1lLWRy/ZXNzLWZhc2hpb24t/c3RvcmUtc3R5bGUt/Y29uY2VwdC5qcGc_/cz02MTJ4NjEyJnc9/MCZrPTIwJmM9Wm91/RUNoNS1YT0N1Qnp2/S0JRZnhneXcwUklH/RVVnOXU1RjBzSmla/Vjg2cz0" alt="Clothing">
                <div class="card-body">
                    <h5 class="card-title">Clothing</h5>
                    <p class="card-text">Explore products in Category 1.</p>
                    <a href="product_listing.php?category=1" class="btn btn-primary">View Products</a>
                </div>
            </div>

            <div class="card category-card">
                <img class="card-img-top" src="https://imgs.search.brave.com/MSLsJ41Kt55Ojkcv5PTlvzEaiMU3Pc5DwNhXM6hU9O8/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvMTgy/MzU3MDUyL3Bob3Rv/L2VsZWN0cm9uaWNz/LXNob3BwaW5nLmpw/Zz9zPTYxMng2MTIm/dz0wJms9MjAmYz1B/cG1GdUtlUXExRmF4/d0RQcm5sQXotUDVI/VU9mREg2SjZNM25F/NDEzb19ZPQ" alt="Electronics">
                <div class="card-body">
                    <h5 class="card-title">Electronics</h5>
                    <p class="card-text">Explore products in Category 2.</p>
                    <a href="product_listing.php?category=2" class="btn btn-primary">View Products</a>
                </div>
            </div>

            <div class="card category-card">
                <img class="card-img-top" src="https://imgs.search.brave.com/sylkd9M8pekQ6ctS9S7Fx2Fvpxsq4RbcT_e4q2W3mDU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS1waG90/by9kaXNwbGF5LWpl/d2VscnktaW5jbHVk/aW5nLW9uZS1qZXdl/bHJ5LWNvbGxlY3Rp/b25fMTAzNDMwMy0z/Nzk5NTMuanBnP3Np/emU9NjI2JmV4dD1q/cGc" alt="Jewelry">
                <div class="card-body">
                    <h5 class="card-title">Jewelry</h5>
                    <p class="card-text">Explore products in Category 3.</p>
                    <a href="product_listing.php?category=3" class="btn btn-primary">View Products</a>
                </div>
            </div>

            <div class="card category-card">
                <img class="card-img-top" src="https://imgs.search.brave.com/ktlaAYZCg2Pp89lxdiUK5ww0A6j2GE8kfh-Wg3VJBp8/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzAzLzIwLzA4Lzc2/LzM2MF9GXzMyMDA4/NzYzNV9YcWFqT1Zp/WHIyQ0EzbkNiakZ2/dkNLUmxzRkF3cUFi/RC5qcGc" alt="Cars">
                <div class="card-body">
                    <h5 class="card-title">Cars</h5>
                    <p class="card-text">Explore products in Category 4.</p>
                    <a href="product_listing.php?category=4" class="btn btn-primary">View Products</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer bg-dark text-white text-center py-3">
        <p>&copy; 2024 Wham E-Commerce. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>