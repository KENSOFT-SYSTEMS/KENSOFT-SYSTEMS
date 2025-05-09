<?php
session_start();
require_once 'includes/db.php';

$sql = "SELECT houses.id, houses.name AS house_name, apartments.name AS apartment_name 
        FROM houses
        JOIN apartments ON houses.apartment_id = apartments.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Houses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Houses</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr><th>ID</th><th>House Name</th><th>Apartment</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['house_name']) ?></td>
                    <td><?= htmlspecialchars($row['apartment_name']) ?></td>
                    <td><a href="edit_house.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
<a href="delete_house.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
</td>
                    
                    
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
