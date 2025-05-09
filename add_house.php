<?php
session_start();
require_once 'includes/db.php';

$apartments = $conn->query("SELECT id, name FROM apartments");
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $apartment_id = intval($_POST['apartment_id']);
    $rent = floatval($_POST['rent']);  // Rent amount as a float

    if (!empty($name) && $apartment_id && $rent > 0) {  // Check if rent is valid
        $stmt = $conn->prepare("INSERT INTO houses (name, apartment_id, rent) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $name, $apartment_id, $rent);  // Bind rent as an integer or float based on DB type
        if ($stmt->execute()) {
            $message = "House added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        $message = "All fields are required and rent must be greater than 0.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add House</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add House</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">House Name</label>
            <input type="text" name="name" class="form-control" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Select Apartment</label>
            <select name="apartment_id" class="form-control" required>
                <option value="">-- Select --</option>
                <?php while ($row = $apartments->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Rent Amount</label>
            <input type="number" name="rent" class="form-control" step="0.01" min="0" required />
        </div>
        <button type="submit" class="btn btn-primary">Add House</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
