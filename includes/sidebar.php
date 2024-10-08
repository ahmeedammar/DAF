<!-- includes/sidebar.php -->
<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h2>Navigation</h2>
        <!--<button id="closeSidebar" class="close-button">✖</button>-->
    </div>
    <ul>
        <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="view_employees.php"><i class="fas fa-users"></i> Manage Employees</a></li>
        <li><a href="view_suppliers.php"><i class="fas fa-handshake"></i> Manage Suppliers</a></li>
        <li><a href="view_transactions.php"><i class="fas fa-money-check-alt"></i> Manage Transactions</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>
<button id="toggleSidebar" class="toggle-button">☰</button>

<style>
.sidebar {
    position: fixed;
    left: -250px;
    /* Hide initially */
    width: 250px;
    height: 100%;
    background-color: #343a40;
    color: white;
    padding: 20px;
    transition: left 0.3s ease;
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.5);
    z-index: 1000;
    /* Ensure it appears above other content */
}

.sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sidebar h2 {
    color: #ce7423;
    margin: 0;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin-top: 20px;
}

.sidebar ul li {
    margin: 15px 0;
}

.sidebar ul li a {
    color: #f8f9fa;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 5px;
    display: flex;
    /* Flexbox for icon alignment */
    align-items: center;
    /* Center icons vertically */
    transition: background-color 0.3s, color 0.3s;
}

.sidebar ul li a i {
    margin-right: 10px;
    /* Space between icon and text */
    font-size: 18px;
    /* Adjust icon size */
}

.sidebar ul li a:hover {
    background-color: #ce7423;
    color: white;
}

.close-button {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

.toggle-button {
    position: fixed;
    left: 10px;
    top: 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px;
    cursor: pointer;
    z-index: 1001;
    /* Ensure it appears above the sidebar */
    transition: background-color 0.3s;
}

.toggle-button:hover {
    background-color: #0056b3;
}
</style>