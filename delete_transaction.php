<?php
session_start();
include 'includes/db.php'; // Ensure this includes your PDO connection
include 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Get the transaction ID from the URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Prepare the DELETE statement
    $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ?");
    
    // Execute the statement
    if ($stmt->execute([$id])) {
        // Optionally, you can add a success message here
    } else {
        // Handle error if delete fails
        echo "Error deleting transaction.";
        exit();
    }
}

// Redirect to the transactions view page
header('Location: view_transactions.php');
exit();
