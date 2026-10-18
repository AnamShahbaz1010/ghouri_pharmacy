<?php
require_once 'config/db.php';
session_start();

$errors = [];

$sql = "SELECT id FROM customers WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
// now mysqli_stmt_num_rows($stmt) tells you how many rows matched

$pageTitle = 'Register';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-5 mb-5" style="max-width: 500px;">
    <h2 class="mb-4">Create an Account</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?php echo $error; ?></div>
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