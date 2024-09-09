<?php
session_start();
include '../config/db.php'; 
include '../includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    if ($stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?")) {
        $stmt->bind_param("ssi", $username, $email, $user_id);
        $stmt->execute();
        echo "Profile updated successfully!";
        $stmt->close();
    } else {
        echo "Failed to prepare the update statement.";
    }
}

if ($stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Failed to prepare the select statement.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Profile</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
</body>
</html>