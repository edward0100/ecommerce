<?php
session_start();
error_reporting(E_ALL);
include '../config/db.php';
include '../includes/navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();


    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];


        if ($user['role'] == 'vendor') {
            $_SESSION['vendor_id'] = $user['id']; 
            header("Location: vendor_dashboard.php");
        } else {
            header("Location: customer_dashboard.php");
        }
        exit();
    } else {
        echo "<div class='alert alert-danger'>Invalid username or password!</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<script src="js/scripts.js"></script>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username: </label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password: </label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
</body>
</html>