<?php
session_start();
include 'includes/db.php'; // Ensure this includes your PDO connection
include 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM suppliers WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: view_suppliers.php');
exit();