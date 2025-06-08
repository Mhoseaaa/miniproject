<?php
// detail_ajax.php
session_start();
include 'koneksi.php';

$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    echo "Data tidak ditemukan.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM lowongan WHERE slug = ? LIMIT 1");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

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
        <p><?= htmlspecialchars($row['gaji']) ?></p>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="lamar.php?slug=<?= $slug ?>" class="apply-button">Lamar Sekarang</a>
        <?php else: ?>
            <a href="login_user.php?redirect=lamar.php?slug=<?= $slug ?>" class="apply-button">Login untuk Melamar</a>
        <?php endif; ?>
    </div>
</div>
<?php
} else {
    echo "Lowongan tidak ditemukan.";
}

$stmt->close();
$conn->close();
?>