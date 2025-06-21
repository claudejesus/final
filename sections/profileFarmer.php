<?php
require '../auth.php';
require '../db.php';

if ($_SESSION['user']['role'] !== 'farmer') {
    header('Location: farmer.php');
    exit;
}

$userId = $_SESSION['user']['id'];
$message = '';

// Fetch current farmer info
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Basic validation
    if (empty($username) || empty($email)) {
        $message = "Username and Email are required.";
    } elseif ($password !== $password_confirm) {
        $message = "Passwords do not match.";
    } else {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $username, $email, $hashed, $userId);
        } else {
            // Update without changing password
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $username, $email, $userId);
        }
        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
            $_SESSION['user']['username'] = $username; // update session username
        } else {
            $message = "Failed to update profile.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f4f4f4; }
        form { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        label { display: block; margin-top: 15px; }
        input[type=text], input[type=email], input[type=password] {
            width: 100%; padding: 8px; margin-top: 5px;
            box-sizing: border-box;
        }
        button {
            margin-top: 20px; padding: 10px 15px;
            background-color: #006400; color: white; border: none;
            cursor: pointer; border-radius: 5px;
        }
        .message { margin-top: 15px; color: red; }
        a { display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>

<h2>Edit Profile</h2>

<?php if ($message): ?>
<p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Username
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </label>

    <label>Email
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </label>

    <label>New Password (leave blank to keep current)
        <input type="password" name="password" autocomplete="new-password">
    </label>

    <label>Confirm New Password
        <input type="password" name="password_confirm" autocomplete="new-password">
    </label>

    <button type="submit">Update Profile</button>
</form>

<a href="../farmer.php">← Back to Dashboard</a>

</body>
</html>
