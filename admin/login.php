<?php
require_once '../config/db.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errors[] = "Please enter both username and password.";
    }

    if (empty($errors)) {
        $sql = "SELECT id, username, password FROM admins WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row && password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Ghouri Pharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/ghouri_pharmacy/assets/css/style.css?v=2" rel="stylesheet">
</head>
<body class="bg-light-green">

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow border-0 p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-1">Admin Panel</h3>
        <p class="text-center text-muted small mb-4">Ghouri Pharmacy</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" autocomplete="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="login_password" class="form-control" autocomplete="new-password" required>
                    <button type="button" class="btn btn-outline-secondary" id="toggleLogin">Show</button>
                </div>
            </div>
            <button type="submit" class="btn btn-green w-100">Login</button>
        </form>
    </div>
</div>

<script src="/ghouri_pharmacy/assets/js/script.js"></script>
</body>
</html>