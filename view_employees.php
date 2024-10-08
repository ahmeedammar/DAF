<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php'; // Ensure isLoggedIn() is defined here

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Fetch employee data
$sql = "SELECT * FROM employees";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$employees = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>

    <title>View Employees</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/iconasf.png">
    <link rel="icon" type="image/png" href="assets/images/iconasf.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body>
    <div class="container">
    <?php include 'includes/sidebar.php'; ?>
        <h1> ASF Employees</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Location</th>
                    <th>Phone Number</th>
                    <th>Passport Number</th>
                    <th>ID Card Number</th>
                    <th>Passport Photo</th>
                    <th>ID Card Photo</th>
                    <th>Actions</th> <!-- New column for actions -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= htmlspecialchars($employee['first_name']) ?></td>
                    <td><?= htmlspecialchars($employee['last_name']) ?></td>
                    <td><?= htmlspecialchars($employee['date_of_birth']) ?></td>
                    <td><?= htmlspecialchars($employee['location']) ?></td>
                    <td><?= htmlspecialchars($employee['phone_number']) ?></td>
                    <td><?= htmlspecialchars($employee['passport_number']) ?></td>
                    <td><?= htmlspecialchars($employee['id_card_number']) ?></td>
                    <td>
                        <?php if ($employee['passport_photo']): ?>
                        <a href="<?= htmlspecialchars($employee['passport_photo']) ?>" target="_blank"
                            class="button"> 👁️ View Passport Photo</a>
                        <a href="<?= htmlspecialchars($employee['passport_photo']) ?>" download class="button">📥 Download
                            Passport Photo</a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($employee['id_card_photo']): ?>
                        <a href="<?= htmlspecialchars($employee['id_card_photo']) ?>" target="_blank"
                            class="button"> 👁️ View ID Card Photo</a>
                        <a href="<?= htmlspecialchars($employee['id_card_photo']) ?>" download class="button"> 📥 Download
                            ID Card Photo</a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="button-container">
                            <a href="edit_employee.php?id=<?= $employee['id'] ?>" class="button">✏️</a>
                            <a href="delete_employee.php?id=<?= $employee['id'] ?>" class="button"
                                onclick="return confirm('Are you sure you want to delete this Employee ?');">🗑️</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="button"> ↩️ Back to Dashboard</a>
        <a href="register_employee.php" class="button"> ➕ Add Employee</a>
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