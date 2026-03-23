<?php
// order.php
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'functions/discord.php';
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

// Ambil daftar layanan
$stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1");
$services = $stmt->fetchAll();

$error = '';
$success = '';

// Generate order code
function generateOrderCode() {
    return 'JML-' . strtoupper(uniqid());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'] ?? 0;
    $target_rank = trim($_POST['target_rank'] ?? '');
    $current_rank = trim($_POST['current_rank'] ?? '');
    $ml_account_id = trim($_POST['ml_account_id'] ?? '');
    $ml_password = trim($_POST['ml_password'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    
    // Ambil detail service
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? AND is_active = 1");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();
    
    if (!$service) {
        $error = 'Layanan tidak valid!';
    } elseif (empty($target_rank) || empty($ml_account_id)) {
        $error = 'Target rank dan ID ML wajib diisi!';
    } else {
        $order_code = generateOrderCode();
        
        // Insert order
        $stmt = $pdo->prepare("
            INSERT INTO orders (order_code, user_id, service_id, service_name, current_rank, target_rank, ml_account_id, ml_password, notes, price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([
            $order_code, 
            $user_id, 
            $service['id'], 
            $service['name'], 
            $current_rank, 
            $target_rank, 
            $ml_account_id, 
            $ml_password, 
            $notes, 
            $service['price']
        ])) {
            $order_id = $pdo->lastInsertId();
            
            // Ambil data user untuk webhook
            $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            // Kirim ke Discord
            $orderData = [
                'id' => $order_code,
                'order_id' => $order_id,
                'username' => $user['username'],
                'layanan' => $service['name'],
                'target' => $target_rank,
                'current_rank' => $current_rank ?: 'Tidak disebutkan',
                'ml_id' => $ml_account_id,
                'harga' => $service['price']
            ];
            
            sendToDiscord($orderData);
            
            $success = "Order berhasil dibuat! Kode order: <strong>$order_code</strong>. Admin akan segera memproses.";
        } else {
            $error = 'Gagal membuat order, coba lagi!';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="order-container">
    <div class="container">
        <h1>Form Order Joki ML</h1>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php else: ?>
            <form method="POST" action="" class="order-form">
                <div class="form-group">
                    <label>Pilih Layanan *</label>
                    <select name="service_id" required>
                        <option value="">-- Pilih Layanan --</option>
                        <?php foreach($services as $service): ?>
                        <option value="<?php echo $service['id']; ?>">
                            <?php echo htmlspecialchars($service['name']); ?> - Rp <?php echo number_format($service['price'], 0, ',', '.'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Rank Saat Ini (Opsional)</label>
                    <select name="current_rank">
                        <option value="">-- Pilih Rank --</option>
                        <option>Warrior</option>
                        <option>Elite</option>
                        <option>Master</option>
                        <option>Grandmaster</option>
                        <option>Epic</option>
                        <option>Legend</option>
                        <option>Mythic</option>
                        <option>Mythical Honor</option>
                        <option>Mythical Glory</option>
                        <option>Mythical Immortal</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Target Rank *</label>
                    <select name="target_rank" required>
                        <option value="">-- Pilih Target --</option>
                        <option>Warrior</option>
                        <option>Elite</option>
                        <option>Master</option>
                        <option>Grandmaster</option>
                        <option>Epic</option>
                        <option>Legend</option>
                        <option>Mythic</option>
                        <option>Mythical Honor</option>
                        <option>Mythical Glory</option>
                        <option>Mythical Immortal</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>ID Mobile Legends *</label>
                    <input type="text" name="ml_account_id" placeholder="Contoh: 123456789" required>
                    <small>Cara cek ID: Buka profil ML -> Lihat ID di pojok kiri atas</small>
                </div>
                
                <div class="form-group">
                    <label>Password Akun ML</label>
                    <input type="password" name="ml_password" placeholder="Kosongkan jika tidak ingin memberikan password">
                    <small>Password akan dienkripsi dan hanya admin yang bisa melihat</small>
                </div>
                
                <div class="form-group">
                    <label>Catatan Tambahan</label>
                    <textarea name="notes" rows="3" placeholder="Catatan khusus untuk joki (misal: hero yang dipakai, dll)"></textarea>
                </div>
                
                <div class="order-summary">
                    <h3>Ringkasan Order</h3>
                    <p>Harga akan muncul setelah memilih layanan</p>
                    <div class="total-price">Total: <span id="total-price">Rp 0</span></div>
                </div>
                
                <button type="submit" class="btn-primary">Buat Order</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
// Auto update harga berdasarkan layanan yang dipilih
document.querySelector('select[name="service_id"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const text = selectedOption.text;
    const match = text.match(/Rp ([\d\.]+)/);
    if (match) {
        document.getElementById('total-price').innerText = 'Rp ' + match[1];
    } else {
        document.getElementById('total-price').innerText = 'Rp 0';
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>