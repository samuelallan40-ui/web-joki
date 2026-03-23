<?php
// my-orders.php
require_once 'config/database.php';
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

// Ambil semua order user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="orders-container">
    <div class="container">
        <h1>Riwayat Order</h1>
        
        <?php if(count($orders) > 0): ?>
            <div class="orders-table-wrapper">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Kode Order</th>
                            <th>Layanan</th>
                            <th>Target</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $order): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($order['order_code']); ?></strong></td>
                            <td><?php echo htmlspecialchars($order['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['target_rank']); ?></td>
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
                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>
                                <button class="btn-detail" onclick="showDetail(<?php echo $order['id']; ?>)">Detail</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-orders">
                <i class="fas fa-inbox"></i>
                <p>Belum ada order</p>
                <a href="order.php" class="btn-primary">Order Sekarang</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Detail Order -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modalContent"></div>
    </div>
</div>

<script>
function showDetail(orderId) {
    // Fetch detail order via AJAX
    fetch(`order-detail.php?id=${orderId}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('modalContent').innerHTML = data;
            document.getElementById('orderModal').style.display = 'block';
        });
}

document.querySelector('.close').onclick = function() {
    document.getElementById('orderModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('orderModal')) {
        document.getElementById('orderModal').style.display = 'none';
    }
}
</script>

<style>
.orders-container {
    padding: 50px 0;
    min-height: 70vh;
}

.orders-table-wrapper {
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

.orders-table th,
.orders-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.orders-table th {
    background: rgba(255, 71, 87, 0.2);
    color: #ff4757;
}

.orders-table tr:hover {
    background: rgba(255, 255, 255, 0.05);
}

.btn-detail {
    background: #ff4757;
    color: #fff;
    border: none;
    padding: 5px 12px;
    border-radius: 5px;
    cursor: pointer;
}

.empty-orders {
    text-align: center;
    padding: 50px;
}

.empty-orders i {
    font-size: 64px;
    color: #ff4757;
    margin-bottom: 20px;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
}

.modal-content {
    background: #1a1a2e;
    margin: 10% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 500px;
    position: relative;
}

.close {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 28px;
    cursor: pointer;
    color: #fff;
}
</style>

<?php require_once 'includes/footer.php'; ?>