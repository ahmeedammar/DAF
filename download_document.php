<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $doc_id = intval($_GET['id']);

    $sql = "SELECT * FROM documents WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$doc_id]);
    $document = $stmt->fetch();

    if ($document) {
        $file_path = $document['file_path'];
        $file_name = $document['file_name'];

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            readfile($file_path);
            exit();
        } else {
            echo "File does not exist.";
        }
    } else {
        echo "Document not found.";
    }
} else {
    echo "No document specified.";
}
?>
