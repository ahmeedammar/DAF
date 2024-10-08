<?php
session_start();
include 'includes/db.php';
include 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Fetch employee photos
    $sql = "SELECT passport_photo, id_card_photo FROM employees WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $employee = $stmt->fetch();

    if ($employee) {
        // Delete photos if they exist
        if ($employee['passport_photo'] && file_exists($employee['passport_photo'])) {
            unlink($employee['passport_photo']);
        }
        if ($employee['id_card_photo'] && file_exists($employee['id_card_photo'])) {
            unlink($employee['id_card_photo']);
        }

        // Delete employee record
        $sql = "DELETE FROM employees WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id])) {
            $_SESSION['message'] = 'Employee deleted successfully.';
        } else {
            $_SESSION['error'] = 'Error deleting employee record.';
        }
    } else {
        $_SESSION['error'] = 'Employee not found.';
    }
} else {
    $_SESSION['error'] = 'Invalid employee ID.';
}

header('Location: view_employees.php');
exit();
?>