<?php
include '../config/db.php';
include '../includes/navbar.php';  

$error_message = '';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'customer') {
        header("Location: customer_dashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'vendor') {
        header("Location: vendor_dashboard.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; 
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username); 
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($_SESSION['role'] === 'customer') {
                header("Location: customer_dashboard.php");
            } elseif ($_SESSION['role'] === 'vendor') {
                header("Location: vendor_dashboard.php");
            }
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; 
        }
        .login-container {
            margin-top: 100px;
            max-width: 400px;
            margin: auto;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>

<div class="container login-container">
    <h2 class="text-center">Login</h2>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger error-message" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>

    <p class="text-center mt-3">Don't have an account? <a href="signup.php">Sign up</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
