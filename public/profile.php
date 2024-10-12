<?php
include '../config/db.php'; 
include '../includes/navbar.php';
include '../includes/footer.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $profile_picture = $_FILES['profile_picture'];

    if (password_verify($old_password, $user['password'])) {
        if (!empty($profile_picture['name'])) {
            $target_dir = "../uploads/profile_pictures/";
            $target_file = $target_dir . basename($profile_picture["name"]);

            if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
                $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->execute([$target_file, $user_id]);
            }
        }

        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $user_id]);

        if (!empty($new_password) && $new_password == $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
        }

        header("Location: index.php?message=Profile updated successfully!");
        exit();
    } else {
        $message = "Incorrect password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .profile-container {
            max-width: 600px;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .profile-form {
            display: flex;
            flex-direction: column;
        }
        .back-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container profile-container">
        <h2 class="text-center">Edit Profile</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="profile-form">
            <div class="text-center">
                <img src="<?php echo !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'default-profile.png'; ?>" 
                     alt="Profile Picture" class="profile-picture">
                <input type="file" name="profile_picture" class="form-control mt-2">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="old_password">Current Password</label>
                <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Enter current password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password (Optional)</label>
                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm new password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
            <a href="javascript:history.back()" class="btn btn-secondary back-btn">Back</a>
        </form>
    </div>
</body>
</html>
