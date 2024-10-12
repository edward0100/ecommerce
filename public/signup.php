<?php
include '../config/db.php'; 
include '../includes/navbar.php';  

$error_message = '';
$success_message = '';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'])) {
        $username = $_POST['username']; 
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role']; 

        if (empty($username) || empty($email) || empty($password) || empty($role)) {
            $error_message = "All fields are required.";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);

                if ($stmt->rowCount() > 0) {
                    $error_message = "Email is already registered.";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
                    $stmt->execute([
                        'username' => $username,
                        'email' => $email,
                        'password' => $hashed_password,
                        'role' => $role
                    ]);

                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['role'] = $role;
                    $success_message = "Registration successful! Redirecting...";
                    header("refresh:2; url=index.php"); 
                    exit();
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                $error_message = "Registration failed. Please try again.";
            }
        }
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; 
        }
        .register-container {
            margin-top: 100px;
            max-width: 400px;
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

<div class="container register-container">
    <h2 class="text-center">Register</h2>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger error-message" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success success-message" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <div class="form-group">
            <label>Register as:</label><br>
            <div class="form-check">
                <input type="radio" name="role" value="customer" class="form-check-input" id="customer" required>
                <label for="customer" class="form-check-label">Customer</label>
            </div>
            <div class="form-check">
                <input type="radio" name="role" value="vendor" class="form-check-input" id="vendor" required>
                <label for="vendor" class="form-check-label">Vendor</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>

    <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
