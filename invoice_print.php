<?php
session_start();
// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Optional: Log errors to a file (create "logs" folder and make it writable)
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

require_once 'includes/db.php';

// Get current year-month
$current_month = date('Y-m');

// Fetch all tenants and their house/apartment/rent
$sql = "SELECT tenants.id AS tenant_id, tenants.full_name, houses.id AS house_id, houses.rent, apartments.name AS apartment_name, houses.name AS house_name
        FROM tenants
        JOIN houses ON tenants.house_id = houses.id
        JOIN apartments ON houses.apartment_id = apartments.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Print specific tenant invoice */
        @media print {
            body * {
                visibility: hidden;
            }
            .invoice * {
                visibility: visible;
            }
            .invoice {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Tenant Invoices</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="invoice border p-4 mb-4" id="invoice-<?= $row['tenant_id'] ?>">
            <h4>Invoice for <?= htmlspecialchars($row['full_name']) ?></h4>
            <p><strong>Apartment:</strong> <?= htmlspecialchars($row['apartment_name']) ?></p>
            <p><strong>House:</strong> <?= htmlspecialchars($row['house_name']) ?></p>
            <p><strong>Rent:</strong> KES <?= number_format($row['rent'], 2) ?></p>
            <p><strong>Due Date:</strong> <?= date('Y-m-d', strtotime('first day of next month')) ?></p>
            <button class="btn btn-primary" onclick="printInvoice(<?= $row['tenant_id'] ?>)">Print Invoice</button>
        </div>
    <?php endwhile; ?>
</div>

<script>
    function printInvoice(tenantId) {
        // Hide all invoices first
        var invoices = document.querySelectorAll('.invoice');
        invoices.forEach(function(item) {
            item.style.display = 'none';
        });

        // Show only the specific invoice for the clicked tenant
        var tenantInvoice = document.getElementById('invoice-' + tenantId);
        if (tenantInvoice) {
            tenantInvoice.style.display = 'block'; // Show the specific invoice
            window.print(); // Trigger the print dialog
        }

        // Reset the display of all invoices after a short delay
        setTimeout(function() {
            invoices.forEach(function(item) {
                item.style.display = 'block';
            });
        }, 500); // Allow some time for printing to complete before resetting display
    }
</script>

</body>
</html>
