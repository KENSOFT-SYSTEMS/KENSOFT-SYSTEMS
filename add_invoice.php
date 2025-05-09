<?php
require_once 'includes/db.php';

// Fetch tenants for dropdown
$tenants = $conn->query("SELECT id, full_name FROM tenants");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenant_id = $_POST['tenant_id'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("INSERT INTO invoices (tenant_id, amount, due_date) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $tenant_id, $amount, $due_date);
    $stmt->execute();

    echo "Invoice added successfully.";
}
?>

<form method="POST">
    <label>Tenant</label>
    <select name="tenant_id" required>
        <option value="">Select Tenant</option>
        <?php while($row = $tenants->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
        <?php endwhile; ?>
    </select><br>

    <label>Amount</label>
    <input type="number" step="0.01" name="amount" required><br>

    <label>Due Date</label>
    <input type="date" name="due_date" required><br>

    <button type="submit">Add Invoice</button>
</form>
