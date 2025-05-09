<?php
require_once 'includes/db.php';

if (isset($_GET['id'])) {
    $invoice_id = intval($_GET['id']);

    // Get the invoice details
    $sql = "SELECT invoices.*, tenants.full_name, tenants.phone, tenants.occupation, 
                   houses.name AS house_name, apartments.name AS apartment_name
            FROM invoices
            JOIN tenants ON invoices.tenant_id = tenants.id
            JOIN houses ON invoices.house_id = houses.id
            JOIN apartments ON houses.apartment_id = apartments.id
            WHERE invoices.id = ? AND invoices.status = 'paid'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Format date
        $receipt_date = date('d M Y', strtotime($row['updated_at'] ?? $row['due_date']));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
        }
        .receipt-box {
            max-width: 700px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            background: #fff;
        }
        .company-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-header h2 {
            margin: 0;
        }
    </style>
</head>
<body>
<div class="receipt-box">
    <div class="company-header">
        <h2>DreamStay Apartments</h2>
        <p>123 Nairobi Road, Nairobi, Kenya<br>
        Phone: +254 712 345678 | Email: info@dreamstay.co.ke</p>
        <hr>
        <h4>Payment Receipt</h4>
    </div>

    <p><strong>Tenant Name:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
    
    <p><strong>Apartment:</strong> <?= htmlspecialchars($row['apartment_name']) ?></p>
    <p><strong>House:</strong> <?= htmlspecialchars($row['house_name']) ?></p>
    <p><strong>Amount Paid:</strong> KES <?= number_format($row['amount'], 2) ?></p>
    <p><strong>Payment Date:</strong> <?= $receipt_date ?></p>
    <p><strong>Status:</strong> <?= ucfirst($row['status']) ?></p>

    <hr>
    <p>Thank you for your payment!</p>

    <div class="text-center no-print mt-3">
        <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </div>
</div>
</body>
</html>
<?php
    } else {
        echo "<div class='container mt-5 alert alert-danger'>Receipt not available. Either invoice not found or not paid.</div>";
    }
} else {
    echo "<div class='container mt-5 alert alert-warning'>Invalid request. No invoice ID provided.</div>";
}
?>
