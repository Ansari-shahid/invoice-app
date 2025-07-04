<?php
// Set correct base URL
function base_url($path = '') {
    return 'http://localhost/Invoice-app/' . ltrim($path, '/');
}

session_start(); // ✅ Only one session_start here

// Redirect to login if not logged in
$current_page = basename($_SERVER['PHP_SELF']);
$public_pages = ['login.php', 'register.php'];

if (!isset($_SESSION['user_id']) && !in_array($current_page, $public_pages)) {
    header("Location: " . base_url('login.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Generator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- ✅ Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('dashboard.php') ?>">InvoiceApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if (isset($_SESSION['user_id'])): ?>
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('dashboard.php') ?>"><i class="fas fa-chart-line"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('invoices/add_invoice.php') ?>"><i class="fas fa-file-invoice"></i> Add Invoice</a>
                </li>
                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="clientDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-users"></i> Clients
    </a>
    <ul class="dropdown-menu" aria-labelledby="clientDropdown">
        <li><a class="dropdown-item" href="<?= base_url('clients/view_clients.php') ?>">View Clients</a></li>
        <li><a class="dropdown-item" href="<?= base_url('clients/add_client.php') ?>">Add Client</a></li>
    </ul>
</li>

            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <span class="nav-link disabled">Hello, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= base_url('logout.php') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-4">
