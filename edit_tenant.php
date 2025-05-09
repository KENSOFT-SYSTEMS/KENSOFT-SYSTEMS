<?php
require_once 'includes/db.php';
$id = $_GET['id'] ?? 0;

// Fetch tenant
$stmt = $conn->prepare("SELECT * FROM tenants WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$tenant = $stmt->get_result()->fetch_assoc();

// Update logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $occupation = $_POST['occupation'];
    $house_id = $_POST['house_id'];

    $update = $conn->prepare("UPDATE tenants SET full_name = ?, phone = ?, occupation = ?, house_id = ? WHERE id = ?");
    $update->bind_param("sssii", $full_name, $phone, $occupation, $house_id, $id);
    $update->execute();
    header("Location: view_tenants.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Tenant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .form-container {
            max-width: 600px;
            margin: 60px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Tenant</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($tenant['full_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($tenant['phone']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Occupation</label>
            <input type="text" name="occupation" class="form-control" value="<?= htmlspecialchars($tenant['occupation']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">House ID</label>
            <input type="number" name="house_id" class="form-control" value="<?= $tenant['house_id'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Update</button>
        <a href="view_tenants.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
    </form>
</div>
</body>
</html>
