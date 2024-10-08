<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php'; // Ensure isLoggedIn() is defined here

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

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

    $passport_photo_path = '';
    $id_card_photo_path = '';

    if (validate_image($passport_photo, $allowed_image_types, $allowed_extensions)) {
        $passport_photo_path = 'uploads/' . basename($passport_photo['name']);
        move_uploaded_file($passport_photo['tmp_name'], $passport_photo_path);
    }

    if (validate_image($id_card_photo, $allowed_image_types, $allowed_extensions)) {
        $id_card_photo_path = 'uploads/' . basename($id_card_photo['name']);
        move_uploaded_file($id_card_photo['tmp_name'], $id_card_photo_path);
    }

    $sql = "INSERT INTO employees (first_name, last_name, date_of_birth, location, phone_number, passport_number, id_card_number, passport_photo, id_card_photo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$first_name, $last_name, $date_of_birth, $location, $phone_number, $passport_number, $id_card_number, 
                    $passport_photo_path, $id_card_photo_path]);

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Employee</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/iconasf.png">
    <link rel="icon" type="image/png" href="assets/images/iconasf.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body>
    <div class="container">
    <?php include 'includes/sidebar.php'; ?>
        <h1>Add Employee</h1>
        <form action="register_employee.php" method="post" enctype="multipart/form-data">
            <label>First Name:</label>
            <input type="text" name="first_name" required><br>
            <label>Last Name:</label>
            <input type="text" name="last_name" required><br>
            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" required><br>
            <label>Location:</label>
            <input type="text" name="location"><br>
            <label>Phone Number:</label>
            <input type="text" name="phone_number" required placeholder="23242132" pattern="\d{1,8}"
                title="Phone number should be up to 8 digits" maxlength="8"><br>
            <label>Passport Number:</label>
            <input type="text" name="passport_number"><br>
            <label class="file-input-label" for="passport_photo">Choose Passport Photo</label>
            <input type="file" id="passport_photo" name="passport_photo" class="file-input" accept="image/*">
            <div id="passport_photo_name" class="file-name">No file chosen</div>
            <img id="passportPreview" class="image-preview" alt="Passport Photo Preview"><br>
            <label>ID Card Number:</label>
            <input type="text" name="id_card_number"><br>
            <label class="file-input-label" for="id_card_photo">Choose ID Card Photo</label>
            <input type="file" id="id_card_photo" name="id_card_photo" class="file-input" accept="image/*">
            <div id="id_card_photo_name" class="file-name">No file chosen</div>
            <img style="height:200px, weight:200px"id="idCardPreview" class="image-preview" alt="ID Card Photo Preview"><br>
            <input type="submit" value="Register" class="button">
        </form>
        <a href="index.php" class="button">Back to Dashboard</a>
    </div>

    <script>
    function updateFileName(inputId, displayId, previewId) {
        const fileInput = document.getElementById(inputId);
        const fileNameDisplay = document.getElementById(displayId);
        const previewImage = document.getElementById(previewId);

        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = fileInput.files[0].name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                fileNameDisplay.textContent = 'No file chosen';
                previewImage.style.display = 'none';
            }
        });
    }

    updateFileName('passport_photo', 'passport_photo_name', 'passportPreview');
    updateFileName('id_card_photo', 'id_card_photo_name', 'idCardPreview');
    const fileInput = document.getElementById('id_card_photo');
    const fileNameDisplay = document.getElementById('id_card_photo_name');
    const imgPreview = document.getElementById('idCardPreview');

    fileInput.addEventListener('change', function() {
        const file = fileInput.files[0];
        if (file) {
            fileNameDisplay.textContent = file.name;
            const reader = new FileReader();
            
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;

                img.onload = function() {
                    // Create a canvas to resize the image
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // Calculate new dimensions while maintaining aspect ratio
                    let width = img.width;
                    let height = img.height;
                    if (width > height) {
                        if (width > 200) {
                            height *= 200 / width;
                            width = 200;
                        }
                    } else {
                        if (height > 200) {
                            width *= 200 / height;
                            height = 200;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);
                    imgPreview.src = canvas.toDataURL(); // Set the preview image to the resized version
                };
            };
            reader.readAsDataURL(file);
        } else {
            fileNameDisplay.textContent = 'No file chosen';
            imgPreview.src = ''; // Clear the preview if no file is chosen
        }
    });

    const passportFileInput = document.getElementById('passport_photo');
    const passportFileNameDisplay = document.getElementById('passport_photo_name');
    const passportImgPreview = document.getElementById('passportPreview');

    passportFileInput.addEventListener('change', function() {
        const file = passportFileInput.files[0];
        if (file) {
            passportFileNameDisplay.textContent = file.name;
            const reader = new FileReader();
            
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;

                img.onload = function() {
                    // Create a canvas to resize the image
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // Calculate new dimensions while maintaining aspect ratio
                    let width = img.width;
                    let height = img.height;
                    if (width > height) {
                        if (width > 200) {
                            height *= 200 / width;
                            width = 200;
                        }
                    } else {
                        if (height > 200) {
                            width *= 200 / height;
                            height = 200;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);
                    passportImgPreview.src = canvas.toDataURL(); // Set the preview image to the resized version
                };
            };
            reader.readAsDataURL(file);
        } else {
            passportFileNameDisplay.textContent = 'No file chosen';
            passportImgPreview.src = ''; // Clear the preview if no file is chosen
        }
    });

    </script>
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