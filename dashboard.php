<?php
// dashboard.php
require_once 'config/database.php';
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Ambil riwayat order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_orders = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="dashboard-container">
    <div class="container">
        <h1>Dashboard</h1>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Selamat Datang, <?php echo htmlspecialchars($_SESSION['full_name'] ?: $_SESSION['username']); ?></h3>
                <div class="user-info">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>WhatsApp:</strong> <?php echo htmlspecialchars($user['whatsapp'] ?: '-'); ?></p>
                    <p><strong>Saldo:</strong> Rp <?php echo number_format($user['saldo'], 0, ',', '.'); ?></p>
                </div>
                <a href="order.php" class="btn-primary">Order Joki Sekarang</a>
            </div>
            
            <div class="dashboard-card">
                <h3>Order Terbaru</h3>
                <?php if(count($recent_orders) > 0): ?>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Kode Order</th>
                                <th>Layanan</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_code']); ?></td>
                                <td><?php echo htmlspecialchars($order['service_name']); ?></td>
                                <td>Rp <?php echo number_format($order['price'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="status status-<?php echo $order['status']; ?>">
                                        <?php 
                                        $status_text = [
                                            'pending' => 'Menunggu',
                                            'processing' => 'Diproses',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan'
                                        ];
                                        echo $status_text[$order['status']];
                                        ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="my-orders.php" class="btn-secondary">Lihat Semua Order</a>
                <?php else: ?>
                    <p>Belum ada order. <a href="order.php">Order sekarang!</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>