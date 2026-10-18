<?php
$pageTitle = 'Home';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-5">
    <h1>Welcome to Ghouri Pharmacy</h1>
    <p>Your trusted online pharmacy.</p>
    <a href="#" class="btn btn-green">Test Button</a>
</div>
<section class="hero">
    <i class="fa-solid fa-leaf leaf leaf-1"></i>
    <i class="fa-solid fa-leaf leaf leaf-2"></i>
    <i class="fa-solid fa-leaf leaf leaf-3"></i>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-success mb-3">Trusted Online Pharmacy</span>
                <h1 class="display-5 fw-bold">Your Health, <br>Delivered to Your Door</h1>
                <p class="lead text-muted">Order genuine medicines and healthcare products online, with prescription support.</p>
                <a href="medicines.php" class="btn btn-green btn-lg rounded-pill px-4 me-2">Shop Medicines</a>
                <a href="register.php" class="btn btn-outline-green btn-lg rounded-pill px-4">Join Now</a>
            </div>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>