<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Ghouri Pharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/ghouri_pharmacy/assets/css/style.css?v=2" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h2>
    <p>Dashboard coming on Day 7.</p>
    <a href="logout.php" class="btn btn-outline-green">Logout</a>
</div>
</body>
</html>