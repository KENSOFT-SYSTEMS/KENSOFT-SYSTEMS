<?php
session_start();
require_once 'includes/db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Validate house ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid house ID.");
}

$house_id = intval($_GET['id']);

$conn->begin_transaction();

try {
    // 1. Get all tenant IDs for this house
    $tenant_ids = [];
    $result = $conn->prepare("SELECT id FROM tenants WHERE house_id = ?");
    $result->bind_param("i", $house_id);
    $result->execute();
    $res = $result->get_result();
    while ($row = $res->fetch_assoc()) {
        $tenant_ids[] = $row['id'];
    }
    $result->close();

    // 2. Delete invoices for all tenants in this house
    if (!empty($tenant_ids)) {
        $in = implode(',', array_fill(0, count($tenant_ids), '?'));
        $types = str_repeat('i', count($tenant_ids));
        $stmt1 = $conn->prepare("DELETE FROM invoices WHERE tenant_id IN ($in)");
        $stmt1->bind_param($types, ...$tenant_ids);
        $stmt1->execute();
        $stmt1->close();
    }

    // 3. Delete tenants in this house
    $stmt2 = $conn->prepare("DELETE FROM tenants WHERE house_id = ?");
    $stmt2->bind_param("i", $house_id);
    $stmt2->execute();
    $stmt2->close();

    // 4. Delete the house itself
    $stmt3 = $conn->prepare("DELETE FROM houses WHERE id = ?");
    $stmt3->bind_param("i", $house_id);
    $stmt3->execute();
    $stmt3->close();

    $conn->commit();

    header("Location: view_houses.php?deleted=1");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    die("Error deleting house: " . $e->getMessage());
}
?>
