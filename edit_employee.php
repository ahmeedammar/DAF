<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $location = $_POST['location'];
    $phone_number = $_POST['phone_number'];
    $passport_number = $_POST['passport_number'];
    $id_card_number = $_POST['id_card_number'];

    $passport_photo = $_FILES['passport_photo'];
    $id_card_photo = $_FILES['id_card_photo'];

    $allowed_image_types = ['image/jpeg', 'image/png'];
    $allowed_extensions = ['jpg', 'jpeg', 'png'];

    function validate_image($file, $allowed_image_types, $allowed_extensions) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        $file_type = mime_content_type($file['tmp_name']);
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        return in_array($file_type, $allowed_image_types) && in_array($file_ext, $allowed_extensions);
    }

    // Fetch existing photo paths
    $sql = "SELECT passport_photo, id_card_photo FROM employees WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $existing_photos = $stmt->fetch();

    // Initialize photo paths
    $passport_photo_path = $existing_photos['passport_photo'];
    $id_card_photo_path = $existing_photos['id_card_photo'];

    // Process passport photo if uploaded
    if (!empty($passport_photo['name']) && validate_image($passport_photo, $allowed_image_types, $allowed_extensions)) {
        $passport_photo_path = 'uploads/' . basename($passport_photo['name']);
        move_uploaded_file($passport_photo['tmp_name'], $passport_photo_path);
    }

    // Process ID card photo if uploaded
    if (!empty($id_card_photo['name']) && validate_image($id_card_photo, $allowed_image_types, $allowed_extensions)) {
        $id_card_photo_path = 'uploads/' . basename($id_card_photo['name']);
        move_uploaded_file($id_card_photo['tmp_name'], $id_card_photo_path);
    }

    // Update employee record
    $sql = "UPDATE employees 
            SET first_name = ?, last_name = ?, date_of_birth = ?, location = ?, phone_number = ?, passport_number = ?, id_card_number = ?, 
                passport_photo = ?, id_card_photo = ? 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$first_name, $last_name, $date_of_birth, $location, $phone_number, $passport_number, $id_card_number, 
                    $passport_photo_path, $id_card_photo_path, $id]);

    header('Location: view_employees.php');
    exit();
}

// Fetch existing data
$sql = "SELECT * FROM employees WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$employee = $stmt->fetch();

if (!$employee) {
    echo 'Employee not found.';
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    
    <title>Edit Employee</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/iconasf.png">
    <link rel="icon" type="image/png" href="assets/images/iconasf.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="container">
    <?php include 'includes/sidebar.php'; ?>
        <h1>Edit Employee</h1>
        <form action="edit_employee.php?id=<?= $employee['id'] ?>" method="post" enctype="multipart/form-data">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" required><br>
            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>" required><br>
            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" value="<?= htmlspecialchars($employee['date_of_birth']) ?>"
                required><br>
            <label>Location:</label>
            <input type="text" name="location" value="<?= htmlspecialchars($employee['location']) ?>"><br>
            <label>Phone Number:</label>
            <input type="text" name="phone_number" value="<?= htmlspecialchars($employee['phone_number']) ?>"
                required><br>
            <label>Passport Number:</label>
            <input type="text" name="passport_number" value="<?= htmlspecialchars($employee['passport_number']) ?>"><br>
            <label>ID Card Number:</label>
            <input type="text" name="id_card_number" value="<?= htmlspecialchars($employee['id_card_number']) ?>"><br>

            <label>Passport Photo:</label>
            <input type="file" name="passport_photo"><br>
            <?php if ($employee['passport_photo']): ?>
            <img src="<?= htmlspecialchars($employee['passport_photo']) ?>" alt="Passport Photo" width="100"><br>
            <?php endif; ?>

            <label>ID Card Photo:</label>
            <input type="file" name="id_card_photo"><br>
            <?php if ($employee['id_card_photo']): ?>
            <img src="<?= htmlspecialchars($employee['id_card_photo']) ?>" alt="ID Card Photo" width="100"><br>
            <?php endif; ?>

            <input type="submit" value="Update" class="button">
        </form>
        <a href="view_employees.php" class="button">Back to Employee List</a>
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