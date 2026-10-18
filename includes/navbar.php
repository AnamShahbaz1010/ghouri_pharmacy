<?php $cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>

<div class="top-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <span><i class="fa-solid fa-phone me-2"></i>+92 300 0000000</span>
        <span class="d-none d-md-inline"><i class="fa-solid fa-truck-fast me-2"></i>Fast home delivery on all orders</span>
        <span><i class="fa-regular fa-clock me-2"></i>Open daily 9 AM - 11 PM</span>
    </div>
</div>

<nav class="navbar navbar-expand-lg main-nav sticky-top">
    <div class="container">
        <a class="navbar-brand brand" href="/ghouri_pharmacy/index.php">
            <span class="brand-icon"><i class="fa-solid fa-leaf"></i></span>
            <span><strong>Ghouri</strong> Pharmacy</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/ghouri_pharmacy/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/ghouri_pharmacy/medicines.php">Medicines</a></li>
                <?php if (isset($_SESSION['customer_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="/ghouri_pharmacy/my_orders.php">My Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ghouri_pharmacy/profile.php">Profile</a></li>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="/ghouri_pharmacy/cart.php" class="cart-link">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </a>

                <?php if (isset($_SESSION['customer_id'])): ?>
                    <span class="small text-muted d-none d-lg-inline">Hi, <?php echo htmlspecialchars($_SESSION['customer_name']); ?></span>
                    <a href="/ghouri_pharmacy/logout.php" class="btn btn-outline-green btn-sm rounded-pill px-3">Logout</a>
                <?php else: ?>
                    <a href="/ghouri_pharmacy/login.php" class="btn btn-outline-green btn-sm rounded-pill px-3">Login</a>
                    <a href="/ghouri_pharmacy/register.php" class="btn btn-green btn-sm rounded-pill px-3">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>