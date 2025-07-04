<?php
include 'includes/db.php';
include 'includes/header.php';
//session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

// Search & sort values
$search = $_GET['search'] ?? '';
$sort_by = $_GET['sort_by'] ?? 'created_at';
$order = $_GET['order'] ?? 'DESC';

// Build WHERE clause for search
$where = "";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $where = "WHERE invoices.invoice_number LIKE '%$search%' OR clients.name LIKE '%$search%'";
}

// Sortable columns
$sortable_columns = ['invoice_number', 'client_name', 'grand_total', 'created_at'];
if (!in_array($sort_by, $sortable_columns)) {
    $sort_by = 'created_at';
}

// SQL
$sql = "SELECT invoices.*, clients.name AS client_name 
        FROM invoices 
        JOIN clients ON invoices.client_id = clients.id 
        $where 
        ORDER BY $sort_by $order";

$result = $conn->query($sql);
?>

<h2>Dashboard</h2>

<form method="GET" class="row g-3 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by Invoice or Client" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-2">
        <select name="sort_by" class="form-select">
            <option value="created_at" <?= $sort_by == 'created_at' ? 'selected' : '' ?>>Sort by Date</option>
            <option value="invoice_number" <?= $sort_by == 'invoice_number' ? 'selected' : '' ?>>Sort by Invoice #</option>
            <option value="client_name" <?= $sort_by == 'client_name' ? 'selected' : '' ?>>Sort by Client</option>
            <option value="grand_total" <?= $sort_by == 'grand_total' ? 'selected' : '' ?>>Sort by Total</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="order" class="form-select">
            <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Descending</option>
            <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Ascending</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Apply</button>
    </div>
    <div class="col-md-2">
        <a href="dashboard.php" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>
<div class="mb-3">
    <a href="invoices/export_csv.php" class="btn btn-success">Export CSV</a>
    <a href="invoices/export_excel.php" class="btn btn-warning">Export Excel</a>
</div>
<?php
$total_invoices = $conn->query("SELECT COUNT(*) as total FROM invoices")->fetch_assoc()['total'];
$total_clients = $conn->query("SELECT COUNT(*) as total FROM clients")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(grand_total) as revenue FROM invoices")->fetch_assoc()['revenue'] ?? 0;
?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                Total Invoices
                <h3><?= $total_invoices ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                Total Revenue
                <h3>₹<?= number_format($total_revenue, 2) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white shadow">
            <div class="card-body">
                Total Clients
                <h3><?= $total_clients ?></h3>
            </div>
        </div>
    </div>
</div>


<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Invoice #</th>
            <th>Client</th>
            <th>Total (₹)</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['invoice_number']; ?></td>
            <td><?= $row['client_name']; ?></td>
            <td><?= number_format($row['grand_total'], 2); ?></td>
            <td><?= date('d M Y', strtotime($row['created_at'])); ?></td>
            <td>
                <a href="invoices/view_invoice.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-info">View</a>
                <a href="invoices/edit_invoice.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="invoices/delete_invoice.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this invoice?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
