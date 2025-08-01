<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login_user.php");
    exit;
}

include 'koneksi.php';

$userEmail = $_SESSION['user_email'];

// Ambil data user dari database TANPA prepared statement
$userEmailEsc = $conn->real_escape_string($userEmail);
$sqlUser = "SELECT name, email FROM users WHERE email = '$userEmailEsc'";
$resultUser = $conn->query($sqlUser);

if (!$resultUser || $resultUser->num_rows === 0) {
    session_destroy();
    header("Location: login_user.php");
    exit;
}

$user = $resultUser->fetch_assoc();

// Ambil parameter filter
$keyword  = $_GET['keyword'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? '';

$sql = "SELECT * FROM lowongan WHERE 1=1";
if (!empty($keyword)) {
    $keywordEsc = $conn->real_escape_string($keyword);
    $sql .= " AND (judul LIKE '%$keywordEsc%' OR perusahaan LIKE '%$keywordEsc%')";
}
if (!empty($kategori)) {
    $kategoriEsc = $conn->real_escape_string($kategori);
    $sql .= " AND kategori = '$kategoriEsc'";
}
if (!empty($lokasi)) {
    $lokasiEsc = $conn->real_escape_string($lokasi);
    $sql .= " AND lokasi LIKE '%$lokasiEsc%'";
}
$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
if (!$result) {
    die("Query error: " . $conn->error);
}
?>




<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Job Portal Indonesia</title>
<link rel="stylesheet" href="navbar.css">
<style>
body {
    overflow-x: hidden;
}

.outline-button {
    background: white;
    color: #001f54;
    border: 2px solid #001f54;
    padding: 8px 16px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s ease;
}

.outline-button:hover {
    background: #001f54;
    color: white;
}

/* BREADCRUMB */
.breadcrumb {
    display: flex;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 16px;
}

.breadcrumb li {
    display: flex;
    align-items: center;
    color: #555;
}

.breadcrumb li a {
    text-decoration: none;
    color: #001f54;
    font-weight: bold;
    transition: color 0.3s;
}

.breadcrumb li a:hover {
    color: #000b1d;
}

.breadcrumb li span {
    margin: 0 8px;
    color: #999;
}

.breadcrumb .active {
    font-weight: bold;
    color: #000;
}

/* LAYOUT */
.container {
    width: 80%;
    margin: auto;
    padding: 20px;
    margin-top: 80px;
}

.main-container {
    display: flex;
    height: 90vh;
    padding: 20px;
}

.job-list {
    width: 60%;
    overflow-y: auto;
    border-right: 0 solid #ccc;
    padding-right: 10px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 20px;
    padding: 20px;
}

.job-details {
  width: 65%;
  padding: 40px;
  background-color: #f5f6f8;
  min-height: 400px;
  border-radius: 12px;
  overflow-y: auto;
  display: flex;
  align-items: flex-start;
}

.job-placeholder {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  color: #333;
}

.job-placeholder h2 {
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 8px;
}

.job-placeholder p {
  font-size: 16px;
  color: #666;
  margin-bottom: 20px;
}

.job-placeholder img {
  width: 165px;
  border-radius: 50%;
  background-color: #ffeaf5;
  padding: 4px;

  margin-left: 90%;
}

/* JOB CARD - HOVER ONLY VERSION */
.job-card {
    cursor: pointer;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    background: white;
    padding: 15px;
    border-radius: 20px;
    width: 500px;
    height: 260px;
    
    /* Border standar */
    border: 2px solid #e0e0e0;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    
    /* Transisi hanya untuk border dan shadow */
    transition: 
        border-color 0.3s ease,
        box-shadow 0.3s ease;
}

.job-card:hover {
    /* Hanya ubah warna border dan shadow */
    border-color: #001f54;
    box-shadow: 0 2.5px 14px rgba(0, 31, 84, 0.12);
}

.job-card p {
    font-size: 14px;
    color: #666;
    margin: 7px 0;
}

.corp {
    font-size: 14px;
    font-weight: bold;
    color: #555;
    margin-top: -5px !important;
}

.job-card img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
}

.job-logo-container {
    width: 80px;
    height: 80px;
    border-radius: 10px;
}

.job-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 2px;
}

.job-header {
    display: flex;
    align-items: center;
    gap: 15px;
}

.job-card h5 {
    margin: 10px 0;
}

.job-card a {
    margin-top: 10px;
    text-align: center;
    display: block;
    padding: 10px;
    background-color: #001f54;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease-in-out;
}

.job-card a:hover {
    background-color: #000e27;
}

/* MENU DROPDOWN */
.menu-container {
    position: absolute;
    top: 14px;
    right: 10px;
}

.menu-button {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    padding: 5px;
    color: #333;
}

.dropdown-menu {
    position: absolute;
    top: 30px;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    list-style: none;
    padding: 10px;
    display: none;
    min-width: 120px;
}

.menu-container:hover .dropdown-menu,
.menu-button:focus + .dropdown-menu {
    display: inline-block;
}

.dropdown-menu li a {
    text-decoration: none;
    color: white;
    display: block;
    transition: background 0.3s;
}

.dropdown-menu li a:hover {
    background-color: #000b1d;
}

/* SEARCH BOX */
.search-box {
    background-color: #001f54;
    background-size: cover;
    background-position: center;
    padding: 30px 20px;
    border-radius: 0;
    color: white;
    margin: 0;
    width: 100%;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.search-box form {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 15px;
    max-width: 1200px;
    margin: auto;
}

.search-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    width: 100%;
}

.search-field {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 200px;
}

.search-field label {
    font-size: 14px;
    margin-bottom: 8px;
    color: white;
    display: block;
}

