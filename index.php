<?php
// Koneksi ke database
include 'koneksi.php';

$keyword  = $_GET['keyword'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? '';

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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Job Portal Indonesia</title>
<link rel="stylesheet" href="/miniproject/styles/rumah.css?v=<?= time(); ?>">
<style>

</style>
</head>
<body>

<?php include 'navbar.php'; ?>

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
        <div class="job-card" data-slug="<?= htmlspecialchars($row['slug']) ?>">
          <!-- Menu titik tiga -->
          <div class="menu-container">
            <button class="menu-button">⋮</button>
            <ul class="dropdown-menu">
              <li><a href="#">Simpan</a></li>
              <li><a href="#">Tidak Tertarik</a></li>
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
              <?= htmlspecialchars($row['gaji']) ?>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Tidak ada lowongan ditemukan.</p>
    <?php endif; ?>
  </div>

  <div class="job-details" id="job-detail">
    <p style="text-align:center; color: #999;">Pilih lowongan untuk melihat detail.</p>
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

    const slug = card.getAttribute('data-slug');
    fetch(`detail_ajax.php?slug=${slug}`)
      .then(res => res.text())
      .then(html => {
        jobDetail.innerHTML = html;
      });
  });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
