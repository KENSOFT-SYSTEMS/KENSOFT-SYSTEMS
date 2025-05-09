

<?php
session_start();
require_once 'includes/db.php';

// SQL query to fetch tenants, house names, apartment names, and rent from the houses table
$sql = "SELECT tenants.id, tenants.full_name, houses.name AS house_name, apartments.name AS apartment_name, houses.rent
        FROM tenants
        JOIN houses ON tenants.house_id = houses.id
        JOIN apartments ON houses.apartment_id = apartments.id";
$result = $conn->query($sql);
?>
<?php
// Enable all errors and warnings
error_reporting(E_ALL);

// Display errors on the screen
ini_set('display_errors', '1');
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Tenants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Tenants</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>House</th>
                <th>Apartment</th>
                <th>Rent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['house_name']) ?></td>
                    <td><?= htmlspecialchars($row['apartment_name']) ?></td>
                    <td><?= htmlspecialchars($row['rent']) ?></td>
                    <td>
                        <a href="edit_tenant.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_tenant.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
