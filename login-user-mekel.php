<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Portal Kerja - Login / Register</title>

    <!-- Link CSS -->
    <link rel="stylesheet" href="/miniprojectt/styles/login.css?v=<?= time(); ?>" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet" />
</head>
<body>
    <!-- Navbar -->
<div class="navbar-container">
    <nav class="navbar">
        <a href="index.php" class="logo">
            <img src="assets/logo website/jobseeker.png" alt="Logo Web" />
        </a>
        <div class="nav-right">
            <a href="login_pelamar.php"><button class="outline-button">Masuk</button></a>
            <ul class="breadcrumb">
                <li><a href="index.php" class="nav-item active">Beranda</a></li>
            </ul>
        </div>
    </nav>
</div>


    <!-- Container Utama -->
    <div class="container">

    <!-- Panel Kiri -->
    <div class="left-panel">
        <img src="assets/background/job.jpg" alt="Login Background" class="bg-image" />
    </div>

        <!-- Panel Kanan -->
        <div class="right-panel">
            <div class="form-box">
                <!-- Tombol Toggle -->
                <div class="form-toggle">
                    <button id="loginToggle" class="active">Login</button>
                    <button id="registerToggle">Register</button>
                </div>

                <!-- Form Container -->
                <div class="form-container">
                    <!-- Form Login -->
                    <form id="loginForm" action="proses_login.php" method="post" class="active">
                        <label for="loginEmail">Email</label>
                        <input type="email" id="loginEmail" name="email" required />

                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="password" required />

                        <button type="submit" class="submit-btn">Masuk</button>

                        <div class="form-footer">
                            <a href="#">Lupa Password?</a>
                            <a href="employer_login.php">Anda seorang pemilik usaha?</a>
                        </div>
                    </form>

                    <!-- Form Register -->
                    <form id="registerForm" action="proses_register.php" method="post">
                        <label for="regName">Nama Lengkap</label>
                        <input type="text" id="regName" name="nama" required />

                        <label for="regEmail">Email</label>
                        <input type="email" id="regEmail" name="email" required />

                        <label for="regPassword">Password</label>
                        <input type="password" id="regPassword" name="password" required />

                        <label for="regConfirm">Konfirmasi Password</label>
                        <input type="password" id="regConfirm" name="confirm_password" required />

                        <button type="submit" class="submit-btn">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="javascript/script.js"></script>
</body>
</html>
