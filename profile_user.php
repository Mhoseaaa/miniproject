<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login_user.php");
    exit;
}

include 'koneksi.php';

$userEmail = $_SESSION['user_email'];

// GANTI DENGAN QUERY BIASA (TANPA BIND)
$sql = "SELECT name, email FROM users WHERE email = '$userEmail'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login_user.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="navbar.css">
<title>Profil Saya</title>
<style>
  /* PROFILE CONTENT */
  .profile-container {
    max-width: 500px;
    margin: 40px auto;
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
  }

  h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #001f54;
  }

  .profile-item {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
  }

  .label {
    font-weight: bold;
    color: #001f54;
    margin-bottom: 5px;
  }

  .value {
    font-size: 16px;
    color: #333;
  }

  .back-link {
    display: inline-block;
    margin-top: 20px;
    color: #001f54;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s;
  }

  .back-link:hover {
    color: #000e27;
    text-decoration: underline;
  }

  /* FOOTER */
  .footer {
    background-color: #001f54;
    color: white;
    padding: 10px 20px;
    text-align: center;
    margin-top: 40px;
  }

  .footer-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: auto;
    text-align: left;
  }

  .footer-section {
    flex: 1;
    min-width: 250px;
    margin: 10px;
  }

  .footer-section h3,
  .footer-section h4 {
    margin-bottom: 10px;
    font-size: 18px;
  }

  .footer-section ul {
    list-style: none;
    padding: 0;
  }

  .footer-section ul li {
    margin: 5px 0;
  }

  .footer-section ul li a {
    color: white;
    text-decoration: none;
    transition: color 0.3s;
  }

  .footer-section ul li a:hover {
    color: #ff007f;
  }

  .footer-copy {
    margin-top: 20px;
    font-size: 14px;
    opacity: 0.7;
  }
</style>
</head>
<body>

<div class="navbar-container">
    <nav class="navbar">
        <a href="dashboard_user.php" class="logo">
            <img src="assets/logo website/jobseeker.png" alt="JobSeeker Logo">
        </a>
        <div class="nav-right">
            <a href="dashboard_user.php" class="nav-item">Dashboard</a>
            <a href="lowongan_user.php" class="nav-item">Daftar Lowongan</a>
            <a href="lamaran_user.php" class="nav-item">Lamaran Saya</a>
            <a href="profile_user.php" class="nav-item active">Profil</a>
            <a href="logout.php" class="nav-item">Logout</a>
        </div>
    </nav>
</div>

<div class="profile-container">
    <h2>Profil Saya</h2>
    <div class="profile-item">
        <div class="label">Nama:</div>
        <div class="value"><?= htmlspecialchars($user['name']) ?></div>
    </div>
    <div class="profile-item">
        <div class="label">Email:</div>
        <div class="value"><?= htmlspecialchars($user['email']) ?></div>
    </div>
    <div class="profile-item">
        <div class="label">Password:</div>
        <div class="value"><?= str_repeat('*', 8) ?></div>
    </div>
    <a href="dashboard_user.php" class="back-link">Kembali ke Dashboard</a>
</div>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>JobSeeker</h3>
            <p>Temudahkan pencarian kerja untuk masa depan yang lebih baik</p>
        </div>
        <div class="footer-section">
            <h4>Perusahaan</h4>
            <ul>
                <li><a href="#">Tentang Kami</a></li>
                <li><a href="#">Karir</a></li>
                <li><a href="#">Blog</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Dukungan</h4>
            <ul>
                <li><a href="#">Pusat Bantuan</a></li>
                <li><a href="#">Kebijakan Privasi</a></li>
                <li><a href="#">Syarat & Ketentuan</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Kontak</h4>
            <ul>
                <li>Email: info@jobseeker.id</li>
                <li>Telepon: (021) 1234-5678</li>
                <li>Alamat: Yogyakarta, Indonesia</li>
            </ul>
        </div>
    </div>
    <div class="footer-copy">
        <p>&copy; 2025 JobSeeker Indonesia. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>
