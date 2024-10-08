<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php'; // Ensure isLoggedIn() is defined here

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Retrieve user information from session
$user_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Admin';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/x-icon" href="assets/images/iconasf.png">
    <link rel="icon" type="image/png" href="assets/images/iconasf.png">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 90%;
        max-width: 1200px;
        margin: auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    h1 {
        color: #333;
        font-size: 28px;
        margin-bottom: 20px;
        text-align: center;
    }

    .dashboard-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .card {
        background-color: #007bff;
        color: white;
        border-radius: 8px;
        padding: 20px;
        width: 300px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    .button {
        display: inline-block;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        background-color: #007bff;
        color: white;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        margin: 10px;
        transition: background-color 0.3s, transform 0.3s;
    }

    .button:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    canvas {
        display: block;
        margin: 20px auto;
        /* Center the canvas */
    }
    </style>
</head>

<body>

    <div class="container">
        <?php include 'includes/sidebar.php'; ?>
        <h1 style="color: #ce7423">Welcome, <?= $user_name ?>!</h1>
        <div class="dashboard-grid">
            <div class="card">
                <div class="icon">👤</div>
                <h2>Employee Management</h2>
                <p>Manage employee records and details from here.</p>
                <a href="register_employee.php" class="button"> ➕ Add Employee</a>
                <a href="view_employees.php" class="button"> 👷🏼 View Employees</a>
            </div>
            <div class="card">
                <div class="icon">📦</div>
                <h2>Supplier Management</h2>
                <p>Manage supplier records and details from here.</p>
                <a href="add_supplier.php" class="button"> ➕ Add Supplier</a>
                <a href="view_suppliers.php" class="button"> 🤝 View Suppliers</a>
            </div>
            <div class="card">
                <div class="icon">💳</div>
                <h2>Supplier Transactions</h2>
                <p>Manage transactions with suppliers from here.</p>
                <a href="add_transaction.php" class="button"> ➕ Add Transaction</a>
                <a href="view_transactions.php" class="button"> 💵 View Transactions</a>
            </div>
        </div>

        <h1 style="color: #ce7423">Transaction Overview</h1>

        <h2>Total Amounts Including Taxes</h2>
        <canvas id="totalInclChart" width="400" height="400"></canvas>

        <h2>Total Amounts Excluding Taxes</h2>
        <canvas id="totalExclChart" width="400" height="400"></canvas>

        <?php
        // Query to get totals
        $sql = "SELECT 
                    SUM(amount_excl_taxes) AS total_excl_taxes,
                    SUM(amount_incl_taxes) AS total_incl_taxes,
                    SUM(CASE WHEN status = 'Paid' THEN amount_excl_taxes ELSE 0 END) AS paid_excl_taxes,
                    SUM(CASE WHEN status = 'Paid' THEN amount_incl_taxes ELSE 0 END) AS paid_incl_taxes,
                    SUM(CASE WHEN status = 'Not Paid' THEN amount_excl_taxes ELSE 0 END) AS not_paid_excl_taxes,
                    SUM(CASE WHEN status = 'Not Paid' THEN amount_incl_taxes ELSE 0 END) AS not_paid_incl_taxes
                FROM transactions";
        $stmt = $pdo->query($sql);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $paid_excl_taxes = $data['paid_excl_taxes'] ?: 0;
        $paid_incl_taxes = $data['paid_incl_taxes'] ?: 0;
        $not_paid_excl_taxes = $data['not_paid_excl_taxes'] ?: 0;
        $not_paid_incl_taxes = $data['not_paid_incl_taxes'] ?: 0;
        ?>

        <script>
        // Data from PHP
        const paidInclTaxes = <?= json_encode($paid_incl_taxes) ?>;
        const notPaidInclTaxes = <?= json_encode($not_paid_incl_taxes) ?>;

        const paidExclTaxes = <?= json_encode($paid_excl_taxes) ?>;
        const notPaidExclTaxes = <?= json_encode($not_paid_excl_taxes) ?>;

        // Total Amounts Including Taxes Chart
        const totalInclCtx = document.getElementById('totalInclChart').getContext('2d');
        const totalInclChart = new Chart(totalInclCtx, {
            type: 'pie',
            data: {
                labels: ['Paid', 'Not Paid'],
                datasets: [{
                    label: 'Total Amounts Including Taxes',
                    data: [paidInclTaxes, notPaidInclTaxes],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Total Amounts Excluding Taxes Chart
        const totalExclCtx = document.getElementById('totalExclChart').getContext('2d');
        const totalExclChart = new Chart(totalExclCtx, {
            type: 'pie',
            data: {
                labels: ['Paid', 'Not Paid'],
                datasets: [{
                    label: 'Total Amounts Excluding Taxes',
                    data: [paidExclTaxes, notPaidExclTaxes],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
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
    </div>
</body>

</html>