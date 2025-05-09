<?php
require_once 'includes/db.php';

$result = $conn->query("SELECT invoices.*, tenants.full_name 
                        FROM invoices 
                        JOIN tenants ON invoices.tenant_id = tenants.id 
                        ORDER BY invoices.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style for printing */
        @media print {
            body * {
                visibility: hidden;
            }
            .print-invoice * {
                visibility: visible;
            }
            .print-invoice {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Invoices</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tenant</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= number_format($row['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['due_date']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <?php if ($row['status'] == 'unpaid'): ?>
                            <a href="pay_invoice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Mark as Paid</a>

                        <?php else: ?>
                            <a href="receipt.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-info">View/Print Receipt</a>
                        <?php endif; ?>
                        <!-- Print Button -->
                        <button class="btn btn-sm btn-primary" onclick="printInvoice(<?= $row['id'] ?>)">Print Invoice</button>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No invoices found.</div>
    <?php endif; ?>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<!-- Print modal (hidden) -->
<div id="printModal" class="print-invoice" style="display:none;">
    <div class="invoice-content">
        <!-- Invoice details will be dynamically inserted here -->
    </div>
</div>

<script>
    function printInvoice(invoiceId) {
        // Fetch invoice details via AJAX (You may customize this further)
        fetch(`get_invoice.php?id=${invoiceId}`)
            .then(response => response.text())
            .then(data => {
                // Insert invoice content into the print modal
                document.querySelector('.invoice-content').innerHTML = data;
                
                // Show the modal content (for printing)
                document.getElementById('printModal').style.display = 'block';
                
                // Trigger the print dialog
                window.print();
                
                // Hide the modal after printing
                setTimeout(function() {
                    document.getElementById('printModal').style.display = 'none';
                }, 1000);
            })
            .catch(error => console.error('Error fetching invoice:', error));
    }
</script>

</body>
</html>
