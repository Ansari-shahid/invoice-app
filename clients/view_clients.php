<?php include '../includes/db.php'; ?>
<?php include '../includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Clients</h2>
    <a href="add_client.php" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Add Client
    </a>
</div>

<!-- Your table/list of clients continues here -->

<h2>All Clients</h2>

<?php
$clients = $conn->query("SELECT * FROM clients ORDER BY created_at DESC");
?>

<table class="table table-bordered mt-4">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
    <?php while($c = $clients->fetch_assoc()): ?>
        <tr>
            <td><?= $c['name'] ?></td>
            <td><?= $c['email'] ?></td>
            <td><?= $c['phone'] ?></td>
            <td><?= nl2br($c['address']) ?></td>
            <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
