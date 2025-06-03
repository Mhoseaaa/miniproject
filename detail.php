<?php
// Koneksi database
include 'koneksi.php';

// Ambil ID atau slug dari URL
$id = $_GET['id'] ?? null;
$slug = $_GET['slug'] ?? null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM lowongan WHERE id = ?");
    $stmt->bind_param("i", $id);
} elseif ($slug) {
    $stmt = $conn->prepare("SELECT * FROM lowongan WHERE slug = ?");
    $stmt->bind_param("s", $slug);
} else {
    echo "Lowongan tidak ditemukan.";
    exit;
}

$stmt->execute();
$result = $stmt->get_result();
$lowongan = $result->fetch_assoc();

if (!$lowongan) {
    echo "Lowongan tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Detail lowongan kerja Backend Developer di Code Labs, Yogyakarta, Indonesia. Gaji Rp 5.000.000 - Rp 8.000.000. Ajukan lamaran sekarang!">
    <title>Detail Lowongan - Job Seeker</title>
    <link rel="stylesheet" href="/miniproject/styles/detail.css?v=<?= time(); ?>">
</head>

<body>
    <nav class="navbar">
        <a href="index.html" class="logo"><img src="assets/logo website/jobseeker.png" alt="Logo Web"></a>
        <div class="nav-right">
            <a href="/login.html"><button class="outline-button">Masuk</button></a>
            <ul class="breadcrumb">
                <li><a href="/index.html" class="nav-item">Beranda</a></li>
                <li><span></span></li>
                <li><a href="code_labs.html" class="nav-item active">Detail</a></li>
                <li><span></span></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <div class="job-header">
        <img src="<?= htmlspecialchars($lowongan['logo']) ?>" alt="Logo Perusahaan">
            <div>
            <h1><?= htmlspecialchars($lowongan['judul']) ?></h1>
            <p class="company"><?= htmlspecialchars($lowongan['perusahaan']) ?></p>
            <p><?= htmlspecialchars($lowongan['lokasi']) ?></p>
            <p class="category"><?= htmlspecialchars($lowongan['kategori']) ?></p>
            <p class="salary"><?= htmlspecialchars($lowongan['gaji']) ?></p>
            <p class="deadline">Diposting: <?= date("d M Y", strtotime($lowongan['created_at'])) ?></p>
                <p class="deadline">Batas Lamaran: <?= htmlspecialchars($lowongan['batas_lamaran']) ?></p>
                <div class="job-buttons">
                    <button class="apply-button"><a href="/lamaran.html">Lamar Sekarang</a></button>
                    <button class="apply-button"><a href="/index.html">Kembali Ke Beranda</a></button>
                </div>
            </div>
        </div>

        <section class="job-desc">
            <h2>Deskripsi Pekerjaan</h2>
            <p><?= nl2br(htmlspecialchars($lowongan['deskripsi'])) ?></p>
        </section>

        <section class="job-requirements">
            <h2>Syarat & Kualifikasi</h2>
            <p><?= nl2br(htmlspecialchars($lowongan['kualifikasi'])) ?></p>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>JobSeeker</h3>
                <p>Temukan pekerjaan impianmu dengan mudah dan cepat.</p>
            </div>
            <div class="footer-section">
                <h4>Menu</h4>
                <ul>
                    <li><a href="/index.html">Beranda</a></li>
                    <li><a href="/index.html">Lowongan</a></li>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </div>
        </div>
        <p class="footer-copy">&copy; 2025 JobSeeker. All rights reserved.</p>
    </footer>
</body>

</html>