<?php
session_start();
include 'includes/db.php'; // Ensure this includes your PDO connection
include 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $name = htmlspecialchars($_POST['name']);
    $designation = htmlspecialchars($_POST['designation']);

    // Insert into database using PDO
    $sql = "INSERT INTO suppliers (name, designation) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $designation]);

    // Redirect to the view suppliers page
    header('Location: view_suppliers.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/iconasf.png">
    <link rel="icon" type="image/png" href="assets/images/iconasf.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

   
</head>

<body>
    <div class="container">
    <?php include 'includes/sidebar.php'; ?>
        <h1>Add Supplier</h1>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="designation">Designation:</label>
            <input type="text" id="designation" name="designation" required>
            <br>
            <button type="submit"> ➕ Add Supplier</button>
        </form>
        <a href="index.php" class="button"> ↩️ Back to Dashboard</a>
        <a href="view_suppliers.php" class="button"> 👀 View Suppliers</a>

    </div>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.style.left === '0px') {
                sidebar.style.left = '-200px'; // Hide the sidebar
            } else {
                sidebar.style.left = '0px'; // Show the sidebar
            }
        });
        </script>
</body>

</html>