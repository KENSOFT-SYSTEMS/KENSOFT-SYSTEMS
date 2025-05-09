<?php
session_start();
require_once 'includes/db.php';

$result = $conn->query("SELECT * FROM apartments");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Apartments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Apartments</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>No. of Houses</th>
                <th>No. of Tenants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <?php
                    $apartment_id = $row['id'];

                    // Count houses for this apartment
                    $houseCount = $conn->query("SELECT COUNT(*) AS count FROM houses WHERE apartment_id = $apartment_id")->fetch_assoc()['count'];

                    // Count tenants in houses belonging to this apartment
                    $tenantCountQuery = "
                        SELECT COUNT(*) AS count FROM tenants 
                        WHERE house_id IN (
                            SELECT id FROM houses WHERE apartment_id = $apartment_id
                        )";
                    $tenantCount = $conn->query($tenantCountQuery)->fetch_assoc()['count'];
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= $houseCount ?></td>
                    <td><?= $tenantCount ?></td>
                    <td>
                        <a href="edit_apartment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_apartment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
