<?php
session_start();

$id = (int)($_GET['id'] ?? 0);

if (isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
    $_SESSION['cart_msg'] = ['success', 'Item removed from cart.'];
}

header("Location: cart.php");
exit;
?>