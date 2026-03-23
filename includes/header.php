<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joki ML - Jasa Joki Mobile Legends</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">Joki<span>ML</span></a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="#layanan">Layanan</a>
                <a href="#how-it-works">Cara Kerja</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="order.php" class="btn-order">Order Joki</a>
                    <a href="logout.php" class="btn-logout">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php" class="btn-register">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main>
