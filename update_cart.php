<?php
require_once 'config/db.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php?required=1");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qty']) && is_array($_POST['qty'])) {
    $adjusted = false;

    foreach ($_POST['qty'] as $medId => $qty) {
        $medId = (int)$medId;
        $qty = (int)$qty;

        if (!isset($_SESSION['cart'][$medId])) {
            continue;
        }

        // quantity 0 or less means remove
        if ($qty <= 0) {
            unset($_SESSION['cart'][$medId]);
            continue;
        }

        $stmt = mysqli_prepare($conn, "SELECT stock_quantity FROM medicines WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $medId);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$row || $row['stock_quantity'] <= 0) {
            unset($_SESSION['cart'][$medId]);
            continue;
        }

        if ($qty > $row['stock_quantity']) {
            $qty = $row['stock_quantity'];
            $adjusted = true;
        }

        $_SESSION['cart'][$medId] = $qty;
    }

    $_SESSION['cart_msg'] = $adjusted
        ? ['warning', 'Some quantities were reduced to the available stock.']
        : ['success', 'Cart updated.'];
}

header("Location: cart.php");
exit;
?>