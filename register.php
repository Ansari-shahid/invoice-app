<?php
include 'includes/db.php';
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Check if passwords match
    if ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username already exists.";
        } else {
            // Insert user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);
            $stmt->execute();
            $message = "User registered successfully. You can now log in.";
            header("Location: login.php");
            exit;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="mb-3">Register</h3>
            <?php if ($message): ?>
                <div class="alert alert-danger"><?= $message ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm" class="form-control" required>
                </div>
                <button class="btn btn-success w-100" type="submit">Register</button>
            </form>
            <div class="mt-3 text-center">
                <a href="login.php">Already have an account? Login</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
