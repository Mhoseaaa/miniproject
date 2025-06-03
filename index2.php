<?php
// Koneksi ke database
include 'koneksi.php';

// Ambil input pencarian
$keyword  = $_GET['keyword'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? '';

// Query pencarian
$sql = "SELECT * FROM lowongan WHERE 1=1";
if (!empty($keyword)) {
    $sql .= " AND (judul LIKE '%" . $conn->real_escape_string($keyword) . "%' OR perusahaan LIKE '%" . $conn->real_escape_string($keyword) . "%')";
}
if (!empty($kategori)) {
    $sql .= " AND kategori = '" . $conn->real_escape_string($kategori) . "'";
}
if (!empty($lokasi)) {
    $sql .= " AND lokasi LIKE '%" . $conn->real_escape_string($lokasi) . "%'";
}
$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Job Portal Indonesia</title>
  <link rel="stylesheet" href="/miniproject/styles/beranda.css?v=<?= time(); ?>">
</head>
<body>

<?php include 'navbar.php' ?>

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
          <option value="Manufaktur" <?= $kategori === 'Manufaktur' ? 'select36ed' : '' ?>>Manufaktur</option>
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

  <div class="job-list">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="job-card">
          <div class="job-header">
            <div class="job-logo-container">
              <img src="<?= htmlspecialchars($row['logo']) ?>" alt="Logo <?= htmlspecialchars($row['perusahaan']) ?>">
            </div>
            <div class="job-info">
              <h3><?= htmlspecialchars($row['judul']) ?></h3>
              <p class="corp"><?= htmlspecialchars($row['perusahaan']) ?></p>
            </div>
            <div class="menu-container">
              <button class="menu-button">⋮</button>
              <ul class="dropdown-menu">
                <li><a href="#">Simpan</a></li>
                <li><a href="#">Tidak Tertarik</a></li>
              </ul>
            </div>
          </div>
          <p><?= htmlspecialchars($row['kategori']) ?></p>
          <p><?= htmlspecialchars($row['lokasi']) ?></p>
          <p><?= htmlspecialchars($row['gaji']) ?></p>
          <p>(<?= htmlspecialchars($row['tipe']) ?>)</p>
          <div class="job-button-container">
            <a href="detail.php?slug=<?= urlencode($row['slug']) ?>">Lihat Detail</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;">Tidak ada lowongan ditemukan.</p>
    <?php endif; ?>
  </div>

  <footer class="footer">
    <div class="footer-container">
      <div class="footer-section">
        <h3>JobSeeker</h3>
        <p>Temukan pekerjaan impianmu dengan mudah dan cepat.</p>
      </div>
      <div class="footer-section">
        <h4>Menu</h4>
        <ul>
          <li><a href="/index.php">Beranda</a></li>
          <li><a href="/index.php">Lowongan</a></li>
          <li><a href="#">Tentang Kami</a></li>
          <li><a href="#">Kontak</a></li>
        </ul>
      </div>
    </div>
    <p class="footer-copy">&copy; 2025 JobSeeker. All rights reserved.</p>
  </footer>

  

</body>
</html>

<?php $conn->close(); ?>
