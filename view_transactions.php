<?php
// Assuming you have a database connection file included here
include 'includes/db.php';

$query = "SELECT * FROM transactions";
$stmt = $pdo->query($query);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);


$query = "SELECT t.*, s.name AS supplier_name FROM transactions t
          JOIN suppliers s ON t.supplier_id = s.id"; // Assuming supplier_id is the correct foreign key
$stmt = $pdo->query($query);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Transactions</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/iconasf.png">
    <link rel="icon" type="image/png" href="assets/images/iconasf.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
    

    .status-paid {
        color: green;
        font-weight: bold;
    }

    .status-not-paid {
        color: red;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="container">
    <?php include 'includes/sidebar.php'; ?>
        <h1>Supplier Transactions</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Invoice Number</th>
                    <th>Payment Modality</th>
                    <th>Amount (Excl. Taxes)</th>
                    <th>Amount (Incl. Taxes)</th>
                    <th>Status</th>
                    <th>Payment Type</th>
                    <th>Payment Date</th>
                    <th>Invoice Date</th>
                    <th> Actions </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['supplier_name']) ?></td>
                    <td><?= htmlspecialchars($transaction['invoice_number']) ?></td>
                    <td><?= htmlspecialchars($transaction['payment_modality']) ?></td>
                    <td><?= htmlspecialchars($transaction['amount_excl_taxes']) ?></td>
                    <td><?= htmlspecialchars($transaction['amount_incl_taxes']) ?></td>
                    <td class="<?= $transaction['status'] == 'Paid' ? 'status-paid' : 'status-not-paid' ?>">
                        <?= htmlspecialchars($transaction['status']) ?>
                    </td>
                    <td><?= htmlspecialchars($transaction['payment_type']) ?></td>
                    <td><?= htmlspecialchars($transaction['payment_date']) ?></td>
                    <td><?= htmlspecialchars($transaction['invoice_date']) ?></td>
                    <td>
                        <div class="button-container" style="display: flex; justify-content: center;">
                            <!-- Added button container for consistent styling -->
                            <a href="edit_transaction.php?id=<?= $transaction['id'] ?>" class="button">✏️</a>
                            <a href="delete_transaction.php?id=<?= $transaction['id'] ?>" class="button"
                                onclick="return confirm('Are you sure you want to delete this transaction?');">🗑️</a>

                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
        <a href="index.php" class="button"> ↩️ Back to Dashboard</a>
        <a href="add_transaction.php" class="button"> ➕ Add Transaction </a> <!-- Updated button style -->
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