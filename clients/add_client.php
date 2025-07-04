<?php include '../includes/db.php'; ?>
<?php include '../includes/header.php'; ?>

<h2>Add Client</h2>

<form action="save_client.php" method="POST" class="mt-4">
    <div class="mb-3">
        <label>Client Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control">
    </div>
    <div class="mb-3">
        <label>Address</label>
        <textarea name="address" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Add Client</button>
</form>

<?php include '../includes/footer.php'; ?>
