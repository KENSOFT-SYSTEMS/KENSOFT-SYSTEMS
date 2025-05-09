<?php
require_once 'includes/db.php';

$currentMonth = date('Y-m');
$dueDay = 5;
$dueDate = date("Y-m-$dueDay");
$generated = 0;

// Get tenants with their house rent
$query = $conn->query("
    SELECT t.id AS tenant_id, h.rent
    FROM tenants t
    JOIN houses h ON t.houseNumber = h.tableID
");

while ($row = $query->fetch_assoc()) {
    $tenant_id = $row['tenant_id'];
    $rent = $row['rent'];

    // Check if invoice already exists
    $check = $conn->prepare("
        SELECT id FROM invoices 
        WHERE tenant_id = ? AND DATE_FORMAT(due_date, '%Y-%m') = ?
    ");
    $check->bind_param("is", $tenant_id, $currentMonth);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        // Insert invoice
        $insert = $conn->prepare("INSERT INTO invoices (tenant_id, amount, due_date) VALUES (?, ?, ?)");
        $insert->bind_param("ids", $tenant_id, $rent, $dueDate);
        $insert->execute();
        $generated++;
    }
}

?>

<h3><?= $generated ?> invoice(s) generated for <?= $currentMonth ?> using house rent values.</h3>
<a href="view_invoices.php">View Invoices</a>
