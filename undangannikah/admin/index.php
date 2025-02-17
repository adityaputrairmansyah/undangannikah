<?php
session_start();

$username = "admin";
$password = "password123"; // Ganti dengan password yang lebih aman

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-form {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 24px;
            background: linear-gradient(to right, #e74c3c, #c0392b);
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
            color: white;
        }
        .back-btn i {
            margin-right: 8px;
            transition: transform 0.3s ease;
        }
        .back-btn:hover i {
            transform: translateX(-4px);
        }
        .login-title {
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .form-control {
            padding: 12px;
            border-radius: 10px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.2);
            border-color: #e74c3c;
        }
        .btn-login {
            background: #e74c3c;
            border: none;
            padding: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-form">
                    <h2 class="text-center login-title">Login Admin</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-login btn-primary w-100 mb-3">Login</button>
                    </form>
                    <div class="back-link">
                        <a href="../" class="back-btn">
                            <i class="bi bi-arrow-left"></i>
                            Kembali ke Undangan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 