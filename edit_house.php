<?php
require_once 'includes/db.php';
$id = $_GET['id'] ?? 0;

// Fetch house
$stmt = $conn->prepare("SELECT * FROM houses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$house = $stmt->get_result()->fetch_assoc();

// Update logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $apartment_id = $_POST['apartment_id'];

    $update = $conn->prepare("UPDATE houses SET name = ?, apartment_id = ? WHERE id = ?");
    $update->bind_param("sii", $name, $apartment_id, $id);
    $update->execute();
    header("Location: view_houses.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit House</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f6;
            font-family: Arial, sans-serif;
        }
        .form-container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        .btn {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit House</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">House Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($house['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apartment ID</label>
            <input type="number" name="apartment_id" value="<?= $house['apartment_id'] ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="view_houses.php" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</div>
</body>
</html>
