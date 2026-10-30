<?php
require_once 'config/db.php';
session_start();

// 1. Read filters from the URL
$search = trim($_GET['q'] ?? '');
$categoryId = (int)($_GET['category'] ?? 0);

// 2. Categories for the filter buttons
$catResult = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name");

// 3. Build the medicines query based on active filters
$sql = "SELECT id, name, price, stock_quantity, requires_prescription
        FROM medicines WHERE 1=1";
$types = "";
$values = [];

if ($categoryId > 0) {
    $sql .= " AND category_id = ?";
    $types .= "i";
    $values[] = $categoryId;
}

if ($search !== '') {
    $sql .= " AND name LIKE ?";
    $types .= "s";
    $values[] = "%" . $search . "%";
}

$sql .= " ORDER BY name";

$stmt = mysqli_prepare($conn, $sql);
if ($types !== "") {
    mysqli_stmt_bind_param($stmt, $types, ...$values);
}
mysqli_stmt_execute($stmt);
$medicines = mysqli_stmt_get_result($stmt);

$pageTitle = 'Medicines';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-3">Our Medicines</h2>

    <!-- Search -->
    <form method="GET" action="" class="row g-2 mb-3">
        <?php if ($categoryId > 0): ?>
            <input type="hidden" name="category" value="<?php echo $categoryId; ?>">
        <?php endif; ?>
        <div class="col-md-9">
            <input type="text" name="q" class="form-control" placeholder="Search medicines by name..."
                   value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-green w-100">Search</button>
        </div>
    </form>

    <!-- Category filters -->
    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="medicines.php" class="btn btn-sm rounded-pill <?php echo $categoryId === 0 ? 'btn-green' : 'btn-outline-green'; ?>">All</a>
        <?php while ($cat = mysqli_fetch_assoc($catResult)): ?>
            <a href="medicines.php?category=<?php echo $cat['id']; ?>"
               class="btn btn-sm rounded-pill <?php echo $categoryId === (int)$cat['id'] ? 'btn-green' : 'btn-outline-green'; ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- Medicine cards -->
    <div class="row">
        <?php if (mysqli_num_rows($medicines) === 0): ?>
            <div class="col-12">
                <div class="alert alert-info">No medicines found.</div>
            </div>
        <?php endif; ?>

        <?php while ($med = mysqli_fetch_assoc($medicines)): ?>
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="bg-light-green text-center py-4">
                        <i class="fa-solid fa-pills fa-3x" style="color: var(--primary-green);"></i>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title"><?php echo htmlspecialchars($med['name']); ?></h6>
                        <p class="fw-bold mb-2" style="color: var(--primary-green);">
                            Rs. <?php echo number_format($med['price'], 2); ?>
                        </p>

                        <?php if ($med['requires_prescription']): ?>
                            <span class="badge bg-warning text-dark">Rx Required</span>
                        <?php endif; ?>

                        <?php if ($med['stock_quantity'] <= 0): ?>
                            <span class="badge bg-danger">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white border-0">
    <a href="medicine_detail.php?id=<?php echo $med['id']; ?>"
       class="btn btn-outline-green btn-sm w-100 mb-2">View Details</a>
    <?php if ($med['stock_quantity'] > 0): ?>
        <a href="add_to_cart.php?id=<?php echo $med['id']; ?>&qty=1"
           class="btn btn-green btn-sm w-100">Add to Cart</a>
    <?php else: ?>
        <button class="btn btn-secondary btn-sm w-100" disabled>Out of Stock</button>
    <?php endif; ?>
</div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>