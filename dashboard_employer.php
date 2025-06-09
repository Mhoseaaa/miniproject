<?php
session_start();

if (!isset($_SESSION['employer_id'])) {
    header("Location: login_employer.php");
    exit;
}

include 'koneksi.php';

$employerId = $_SESSION['employer_id'];

// Initialize search variables
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$lokasi = isset($_GET['lokasi']) ? $_GET['lokasi'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Ambil data employer dari database
$stmt = $conn->prepare("SELECT id, nama_perusahaan, email FROM employers WHERE id = ?");
$stmt->bind_param("i", $employerId);
$stmt->execute();
$resultEmployer = $stmt->get_result();
$employer = $resultEmployer->fetch_assoc();

if (!$employer) {
    session_destroy();
    header("Location: login_employer.php");
    exit;
}

// Ambil lowongan yang dibuat oleh employer ini dengan filter pencarian
$sql = "SELECT * FROM lowongan WHERE employer_id = ?";
$params = [$employerId];
$types = "i";

if (!empty($keyword)) {
    $sql .= " AND (judul LIKE ? OR perusahaan LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
    $types .= "ss";
}

if (!empty($kategori)) {
    $sql .= " AND kategori = ?";
    $params[] = $kategori;
    $types .= "s";
}

if (!empty($lokasi)) {
    $sql .= " AND lokasi LIKE ?";
    $params[] = "%$lokasi%";
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Job Portal Indonesia</title>
<link rel="stylesheet" href="styles/index.css?v=<?= time(); ?>">
<style>
  /* GLOBAL STYLING */
body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    overflow-x: hidden;
    padding-top: 60px; /* Sesuaikan dengan tinggi navbar */
}

main {
    overflow-x: hidden;
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

/* JOB CARD */
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
    border: 2px solid #e0e0e0;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.job-card:hover {
    border-color: #001f54;
    box-shadow: 0 2.5px 14px rgba(0, 31, 84, 0.12);
}

.job-card p {
    font-size: 14px;
    color: #666;
    margin: 7px 0;
}

.job-card img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
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

/* ADD JOB BUTTON */
.add-job-button {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #001f54;
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    text-decoration: none;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    z-index: 1000;
}

.add-job-button:hover {
    background-color: #000b1d;
    transform: scale(1.1);
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
            <a href="profile_employer.php" class="nav-item">Profil</a> |
            <a href="tambah_lowongan.php" class="nav-item">Tambah Lowongan</a> |
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
          <!-- Menu titik tiga -->
          <div class="menu-container">
            <button class="menu-button">⋮</button>
            <ul class="dropdown-menu">
              <li><a href="edit_job.php?id=<?= $row['id'] ?>">Edit</a></li>
              <li><a href="delete_job.php?id=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">Hapus</a></li>
            </ul>
          </div>

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
</div>

<!-- Floating Add Job Button -->
<a href="add_job.php" class="add-job-button">+</a>

<script>
// Klik job-card buat load detail
const jobCards = document.querySelectorAll('.job-card');
const jobDetail = document.getElementById('job-detail');

jobCards.forEach(card => {
  card.addEventListener('click', (e) => {
    // Cegah klik di menu titik tiga
    if (e.target.closest('.menu-container')) return;

    const id = card.getAttribute('data-id');
    fetch(`detail_employer.php?id=${id}`)
      .then(res => res.text())
      .then(html => {
        jobDetail.innerHTML = html;
      });
  });
});
</script>

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