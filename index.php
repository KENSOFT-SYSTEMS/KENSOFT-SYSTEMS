<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background: url('assets/images/rental-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            padding: 40px;
            max-width: 400px;
            margin: 100px auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(6px);
        }

        .login-container h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            width: 100%;
            border-radius: 10px;
            padding: 10px;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to <br> <span style="font-size: 16px; color:#000; font-weight: bolder;">MakaziLink Property Management System</span></h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <form action="auth/login.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary">Login</button>
            <label style="color: green;font-weight: bolder; margin-left: 12%; margin-top: 8%;" >Powered by KENSOFT SYSTEMS</label>
        </form>
    </div>
</body>
</html>
