// assets/js/script.js
// Smooth scroll untuk anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Konfirmasi logout
const logoutBtn = document.querySelector('.btn-logout');
if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
        if (!confirm('Yakin ingin logout?')) {
            e.preventDefault();
        }
    });
}

// Format Rupiah di input
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

// Auto close alert setelah 5 detik
const alerts = document.querySelectorAll('.alert');
alerts.forEach(alert => {
    setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
});
