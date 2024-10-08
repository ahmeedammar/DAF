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
    $stmt = $pdo->prepare("SELECT * FROM suppliers WHERE id = ?");
    $stmt->execute([$id]);
    $supplier = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars($_POST['name']);
        $designation = htmlspecialchars($_POST['designation']);

        $stmt = $pdo->prepare("UPDATE suppliers SET name = ?, designation = ? WHERE id = ?");
        $stmt->execute([$name, $designation, $id]);

        header('Location: view_suppliers.php');
        exit();
    }
} else {
    header('Location: view_suppliers.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/iconasf.png">
    <link rel="icon" type="image/png" href="assets/images/iconasf.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="container">
    <?php include 'includes/sidebar.php'; ?>
        <h1>Edit Supplier</h1>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($supplier['name']) ?>" required>
            <br>
            <label for="designation">Designation:</label>
            <input type="text" id="designation" name="designation"
                value="<?= htmlspecialchars($supplier['designation']) ?>" required>
            <br>
            <button type="submit">Update Supplier</button>
        </form>
        <a href="view_suppliers.php" class="button"> View Suppliers</a>
        <a href="index.php" class="button">↩️ Back to Dashboard </a>
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