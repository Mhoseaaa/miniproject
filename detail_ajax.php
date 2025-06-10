<?php
// detail_ajax.php
session_start();
include 'koneksi.php';

$id = $_GET['id'] ?? '';
if (empty($id) || !is_numeric($id)) {
    echo "Data tidak ditemukan.";
    exit;
}

// Aman secara dasar karena sudah dicek is_numeric, meski bukan cara terbaik
$id = (int)$id;

$sql = "SELECT * FROM lowongan WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
?>
<link rel="stylesheet" href="styles/detail_ajax.css?v=<?= time(); ?>">

<div class="job-detail">
    <div class="detail-header">
        <img src="<?= htmlspecialchars($row['logo']) ?>" alt="Logo <?= htmlspecialchars($row['perusahaan']) ?>" class="detail-logo" style="max-width:150px">
        <div>
            <h2><?= htmlspecialchars($row['judul']) ?></h2>
            <h4><?= htmlspecialchars($row['perusahaan']) ?></h4>
            <b><p><?= htmlspecialchars($row['lokasi']) ?> • <?= htmlspecialchars($row['kategori']) ?> • <?= htmlspecialchars($row['tipe']) ?></p></b>
        </div>
    </div>
    <div class="detail-body">
        <h3>Deskripsi Pekerjaan</h3>
        <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
        <h3>Kualifikasi Pekerjaan</h3>
        <p><?= nl2br(htmlspecialchars($row['kualifikasi'])) ?></p>
        <h3>Gaji</h3>
        <p>Rp.<?= htmlspecialchars($row['gaji_min']) ?> - Rp.<?= htmlspecialchars($row['gaji_max']) ?></p>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="lamar.php?id=<?= $id ?>" class="apply-button">Lamar Sekarang</a>
        <?php else: ?>
            <a href="login_user.php?redirect=dashboard_user.php?id=<?= $id ?>" class="apply-button">Login untuk Melamar</a>
        <?php endif; ?>
    </div>
</div>
<?php
} else {
    echo "Lowongan tidak ditemukan.";
}

$conn->close();
?>
