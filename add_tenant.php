<?php
session_start();
require_once 'includes/db.php';

$houses = $conn->query("SELECT houses.id, houses.name AS house_name, apartments.name AS apartment_name 
                        FROM houses JOIN apartments ON houses.apartment_id = apartments.id");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $occupation = trim($_POST['occupation']);
    $house_id = intval($_POST['house_id']);

    if (!empty($full_name) && $house_id) {
        $stmt = $conn->prepare("INSERT INTO tenants (full_name, phone, occupation, house_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $full_name, $phone, $occupation, $house_id);
        if ($stmt->execute()) {
            $message = "Tenant added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        $message = "Please fill in all required fields.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Tenant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add Tenant</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST">
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" required />
    </div>
    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" />
    </div>
    <div class="mb-3">
        <label class="form-label">Occupation</label>
        <input type="text" name="occupation" class="form-control" />
    </div>
    <div class="mb-3">
        <label class="form-label">Select House</label>
        <select name="house_id" class="form-control" required>
            <option value="">-- Select --</option>
            <?php while ($row = $houses->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>">
                    <?= htmlspecialchars($row['apartment_name'] . ' - ' . $row['house_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Add Tenant</button>
    <a href="dashboard.php" class="btn btn-secondary">Back</a>
</form>

</div>
</body>
</html>
