<?php
// detail_ajax.php (versi employer)
session_start();
include 'koneksi.php';

$id = $_GET['id'] ?? '';
if (empty($id) || !is_numeric($id)) {
    echo "Data tidak ditemukan.";
    exit;
}

// Casting ke integer untuk keamanan dasar
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

        <?php if (isset($_SESSION['employer_id'])): ?>
            <div style="margin-top: 20px;">
                <a href="edit_lowongan.php?id=<?= $id ?>" style="background-color:gold;color:black;padding:10px 20px;text-decoration:none;border-radius:5px;margin-right:10px;">Edit</a>
                <a href="hapus_lowongan.php?id=<?= $id ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');" style="background-color:red;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">Hapus</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
} else {
    echo "Lowongan tidak ditemukan.";
}

$conn->close();
?>
