<?php
require_once 'config/db.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Please enter both email and password.";
    }

    if (empty($errors)) {
        $sql = "SELECT id, full_name, password FROM customers WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row && password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['customer_id'] = $row['id'];
            $_SESSION['customer_name'] = $row['full_name'];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}

$pageTitle = 'Login';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-5 mb-5" style="max-width: 450px;">
    <h2 class="mb-4">Login</h2>

    <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success">Registration successful. Please log in.</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?php echo $error; ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" autocomplete="email" required>
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

    <p class="mt-3 text-center">No account? <a href="register.php">Register</a></p>
</div>

<?php include 'includes/footer.php'; ?>