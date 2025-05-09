<?php
require_once 'includes/db.php';

if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];
    
    // Get the invoice details
    $sql = "SELECT invoices.*, tenants.full_name, houses.name AS house_name, apartments.name AS apartment_name
            FROM invoices
            JOIN tenants ON invoices.tenant_id = tenants.id
            JOIN houses ON invoices.house_id = houses.id
            JOIN apartments ON houses.apartment_id = apartments.id
            WHERE invoices.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Company Header
        echo "<div style='text-align: center; margin-bottom: 30px; font-family: Arial, sans-serif;'>";
        echo "<h2 style='margin-bottom: 5px;'>Greenfield Rentals Ltd</h2>";
        echo "<p style='margin: 0;'>P.O. Box 12345-00100, Nairobi, Kenya</p>";
        echo "<p style='margin: 0;'>Phone: +254 712 345678 | Email: info@greenfieldrentals.co.ke</p>";
        echo "<hr style='margin-top: 15px;'>";
        echo "</div>";

        // Invoice Details
        echo "<h3>Invoice for Tenant: " . htmlspecialchars($row['full_name']) . "</h3>";
        echo "<p><strong>Apartment:</strong> " . htmlspecialchars($row['apartment_name']) . "</p>";
        echo "<p><strong>House:</strong> " . htmlspecialchars($row['house_name']) . "</p>";
        echo "<p><strong>Amount:</strong> KES " . number_format($row['amount'], 2) . "</p>";
        echo "<p><strong>Due Date:</strong> " . htmlspecialchars($row['due_date']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
        echo "<br><p>Thank you for your tenancy.</p>";
    } else {
        echo "Invoice not found.";
    }
}
?>
