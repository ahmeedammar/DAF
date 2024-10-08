<?php
session_start();
include 'includes/db.php'; // Ensure this includes your PDO connection
include 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM suppliers";
$stmt = $pdo->query($query);
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/images/iconasf.png">
    <link rel="icon" type="image/png" href="assets/images/iconasf.png">
    <title>View Suppliers</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="container">
    <?php include 'includes/sidebar.php'; ?>
        <h1>Suppliers List</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $supplier): ?>
                <tr>
                    <td><?= $supplier['id'] ?></td>
                    <td><?= htmlspecialchars($supplier['name']) ?></td>
                    <td><?= htmlspecialchars($supplier['designation']) ?></td>
                    <td>
                        <div class="button-container" style="display: flex; justify-content: center;">
                            <!-- Added button container for consistent styling -->
                            <a href="edit_supplier.php?id=<?= $supplier['id'] ?>" class="button">✏️</a>
                            <a href="delete_supplier.php?id=<?= $supplier['id'] ?>" class="button"  onclick="return confirm('Are you sure you want to delete this Supplier?');">🗑️</a>

                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="button"> ↩️ Back to Dashboard</a>
        <a href="add_supplier.php" class="button"> ➕ Add Supplier </a> <!-- Updated button style -->
        
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