<?php
// detail_ajax.php

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
<div class="job-detail">
    <div class="detail-header">
        <img src="<?= htmlspecialchars($row['logo']) ?>" alt="Logo <?= htmlspecialchars($row['perusahaan']) ?>" class="detail-logo" style ="max-width:150px">
        <div>
            <h2><?= htmlspecialchars($row['judul']) ?></h2>
            <h4><?= htmlspecialchars($row['perusahaan']) ?></h4>
            <p><?= htmlspecialchars($row['lokasi']) ?> • <?= htmlspecialchars($row['kategori']) ?> • <?= htmlspecialchars($row['tipe']) ?></p>
        </div>
    </div>
    <div class="detail-body">
        <h3>Deskripsi Pekerjaan</h3>
        <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
        <h3>Gaji</h3>
        <p><?= htmlspecialchars($row['gaji']) ?></p>
        <a href="#" class="apply-button">Lamar Sekarang</a>
    </div>
</div>
<?php
} else {
    echo "Lowongan tidak ditemukan.";
}

$stmt->close();
$conn->close();
?>
