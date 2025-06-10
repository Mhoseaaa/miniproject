<?php
session_start();

if (!isset($_SESSION['employer_email'])) {
    header("Location: login_employer.php");
    exit;
}

include 'koneksi.php';

$employerEmail = $_SESSION['employer_email'];
$employerEmail = mysqli_real_escape_string($conn, $employerEmail);

$query = "SELECT nama_perusahaan, email, no_telepon, alamat, logo FROM employers WHERE email = '$employerEmail' LIMIT 1";
$result = mysqli_query($conn, $query);

$employer = mysqli_fetch_assoc($result);

if (!$employer) {
    session_destroy();
    header("Location: login_employer.php");
    exit;
}

// Debug: Tampilkan data employer (bisa dihapus setelah debugging)
// echo "<pre>";
// print_r($employer);
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Profil Employer</title>
<style>
  /* GLOBAL STYLING */
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    padding-top: 60px;
  }

  /* NAVBAR */
  .navbar-container {
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }

  .navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px 15px;
    max-width: 1200px;
    margin: 0 auto;
  }

  .nav-right {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .nav-item {
    position: relative;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    color: black;
    padding-bottom: 5px;
  }

  .nav-item::after {
    content: "";
    display: block;
    width: 100%;
    height: 2px;
    background-color: #001f54;
    position: absolute;
    left: 0;
    bottom: 0;
    transform: scaleX(0);
    transition: transform 0.3s ease-in-out;
  }

  .nav-item.active::after,
  .nav-item:hover::after {
    transform: scaleX(1);
  }

  .logo img {
    width: 150px !important;
    height: auto;
  }

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

<!-- Navbar -->
<div class="navbar-container">
    <nav class="navbar">
        <a href="dashboard_employer.php" class="logo">
            <img src="assets/logo website/jobseeker.png" alt="Logo Web" />
        </a>
        <div class="nav-right">
            <span>Halo, <?= htmlspecialchars($employer['nama_perusahaan']) ?></span> |
            <a href="dashboard_employer.php" class="nav-item">Beranda</a> |
            <a href="profile_employer.php" class="nav-item active">Profil</a> |
            <a href="tambah_lowongan.php" class="nav-item">Tambah Lowongan</a> |
            <a href="logout.php" class="nav-item">Logout</a>
        </div>
    </nav>
</div>

<div class="profile-container">
    <h2>Profil Perusahaan</h2>
    <?php 
    // Perbaikan utama: Mengakses $employer['logo'] langsung
    if (!empty($employer['logo']) && file_exists($employer['logo'])): 
    ?>
    <div style="text-align:center; margin-bottom: 20px;">
        <img src="<?= htmlspecialchars($employer['logo']) ?>" alt="Logo Perusahaan" style="max-width:150px; max-height:150px; border-radius:10px; box-shadow: 0 0 8px rgba(0,0,0,0.1);" />
    </div>
    <?php else: ?>
    <div style="text-align:center; margin-bottom: 20px; color: #666;">
        Logo tidak tersedia (Debug: <?= isset($employer['logo']) ? 'Path: ' . htmlspecialchars($employer['logo']) : 'Kolom logo kosong' ?>)
    </div>
    <?php endif; ?>

    <div class="profile-item">
        <div class="label">Nama Perusahaan:</div>
        <div class="value"><?= htmlspecialchars($employer['nama_perusahaan']) ?></div>
    </div>
    <div class="profile-item">
        <div class="label">Email:</div>
        <div class="value"><?= htmlspecialchars($employer['email']) ?></div>
    </div>
    <div class="profile-item">
        <div class="label">Nomor Telepon:</div>
        <div class="value"><?= htmlspecialchars($employer['no_telepon']) ?></div>
    </div>
    <div class="profile-item">
        <div class="label">Alamat:</div>
        <div class="value"><?= htmlspecialchars($employer['alamat']) ?></div>
    </div>
    <div class="profile-item">
        <div class="label">Password:</div>
        <div class="value"><?= str_repeat('*', 8) ?></div>
    </div>
    <a href="dashboard_employer.php" class="back-link">Kembali ke Dashboard</a>
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