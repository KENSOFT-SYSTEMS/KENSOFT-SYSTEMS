<?php
require_once 'includes/db.php';

if (isset($_GET['id'])) {
    $invoice_id = intval($_GET['id']);

    // Update the invoice status to 'paid'
    $sql = "UPDATE invoices SET status = 'paid' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoice_id);

    if ($stmt->execute()) {
        // Redirect back to invoices page
        header("Location: view_invoices.php");
        exit;
    } else {
        echo "Failed to update invoice.";
    }
} else {
    echo "Invalid request.";
}
?>
