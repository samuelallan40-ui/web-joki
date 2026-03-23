<?php
// index.php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'functions/discord.php';

// Ambil daftar layanan dari database
$stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1");
$services = $stmt->fetchAll();
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Jasa Joki Mobile Legends</h1>
            <p>Rank Push, Skin Event, MCL, dan Layanan Lainnya</p>
            <a href="order.php" class="btn-primary">Order Sekarang</a>
        </div>
    </div>
</section>

<section id="layanan" class="services">
    <div class="container">
        <h2>Layanan Kami</h2>
        <div class="services-grid">
            <?php foreach($services as $service): ?>
            <div class="service-card">
                <i class="fas fa-gamepad"></i>
                <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                <p><?php echo htmlspecialchars($service['description']); ?></p>
                <div class="price">Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></div>
                <a href="order.php?service=<?php echo $service['id']; ?>" class="btn-order">Pesan</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="how-it-works" class="how-it-works">
    <div class="container">
        <h2>Cara Kerja</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Register Akun</h3>
                <p>Daftar akun gratis di website kami</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Pilih Layanan</h3>
                <p>Pilih layanan joki yang kamu butuhkan</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Isi Detail Akun</h3>
                <p>Masukkan ID dan password akun ML kamu</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Konfirmasi Order</h3>
                <p>Order akan diproses admin kami</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>