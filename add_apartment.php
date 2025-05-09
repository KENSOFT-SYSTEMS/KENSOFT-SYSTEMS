<?php
session_start();
require_once 'includes/db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);

    if (!empty($name) && !empty($location)) {
        $stmt = $conn->prepare("INSERT INTO apartments (name, location) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $location);
        if ($stmt->execute()) {
            $message = "Apartment added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        $message = "Both fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Apartment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add Apartment</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Apartment Name</label>
            <input type="text" name="name" class="form-control" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Add Apartment</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