.search-field input,
.search-field select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
}

.search-field input::placeholder {
    color: #aaa;
}

button[type="submit"] {
    background-color: #ff007f;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    height: 42px;
    box-sizing: border-box;
    margin-top: 20px;
}

button[type="submit"]:hover {
    background-color: #e60073;
}

.mt {
    margin-right: 70px;
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
            <a href="dashboard_user.php" class="logo">
                <img src="assets/logo website/jobseeker.png" alt="JobSeeker Logo">
            </a>
            <div class="nav-right">
                <a href="dashboard_user.php" class="nav-item">Dashboard</a>
                <a href="lowongan_user.php" class="nav-item active">Daftar Lowongan</a>
                <a href="lamaran_user.php" class="nav-item">Lamaran Saya</a>
                <a href="profile_user.php" class="nav-item">Profil</a>
                <a href="logout.php" class="nav-item">Logout</a>
            </div>
        </nav>
    </div>

<div class="search-box">
  <form method="GET">
    <div class="search-group">
      <div class="search-field">
        <label for="keyword">Kata Kunci</label>
        <input type="text" name="keyword" placeholder="Masukkan kata kunci" value="<?= htmlspecialchars($keyword) ?>">
      </div>
      <div class="search-field">

      <label for="kategori">Klasifikasi</label>
          <select name="kategori">
          <option value="">Semua Klasifikasi</option>
          <option value="IT" <?= $kategori === 'IT' ? 'selected' : '' ?>>IT</option>
          <option value="Desain" <?= $kategori === 'Desain' ? 'selected' : '' ?>>Desain</option>
          <option value="Ritel" <?= $kategori === 'Ritel' ? 'selected' : '' ?>>Ritel</option>
          <option value="Food & Beverage" <?= $kategori === 'Food & Beverage' ? 'selected' : '' ?>>Food & Beverage</option>
          <option value="Pendidikan" <?= $kategori === 'Pendidikan' ? 'selected' : '' ?>>Pendidikan</option>
          <option value="Kesehatan" <?= $kategori === 'Kesehatan' ? 'selected' : '' ?>>Kesehatan</option>
          <option value="Keuangan" <?= $kategori === 'Keuangan' ? 'selected' : '' ?>>Keuangan</option>
          <option value="Marketing" <?= $kategori === 'Marketing' ? 'selected' : '' ?>>Marketing</option>
          <option value="Teknik" <?= $kategori === 'Teknik' ? 'selected' : '' ?>>Teknik</option>
          <option value="Manufaktur" <?= $kategori === 'Manufaktur' ? 'selected' : '' ?>>Manufaktur</option>
          <option value="Transportasi" <?= $kategori === 'Transportasi' ? 'selected' : '' ?>>Transportasi</option>
          <option value="Administrasi" <?= $kategori === 'Administrasi' ? 'selected' : '' ?>>Administrasi</option>
          <option value="Hukum" <?= $kategori === 'Hukum' ? 'selected' : '' ?>>Hukum</option>
          </select>
          <!-- Tambahkan kategori lainnya -->

      </div>
      <div class="search-field">
        <label for="lokasi">Daerah</label>
        <input type="text" name="lokasi" placeholder="Masukkan lokasi" value="<?= htmlspecialchars($lokasi) ?>">
      </div>
      <button type="submit">Cari</button>
    </div>
  </form>
</div>

<div class="main-container">
  <div class="job-list">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="job-card" data-id="<?= htmlspecialchars($row['id']) ?>">

          <img src="<?= htmlspecialchars($row['logo'] ?? '/path/to/default-logo.png') ?>" alt="<?= htmlspecialchars($row['perusahaan']) ?>" style="height: 60px; margin-bottom: 15px;">
          <div style="font-size: 18px; font-weight: bold; color: #6a1b9a; margin-top: 5px;">
              <?= htmlspecialchars($row['judul']) ?>
          </div>
          <div style="font-size: 14px; color: #333; margin-bottom: 10px;">
              <?= htmlspecialchars($row['perusahaan']) ?>
          </div>
          <div style="font-size: 13px; color: #555; margin-bottom: 5px;">
              <?= htmlspecialchars($row['kategori']) ?> (<?= htmlspecialchars($row['tipe']) ?>)
          </div>
          <div style="font-size: 13px; color: #555; margin-bottom: 5px;">
              <?= htmlspecialchars($row['lokasi']) ?>
          </div>
          <div style="font-size: 13px; color: #555; margin-bottom: 5px;">
              Rp.<?= htmlspecialchars($row['gaji_min']) ?> - Rp.<?= htmlspecialchars($row['gaji_max']) ?>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Tidak ada lowongan ditemukan.</p>
    <?php endif; ?>
  </div>


<div class="job-details" id="job-detail">
  <div class="job-placeholder">
    <h2><= Pilih lowongan kerja</h2>
    <p>Tampilkan detail di sini</p>
    <img src="assets/background/lihat.png" alt="Logo Web" />
  </div>
</div>




<script>
// Klik job-card buat load detail
const jobCards = document.querySelectorAll('.job-card');
const jobDetail = document.getElementById('job-detail');

jobCards.forEach(card => {
  card.addEventListener('click', (e) => {
    // Cegah klik di menu titik tiga
    if (e.target.closest('.menu-container')) return;

    const id = card.getAttribute('data-id'); // Ganti ke data-id
    fetch(`detail_ajax.php?id=${id}`)       // Ganti parameter ke id
      .then(res => res.text())
      .then(html => {
        jobDetail.innerHTML = html;
      });
  });
});
</script>



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

<?php $conn->close(); ?>
