<?php
include '../includes/db.php';
include '../includes/header.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$id = $_GET['id'];
$client = $conn->query("SELECT * FROM clients WHERE id = $id")->fetch_assoc();
?>

<h2>Edit Client</h2>

<form action="update_client.php" method="POST">
    <input type="hidden" name="id" value="<?= $client['id'] ?>">

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?= $client['name'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= $client['email'] ?>">
    </div>
    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= $client['phone'] ?>">
    </div>
    <div class="mb-3">
        <label>Address</label>
        <textarea name="address" class="form-control"><?= $client['address'] ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Client</button>
</form>

<?php include '../includes/footer.php'; ?>
