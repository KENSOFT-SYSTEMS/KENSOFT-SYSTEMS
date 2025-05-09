<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'includes/db.php';

// Stats
$apartments = $conn->query("SELECT COUNT(*) AS total FROM apartments")->fetch_assoc()['total'];
$houses = $conn->query("SELECT COUNT(*) AS total FROM houses")->fetch_assoc()['total'];
$tenants = $conn->query("SELECT COUNT(*) AS total FROM tenants")->fetch_assoc()['total'];

$current_month = date('m');
$current_year = date('Y');

// Paid Rent
$paid = $conn->query("
    SELECT SUM(amount) AS total FROM invoices 
    WHERE MONTH(due_date) = $current_month 
    AND YEAR(due_date) = $current_year 
    AND status = 'paid'
")->fetch_assoc()['total'];
$paid = $paid ? $paid : 0;

// Unpaid Rent
$unpaid = $conn->query("
    SELECT SUM(amount) AS total FROM invoices i
    JOIN tenants t ON i.tenant_id = t.id
    WHERE MONTH(i.due_date) = $current_month 
    AND YEAR(i.due_date) = $current_year 
    AND i.status = 'unpaid'
")->fetch_assoc()['total'];
$unpaid = $unpaid ? $unpaid : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Rental System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #212529;
            color: white;
            padding: 20px 10px;
        }

        .sidebar h4 {
            margin-bottom: 30px;
            font-weight: 600;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px;
            margin-bottom: 10px;
        }

        .sidebar .nav-link:hover {
            background-color: #0d6efd;
            border-radius: 5px;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .stat-card {
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: white; /* Ensures the numbers are white */
        }

        .stat-title {
            font-weight: bold;
            color: white; /* Ensures the titles are white */
        }

        /* Background colors for each category */
        .bg-apartment {
            background-color: #007bff;
        }

        .bg-house {
            background-color: #28a745;
        }

        .bg-tenant {
            background-color: #dc3545;
        }

        .bg-paid {
            background-color: #198754;
        }

        .bg-unpaid {
            background-color: #ffc107;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <h4><i class="fas fa-chart-line me-2"></i>Dashboard</h4>
        <ul class="nav flex-column">
            <li><a href="view_apartments.php" class="nav-link"><i class="fas fa-building me-2"></i>View Apartments</a></li>
            <li><a href="view_houses.php" class="nav-link"><i class="fas fa-house me-2"></i>View Houses</a></li>
            <li><a href="view_tenants.php" class="nav-link"><i class="fas fa-users me-2"></i>View Tenants</a></li>
            <li><a href="view_invoices.php" class="nav-link"><i class="fas fa-receipt me-2"></i>View Payments</a></li>
            <li><a href="generate_invoices.php" class="nav-link"><i class="fas fa-file-invoice-dollar me-2"></i>Generate Invoices</a></li>
            <li><a href="view_invoices.php" class="nav-link"><i class="fas fa-print me-2"></i>Print Invoices/Receipts</a></li>
            <hr class="bg-light">
            <li><a href="add_apartment.php" class="nav-link"><i class="fas fa-plus-circle me-2"></i>Add Apartment</a></li>
            <li><a href="add_house.php" class="nav-link"><i class="fas fa-plus-square me-2"></i>Add House</a></li>
            <li><a href="add_tenant.php" class="nav-link"><i class="fas fa-user-plus me-2"></i>Add Tenant</a></li>
            <li><a href="auth/logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
    </nav>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card bg-apartment">
                    <h5 class="stat-title"><i class="fas fa-building me-2"></i> Apartments</h5>
                    <div class="stat-number"><?php echo $apartments; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-house">
                    <h5 class="stat-title"><i class="fas fa-house me-2"></i> Houses</h5>
                    <div class="stat-number"><?php echo $houses; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-tenant">
                    <h5 class="stat-title"><i class="fas fa-users me-2"></i> Tenants</h5>
                    <div class="stat-number"><?php echo $tenants; ?></div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="stat-card bg-paid">
                    <h5 class="stat-title"><i class="fas fa-money-bill-wave me-2"></i> Paid Rent (Ksh)</h5>
                    <div class="stat-number"><?php echo number_format($paid, 2); ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card bg-unpaid">
                    <h5 class="stat-title"><i class="fas fa-exclamation-circle me-2"></i> Unpaid Rent (Ksh)</h5>
                    <div class="stat-number"><?php echo number_format($unpaid, 2); ?></div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
