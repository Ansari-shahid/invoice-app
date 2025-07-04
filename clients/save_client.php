<?php
include '../includes/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$conn->query("INSERT INTO clients (name, email, phone, address) 
              VALUES ('$name', '$email', '$phone', '$address')");

header("Location: view_clients.php");
exit;
