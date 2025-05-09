<?php
session_start();
require_once 'includes/db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Validate tenant ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid tenant ID.");
}

$tenant_id = intval($_GET['id']);

$conn->begin_transaction();

try {
    // Delete related invoices first
    $stmt1 = $conn->prepare("DELETE FROM invoices WHERE tenant_id = ?");
    if (!$stmt1) {
        throw new Exception("Prepare failed for invoices: " . $conn->error);
    }
    $stmt1->bind_param("i", $tenant_id);
    $stmt1->execute();

    // Delete the tenant
    $stmt2 = $conn->prepare("DELETE FROM tenants WHERE id = ?");
    if (!$stmt2) {
        throw new Exception("Prepare failed for tenants: " . $conn->error);
    }
    $stmt2->bind_param("i", $tenant_id);
    $stmt2->execute();

    $conn->commit();

    header("Location: view_tenants.php?deleted=1");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    die("Error deleting tenant and invoices: " . $e->getMessage());
}
?>
