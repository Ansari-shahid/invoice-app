<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$conn->query("UPDATE clients SET name='$name', email='$email', phone='$phone', address='$address' WHERE id=$id");

header("Location: view_clients.php");
exit;
