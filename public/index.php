<?php
include '../config/db.php'; 
include '../includes/navbar.php';  

if (isset($_SESSION['success_message'])) {
    echo "<script>alert('" . htmlspecialchars($_SESSION['success_message']) . "');</script>";
    unset($_SESSION['success_message']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our E-commerce Store</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; 
        }
        .welcome-message {
            text-align: center;
            margin-top: 50px;
            font-size: 2rem;
            color: #007bff; 
        }
        .flex-container {
            display: flex;
            flex-wrap: wrap; 
            justify-content: space-between; 
            margin: 40px 0;
        }
        .flex-box {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative; 
            width: calc(50% - 10px); 
            height: 400px; 
            margin: 5px; 
            overflow: hidden; 
        }
        .flex-box img {
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        }
        .shop-now {
            position: absolute; 
            bottom: 20px; 
            left: 50%; 
            transform: translateX(-50%); 
            font-size: 1rem;
            padding: 10px 20px;
            background-color: yellow; 
            border: none; 
            color: black; 
            text-align: center; 
        }
        .shop-now:hover {
            background-color: orange; 
        }
    </style>
</head>
<body>

<div class="container">
    <div class="welcome-message">
        <h1>Welcome to Our E-commerce Store!</h1>
    </div>

    <div class="flex-container">
        <div class="flex-box">
            <img src="https://imgs.search.brave.com/-smk6nUTIYRzefExuSU3SZCNmzQoAs6eMtCgNs2AAOk/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvODEz/MTc3NTM4L3Bob3Rv/L3VzZWQtY2Fycy1h/dC1tZXRyby1mb3Jk/LmpwZz9zPTYxMng2/MTImdz0wJms9MjAm/Yz1ITmFjM3ZlVS12/WjFrTmF3SHg4dnBY/MGYwdTRQV2JZZm1k/NExRaWlhQ2hJPQ" alt="Product 1">
            <a href="product.php?id=1" class="btn shop-now">Shop Now!</a>
        </div>
        <div class="flex-box">
            <img src="https://imgs.search.brave.com/MK7dNw0SuB0xtFTbjnAvEVxRfUqwSEKhEw-j2pVlDkw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/ZnJlZS1waG90by9j/bG90aGVzXzE0NDYy/Ny0yNTIxNC5qcGc_/c2l6ZT02MjYmZXh0/PWpwZw" alt="Product 2">
            <a href="product.php?id=2" class="btn shop-now">Shop Now!</a>
        </div>
        <div class="flex-box">
            <img src="https://imgs.search.brave.com/W7yPJ1KBL9ouuGSt65K6fgJf9vpxhwDNzcZAda06IEI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9yZW5l/ZWRlcGFyaXNqZXdl/bHJ5LmNvbS9jZG4v/c2hvcC9jb2xsZWN0/aW9ucy8yMDBhOWZk/Yi1lYzMxLTQzNTQt/YTMxNi04NjI1MWZk/ZTQ0ZGQud2VicD92/PTE3MDg4MTUzMTcm/d2lkdGg9MTI4MA" alt="Product 3">
            <a href="product.php?id=3" class="btn shop-now">Shop Now!</a>
        </div>
        <div class="flex-box">
            <img src="https://imgs.search.brave.com/Y6hSazGEXO-V8XdbQgxuOi8ogqY5OT-GvjPVCnwMBUA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvNTIw/NDY3NzQzL3Bob3Rv/L3BsYXktaXMtdGhl/LWhpZ2hlc3QtZm9y/bS1vZi1yZXNlYXJj/aC5qcGc_cz02MTJ4/NjEyJnc9MCZrPTIw/JmM9THFKc0x5dTBB/QUFMd1lKenc0WWts/SWY3Y0FTYlVLVW9N/elotRlA5Z2FEOD0" alt="Product 4">
            <a href="product.php?id=4" class="btn shop-now">Shop Now!</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
