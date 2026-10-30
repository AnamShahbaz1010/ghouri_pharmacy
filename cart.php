<?php
require_once 'config/db.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php?required=1");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
$needsPrescription = false;

foreach ($cart as $medId => $qty) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, price, stock_quantity, requires_prescription FROM medicines WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $medId);
    mysqli_stmt_execute($stmt);
    $med = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    // medicine deleted by admin: drop it from the cart
    if (!$med) {
        unset($_SESSION['cart'][$medId]);
        continue;
    }

    $med['qty'] = $qty;
    $med['subtotal'] = $med['price'] * $qty;
    $total += $med['subtotal'];
    if ($med['requires_prescription']) {
        $needsPrescription = true;
    }
    $items[] = $med;
}

$msg = $_SESSION['cart_msg'] ?? null;
unset($_SESSION['cart_msg']);

$pageTitle = 'Your Cart';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-3">Your Cart</h2>

    <?php if ($msg): ?>
        <div class="alert alert-<?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
    <?php endif; ?>

    <?php if (empty($items)): ?>

        <div class="alert alert-info">Your cart is empty.</div>
        <a href="medicines.php" class="btn btn-green">Browse Medicines</a>

    <?php else: ?>

        <form method="POST" action="update_cart.php">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Medicine</th>
                            <th>Price</th>
                            <th style="width: 120px;">Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($item['name']); ?>
                                    <?php if ($item['requires_prescription']): ?>
                                        <span class="badge bg-warning text-dark">Rx</span>
                                    <?php endif; ?>
                                </td>
                                <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="qty[<?php echo $item['id']; ?>]"
                                           value="<?php echo $item['qty']; ?>" min="1"
                                           max="<?php echo $item['stock_quantity']; ?>" class="form-control form-control-sm">
                                </td>
                                <td>Rs. <?php echo number_format($item['subtotal'], 2); ?></td>
                                <td>
                                    <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>"
                                       class="btn btn-sm btn-outline-danger">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th colspan="2" style="color: var(--primary-green);">Rs. <?php echo number_format($total, 2); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <?php if ($needsPrescription): ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-file-prescription me-2"></i>
                    Your cart contains medicine that requires a prescription. You will upload it at checkout.
                </div>
            <?php endif; ?>

            <div class="d-flex flex-wrap gap-2">
                <a href="medicines.php" class="btn btn-outline-green">Continue Shopping</a>
                <button type="submit" class="btn btn-outline-green">Update Cart</button>
                <a href="checkout.php" class="btn btn-green ms-auto">Proceed to Checkout</a>
            </div>
        </form>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>