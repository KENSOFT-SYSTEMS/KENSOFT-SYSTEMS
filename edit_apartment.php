<?php
session_start();
require_once 'includes/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM apartments WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $update = $conn->prepare("UPDATE apartments SET name = ?, location = ? WHERE id = ?");
    $update->bind_param("ssi", $name, $location, $id);
    $update->execute();
    header("Location: view_apartments.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Apartment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }
        .form-container {
            max-width: 600px;
            background: #ffffff;
            margin: 60px auto;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-bottom: 25px;
            font-weight: bold;
            text-align: center;
            color: #333;
        }
        .btn-primary {
            width: 100%;
            background-color: #007bff;
            border: none;
        }
        .btn-secondary {
            width: 100%;
            margin-top: 10px;
            background-color: #6c757d;
            border: none;
        }
        input.form-control {
            height: 45px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Apartment</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Apartment Name</label>
            <input name="name" value="<?= htmlspecialchars($result['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input name="location" value="<?= htmlspecialchars($result['location']) ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="view_apartments.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
