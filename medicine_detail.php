<?php
require_once 'config/db.php';
session_start();

// 1. Read the id from the URL
$id = (int)($_GET['id'] ?? 0);

// 2. Fetch the medicine with its category name
$sql = "SELECT m.*, c.name AS category_name
        FROM medicines m
        JOIN categories c ON m.category_id = c.id
        WHERE m.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$med = mysqli_fetch_assoc($result);

$pageTitle = $med ? $med['name'] : 'Medicine not found';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-4 mb-5">
    <a href="medicines.php" class="text-decoration-none">&larr; Back to medicines</a>

    <?php if (!$med): ?>

        <div class="alert alert-warning mt-3">Medicine not found.</div>

    <?php else: ?>

        <div class="row mt-3">
            <!-- Image -->
            <div class="col-md-5 mb-4">
                <?php if (!empty($med['image'])): ?>
                    <img src="/ghouri_pharmacy/uploads/medicines/<?php echo htmlspecialchars($med['image']); ?>"
                         class="img-fluid rounded w-100" style="max-height: 400px; object-fit: contain; background: var(--light-green); padding: 20px;"
                         alt="<?php echo htmlspecialchars($med['name']); ?>">
                <?php else: ?>
                    <div class="bg-light-green text-center py-5 rounded">
                        <i class="fa-solid fa-pills fa-5x" style="color: var(--primary-green);"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Details -->
            <div class="col-md-7">
                <span class="badge bg-success mb-2"><?php echo htmlspecialchars($med['category_name']); ?></span>
                <h2><?php echo htmlspecialchars($med['name']); ?></h2>
                <h3 class="mb-3" style="color: var(--primary-green);">Rs. <?php echo number_format($med['price'], 2); ?></h3>

                <p><?php echo nl2br(htmlspecialchars($med['description'])); ?></p>

                <!-- Stock status -->
                <p>
                    <?php if ($med['stock_quantity'] <= 0): ?>
                        <span class="badge bg-danger fs-6">Out of Stock</span>
                    <?php elseif ($med['stock_quantity'] <= 10): ?>
                        <span class="badge bg-warning text-dark fs-6">Only <?php echo $med['stock_quantity']; ?> left</span>
                    <?php else: ?>
                        <span class="badge bg-success fs-6">In Stock</span>
                    <?php endif; ?>
                </p>

                <!-- Prescription notice -->
                <?php if ($med['requires_prescription']): ?>
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-file-prescription me-2"></i>
                        This medicine requires a valid prescription. You will upload it at checkout.
                    </div>
                <?php endif; ?>

                <!-- Add to cart (works from Day 3) -->
                <form method="GET" action="add_to_cart.php" class="d-flex gap-2 mt-3" style="max-width: 280px;">
                    <input type="hidden" name="id" value="<?php echo $med['id']; ?>">
                    <input type="number" name="qty" value="1" min="1"
                           max="<?php echo max(1, $med['stock_quantity']); ?>" class="form-control">
                    <button type="submit" class="btn btn-green"
                            <?php echo $med['stock_quantity'] <= 0 ? 'disabled' : ''; ?>>Add to Cart</button>
                </form>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>