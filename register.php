<?php
require_once 'config/db.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($full_name) || empty($email)) {
        $errors[] = "Name and email are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if the email is already registered
    if (empty($errors)) {
        $sql = "SELECT id FROM customers WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "This email is already registered.";
        }
    }

    // Save the customer
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO customers (full_name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $full_name, $email, $hashed, $phone, $address);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php?registered=1");
            exit;
        } else {
            $errors[] = "Registration failed: " . mysqli_error($conn);
        }
    }
}

$pageTitle = 'Register';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-5 mb-5" style="max-width: 500px;">
    <h2 class="mb-4">Create an Account</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" autocomplete="off" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">Show</button>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" autocomplete="new-password" required>
                <button type="button" class="btn btn-outline-secondary" id="toggleConfirm">Show</button>
            </div>
        </div>
        <button type="submit" class="btn btn-green w-100">Register</button>
    </form>

    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
</div>

<?php include 'includes/footer.php'; ?>