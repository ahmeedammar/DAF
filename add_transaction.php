<?php
// Include database connection
require 'includes/db.php'; // Ensure you have this file set up for PDO
include 'includes/sidebar.php';
// Fetch suppliers
$query = "SELECT id, name FROM suppliers";
$stmt = $pdo->query($query);
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $supplier_id = (int)$_POST['supplier_id']; // Use supplier_id
    $invoice_number = htmlspecialchars($_POST['invoice_number']);
    $payment_modality = htmlspecialchars($_POST['payment_modality']); // Capture payment_modality
    $amount_excl_taxes = (float)$_POST['amount_excl_taxes'];
    $amount_incl_taxes = (float)$_POST['amount_incl_taxes'];
    $status = htmlspecialchars($_POST['status']);
    $payment_type = htmlspecialchars($_POST['payment_type']);
    $payment_date = htmlspecialchars($_POST['payment_date']);
    $invoice_date = htmlspecialchars($_POST['invoice_date']);

    // Insert into database
    $sql = "INSERT INTO transactions (supplier_id, invoice_number, payment_modality, amount_excl_taxes, amount_incl_taxes, status, payment_type, payment_date, invoice_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$supplier_id, $invoice_number, $payment_modality, $amount_excl_taxes, $amount_incl_taxes, $status, $payment_type, $payment_date, $invoice_date]);

    // Redirect to the view transactions page
    header('Location: view_transactions.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

    <link rel="icon" type="image/x-icon" href="assets/images/iconasf.png">
    <link rel="icon" type="image/png" href="assets/images/iconasf.png">
    <title>Add Transaction</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
    .container {
        width: 90%;
        max-width: 1200px;
        margin: 20px auto;
        background: #ffffff;
        padding: 30px;
        /* Increased padding for better spacing */
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Form Row */
    .form-row {
        display: flex;
        /* Use flexbox for layout */
        justify-content: space-between;
        /* Space out columns */
    }

    /* Form Column */
    .form-column {
        flex: 1;
        /* Make columns take equal space */
        margin-right: 30px;
        /* Increased space between columns */
    }

    /* Remove margin from the last column */
    .form-column:last-child {
        margin-right: 0;
    }

    /* Heading Styles */
    h1 {
        color: #0056b3;
        text-align: center;
        margin-bottom: 20px;
        font-size: 2.5rem;
    }

    /* Label and Input Styles */
    label {
        font-weight: bold;
        margin-top: 15px;
        display: block;
    }

    select,
    input[type="text"],
    input[type="number"],
    input[type="date"] {
        width: 100%;
        padding: 12px;
        /* Increased padding for input fields */
        margin: 10px 0;
        /* Increased margin for more space */
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    select:focus,
    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus {
        border-color: #0056b3;
        outline: none;
    }

    /* Button Styles */
    .button {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 12px 20px;
        text-align: center;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .button:hover {
        background-color: #0056b3;
        transform: translateY(-1px);
    }



    button:hover {
        background-color: #0056b3;
    }

    button:hover {
        background-color: #0056b3;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            /* Stack columns on small screens */
        }

        .form-column {
            margin-right: 0;
            /* No margin for stacked columns */
            margin-bottom: 20px;
            /* Space between stacked columns */
        }

        .button {
            width: 100%;
            /* Full width for buttons */
        }
    }
    </style>

</head>

<body>

    <div class="container">

        <h1>Add Transaction</h1>
        <form method="POST">
            <div class="form-row">
                <div class="form-column">
                    <label for="supplier_id">Supplier Name:</label>
                    <select id="supplier_id" name="supplier_id" required>
                        <option value="">Select a Supplier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= htmlspecialchars($supplier['id']) ?>">
                            <?= htmlspecialchars($supplier['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="invoice_number">Invoice Number:</label>
                    <input type="text" id="invoice_number" name="invoice_number" required>

                    <label for="invoice_date">Invoice Date:</label>
                    <input type="date" id="invoice_date" name="invoice_date" required>

                    <label for="payment_modality">Payment Modality:</label>
                    <input type="text" id="payment_modality" name="payment_modality" required>

                    <label for="amount_excl_taxes">Amount Excl. Taxes: (HT)</label>
                    <input type="number" step="0.001" id="amount_excl_taxes" name="amount_excl_taxes" required>
                </div>
                <div class="form-column">
                    <label for="amount_incl_taxes">Amount Incl. Taxes: </label>
                    <input type="number" step="0.001" id="amount_incl_taxes" name="amount_incl_taxes" required>

                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Paid">Paid</option>
                        <option value="Not Paid">Not Paid</option>
                    </select>

                    <label for="payment_type">Payment Type:</label>
                    <select id="payment_type" name="payment_type" required>
                        <option value="Cheque">Cheque</option>
                        <option value="Virement">Virement</option>
                        <option value="Carte Bancaire">Carte Bancaire</option>
                    </select>

                    <label for="payment_date">Payment Date:</label>
                    <input type="date" id="payment_date" name="payment_date" required>


                </div>

            </div>
            <button type="submit"> ➕ Add Transaction</button>

        </form>
        <a href="view_transactions.php" class="button"> 👀 View Transactions</a>
        <a href="index.php" class="button"> ↩️ Back to Dashboard</a>
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