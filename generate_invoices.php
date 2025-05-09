

<?php
session_start();
// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Optional: Log errors to a file (create "logs" folder and make it writable)
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
?>

<?php

require_once 'includes/db.php';

// Get current year-month
$current_month = date('Y-m');

// Fetch all tenants and their house/apartment/rent
$sql = "SELECT tenants.id AS tenant_id, houses.id AS house_id, houses.rent
        FROM tenants
        JOIN houses ON tenants.house_id = houses.id";
$result = $conn->query($sql);

$created_count = 0;

while ($row = $result->fetch_assoc()) {
    $tenant_id = $row['tenant_id'];
    $house_id = $row['house_id'];
    $rent = $row['rent'];

    // Check if invoice already exists for the tenant this month
    $check = $conn->prepare("SELECT id FROM invoices WHERE tenant_id = ? AND DATE_FORMAT(due_date, '%Y-%m') = ?");
    $check->bind_param("is", $tenant_id, $current_month);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        // Create new invoice
        $due_date = date('Y-m-05'); // due on 5th of the month
        $status = 'unpaid';
        $stmt = $conn->prepare("INSERT INTO invoices (tenant_id, house_id, amount, due_date, status)
                                VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("iidss", $tenant_id, $house_id, $rent, $due_date, $status);


        $stmt->execute();
        $stmt->close();
        $created_count++;
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-success">
        <?php if ($created_count > 0): ?>
            <?= $created_count ?> invoice(s) created for <?= $current_month ?>.
        <?php else: ?>
            All tenants already have invoices for <?= $current_month ?>.
        <?php endif; ?>
    </div>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
