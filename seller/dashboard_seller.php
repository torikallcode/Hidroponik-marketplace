<?php
session_start();
include '../database/db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'seller') {
    header("Location: log/login2.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$seller_name = !empty($_SESSION['first_name']) ? $_SESSION['first_name'] : 'Penjual';

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'handler_product.php';
} elseif (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    include 'delete_product.php';
} elseif (isset($_POST['update_product'])) {
    // Update existing product
    include 'edit_product.php';
}

// Query produk milik seller
$sql = "SELECT * FROM crud_041_book WHERE seller_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$products = $stmt->get_result();
$total_products = $products->num_rows;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Sistem Penjual Hidroponik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style_seller.css">
</head>

<body>
    <header>
        <div class="topbar">
            <h5>Sistem Penjualan Hidroponik</h5>
            <a href="../logout.php" class="logout-top">Logout</a>
        </div>
    </header>

    <div class="sidebar">
        <a href="?page=dashboard" class="<?= (!isset($_GET['page']) || $_GET['page'] == 'dashboard') ? 'active' : '' ?>"><i class="fas fa-user"></i> Dashboard</a>
        <a href="?page=products" class="<?= (isset($_GET['page']) && $_GET['page'] == 'products') ? 'active' : '' ?>"><i class="fas fa-box"></i> Produk</a>
        <a href="?page=reviews" class="<?= (isset($_GET['page']) && $_GET['page'] == 'reviews') ? 'active' : '' ?>"><i class="fas fa-star"></i> Review</a>
        <a href="?page=orders" class="<?= (isset($_GET['page']) && $_GET['page'] == 'orders') ? 'active' : '' ?>"><i class="fas fa-receipt"></i> Pemesanan</a>
        <a href="?page=profil" class="<?= (isset($_GET['page']) && $_GET['page'] == 'profil') ? 'active' : '' ?>"><i class="fas fa-user-circle"></i> Profil</a>
    </div>

    <div class="content">
        <?php
        // Display success/error messages
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }

        $page = $_GET['page'] ?? 'dashboard';

        if ($page == 'dashboard') {
            // Dashboard content
        ?>
            <div class="header">
                <h2>Hi, <?= htmlspecialchars($seller_name ?? 'Penjual') ?>!</h2>
                <h2>Hope you are having great day!</h2>
            </div>
            <p class="mb-4">Selamat datang di sistem penjualan hidroponik. Di sini, Anda bisa mengelola produk-produk Anda, melihat pemesanan customer sekaligus memantau ulasan dari mereka.</p>
            <img src="../images/freshure.png" alt="freshure" style="max-width:100%; height:auto; border-radius: 10px;"><br><br>

            <section class="kategori-produk">
                <h4>Our genre</h4>
                <div class="kategori-slider-wrapper">
                    <button class="btn-prev">&#10094;</button>
                    <div class="kategori-slider">
                        <div class="kategori-card">
                            <img src="../images/sayur.jpg" alt="Sayuran">
                            <h3>Sayuran Segar</h3>
                            <p>You will find the fresh vegetables here!</p>
                        </div>
                        <div class="kategori-card">
                            <img src="../images/buah.jpg" alt="Buah">
                            <h3>Buah Segar</h3>
                            <p>The sweetest fruits here!</p>
                        </div>
                        <div class="kategori-card">
                            <img src="../images/bunga.jpg" alt="Bunga">
                            <h3>Bunga Hias</h3>
                            <p>Beautiful flowers that you can find here!</p>
                        </div>
                    </div>
                    <button class="btn-next">&#10095;</button>
                </div>
            </section>
        <?php
        } elseif ($page == 'products') {
            // Products section
            include 'products.php';
        } elseif ($page == 'reviews') {
            // Reviews section
            include 'review_seller.php';
            // <?php
        } elseif ($page == 'orders') {
            // Orders section
        ?>
            <div class="header">
                <h2>Pemesanan</h2>
                <p>Klik tombol "Pesan via WhatsApp" untuk memesan produk langsung lewat WhatsApp.</p>
            </div>
            <?php
            $products = $conn->prepare("SELECT * FROM crud_041_book WHERE seller_id = ?");
            $products->bind_param("i", $seller_id);
            $products->execute();
            $products = $products->get_result();

            if ($products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()):
                    $wa_number = "62813398739335"; // Ganti dengan nomor WA Anda
                    $message = "Halo, saya tertarik memesan produk *" . $product['title'] . "* dari penjual " . $seller_name . ". Bisa tolong info lebih lanjut?";
                    $wa_link = "https://wa.me/" . $wa_number . "?text=" . urlencode($message);
                ?>
                    <div class="product-card">
                        <strong><?= htmlspecialchars($product['title']) ?></strong><br>
                        <p>Deskripsi: <?= htmlspecialchars($product['description']) ?></p>
                        <a href="<?= $wa_link ?>" target="_blank" class="btn btn-success">
                            <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
                        </a>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada produk untuk dipesan.</p>
            <?php endif; ?>
        <?php
        } elseif ($page == 'profil') {
            include 'profil_seller.php';
        }
        ?>
    </div>

    <footer>
        &copy; 2025 Sistem Penjualan Hidroponik
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Slider script
        document.querySelector('.btn-prev').addEventListener('click', function() {
            document.querySelector('.kategori-slider').scrollBy({
                left: -290,
                behavior: 'smooth'
            });
        });

        document.querySelector('.btn-next').addEventListener('click', function() {
            document.querySelector('.kategori-slider').scrollBy({
                left: 290,
                behavior: 'smooth'
            });
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>