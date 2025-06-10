<?php
// Koneksi ke database
include 'koneksi.php';

$keyword = $_GET['keyword'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$lokasi = $_GET['lokasi'] ?? '';

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
    <title>JobSeeker - Portal Lowongan Kerja Terbaik di Indonesia</title>
    <meta name="description"
        content="Temukan lowongan kerja terbaru di berbagai bidang dan lokasi di Indonesia. JobSeeker membantu Anda menemukan pekerjaan impian.">
    <link rel="stylesheet" href="index.css">
</head>

<style>
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
        margin-bottom: 20px;
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
</style>

<body>

    <!-- Navbar -->
    <div class="navbar-container">
        <nav class="navbar">
            <a href="employer/dashboard_employer.php" class="logo">
                <img src="assets/logo website/jobseeker.png" alt="JobSeeker Logo">
            </a>
            <div class="nav-right">
                <a href="login_user.php" class="nav-item active">Masuk</a>
                <a href="register_user.php" class="nav-item">Daftar</a>
            </div>
        </nav>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Temukan Pekerjaan Impian Anda</h1>
            <p>Ribuan lowongan kerja terbaru dari perusahaan terpercaya di seluruh Indonesia</p>
        </div>
    </section>

    <div class="search-box">
        <form method="GET">
            <div class="search-group">
                <div class="search-field">
                    <label for="keyword">Kata Kunci</label>
                    <input type="text" name="keyword" placeholder="Masukkan kata kunci"
                        value="<?= htmlspecialchars($keyword) ?>">
                </div>
                <div class="search-field">

                    <label for="kategori">Klasifikasi</label>
                    <select name="kategori">
                        <option value="">Semua Klasifikasi</option>
                        <option value="IT" <?= $kategori === 'IT' ? 'selected' : '' ?>>IT</option>
                        <option value="Desain" <?= $kategori === 'Desain' ? 'selected' : '' ?>>Desain</option>
                        <option value="Ritel" <?= $kategori === 'Ritel' ? 'selected' : '' ?>>Ritel</option>
                        <option value="Food & Beverage" <?= $kategori === 'Food & Beverage' ? 'selected' : '' ?>>Food &
                            Beverage</option>
                        <option value="Pendidikan" <?= $kategori === 'Pendidikan' ? 'selected' : '' ?>>Pendidikan</option>
                        <option value="Kesehatan" <?= $kategori === 'Kesehatan' ? 'selected' : '' ?>>Kesehatan</option>
                        <option value="Keuangan" <?= $kategori === 'Keuangan' ? 'selected' : '' ?>>Keuangan</option>
                        <option value="Marketing" <?= $kategori === 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                        <option value="Teknik" <?= $kategori === 'Teknik' ? 'selected' : '' ?>>Teknik</option>
                        <option value="Manufaktur" <?= $kategori === 'Manufaktur' ? 'selected' : '' ?>>Manufaktur</option>
                        <option value="Transportasi" <?= $kategori === 'Transportasi' ? 'selected' : '' ?>>Transportasi
                        </option>
                        <option value="Administrasi" <?= $kategori === 'Administrasi' ? 'selected' : '' ?>>Administrasi
                        </option>
                        <option value="Hukum" <?= $kategori === 'Hukum' ? 'selected' : '' ?>>Hukum</option>
                    </select>
                    <!-- Tambahkan kategori lainnya -->

                </div>
                <div class="search-field">
                    <label for="lokasi">Daerah</label>
                    <input type="text" name="lokasi" placeholder="Masukkan lokasi"
                        value="<?= htmlspecialchars($lokasi) ?>">
                </div>
                <button type="submit">Cari</button>
            </div>
        </form>
    </div>

    <!-- Job Listings -->
    <section class="job-section">
        <div class="section-header">
            <h2 class="section-title">Lowongan Terbaru</h2>
            <a href="#" class="view-all">Lihat Semua <i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="job-list">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="job-card" onclick="window.location.href='login_user.php?'">
                        <button class="save-btn" title="Simpan lowongan"><i class="far fa-bookmark"></i></button>
                        <div class="job-card-header">
                            <img src="<?= htmlspecialchars($row['logo'] ?? 'assets/default-logo.png') ?>"
                                alt="<?= htmlspecialchars($row['perusahaan']) ?>" class="company-logo">
                            <div class="job-info">
                                <h3 class="job-title"><?= htmlspecialchars($row['judul']) ?></h3>
                                <p class="company-name"><?= htmlspecialchars($row['perusahaan']) ?></p>
                                <div class="job-meta">
                                    <span class="job-meta-item"><i class="fas fa-briefcase"></i>
                                        <?= htmlspecialchars($row['kategori']) ?></span>
                                    <span class="job-meta-item"><i class="fas fa-clock"></i>
                                        <?= htmlspecialchars($row['tipe']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="job-card-body">
                            <p class="job-description"><?= htmlspecialchars(substr($row['deskripsi'], 0, 150)) ?>...</p>
                        </div>
                        <div class="job-card-footer">
                            <span class="job-location"><i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($row['lokasi']) ?></span>
                            <span class="job-salary"><i class="fas fa-money-bill-wave"></i>
                                Rp<?= number_format($row['gaji_min'], 0, ',', '.') ?> -
                                Rp<?= number_format($row['gaji_max'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1 / -1; text-align: center; padding: 40px;">Tidak ada lowongan ditemukan. Coba dengan
                    kata kunci lain.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta">
        <h2>Siap untuk memulai perjalanan karir Anda?</h2>
        <p>Daftar sekarang dan dapatkan akses ke ribuan lowongan kerja terbaik di Indonesia.</p>
        <div class="cta-buttons">
            <a href="register_user.php" class="btn btn-primary">Daftar Sekarang</a>
        </div>
    </section>

    <!-- Footer -->
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