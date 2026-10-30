<?php
require_once 'config/db.php';
session_start();

// Only logged-in customers can add to cart
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php?required=1");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$qty = (int)($_GET['qty'] ?? 1);
if ($qty < 1) {
    $qty = 1;
}

// Check the medicine exists and get its stock
$stmt = mysqli_prepare($conn, "SELECT id, name, stock_quantity FROM medicines WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$med = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$med) {
    $_SESSION['cart_msg'] = ['danger', 'Medicine not found.'];
    header("Location: medicines.php");
    exit;
}

if ($med['stock_quantity'] <= 0) {
    $_SESSION['cart_msg'] = ['danger', $med['name'] . ' is out of stock.'];
    header("Location: medicines.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$current = $_SESSION['cart'][$id] ?? 0;
$newQty = $current + $qty;

if ($newQty > $med['stock_quantity']) {
    $newQty = $med['stock_quantity'];
    $_SESSION['cart_msg'] = ['warning', 'Only ' . $med['stock_quantity'] . ' of ' . $med['name'] . ' available. Quantity adjusted.'];
} else {
    $_SESSION['cart_msg'] = ['success', $med['name'] . ' added to cart.'];
}

$_SESSION['cart'][$id] = $newQty;

header("Location: cart.php");
exit;
?>