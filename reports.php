<!DOCTYPE html>
<html>
<head>
    <title>Rent Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .report-container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .report-header {
            margin-bottom: 30px;
            text-align: center;
        }
        .summary-list li {
            padding: 10px 0;
            font-size: 1.2rem;
        }
        .form-control {
            max-width: 300px;
        }
    </style>
</head>
<body>
<div class="report-container">
    <h2 class="report-header">Rent Report - <?= htmlspecialchars($month) ?></h2>

    <form method="GET" class="mb-4 d-flex align-items-end gap-2">
        <div>
            <label class="form-label">Select Month:</label>
            <input type="month" name="month" class="form-control" value="<?= $month ?>">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <ul class="list-group summary-list">
        <li class="list-group-item d-flex justify-content-between">
            <strong>Total Collected:</strong> <span>KES <?= number_format($totalCollected, 2) ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <strong>Total Unpaid:</strong> <span>KES <?= number_format($totalUnpaid, 2) ?></span>
        </li>
    </ul>

    <div class="mt-4 text-center">
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
