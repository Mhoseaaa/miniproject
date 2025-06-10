<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: login_user.php");
    exit;
}

$userId = $_SESSION['user_email'];
$lamaranId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "SELECT l.*, low.judul AS judul_lowongan, low.perusahaan, low.lokasi 
        FROM lamaran l
        JOIN lowongan low ON l.lowongan_id = low.id
        WHERE l.id = $lamaranId";

$result = mysqli_query($conn, $sql);
$lamaran = mysqli_fetch_assoc($result);

if (!$lamaran) {
    echo "<p style='text-align:center;'>Data lamaran tidak ditemukan.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lamaran</title>
    <link rel="stylesheet" href="navbar.css">
    <style>
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .main-container {
            display: flex;
            min-height: 90vh;
            padding: 20px;
        }

        .lamaran-list {
            width: 100%;
            overflow-y: auto;
            padding: 20px;
        }

        .lamaran-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #4CAF50;
        }

        .lamaran-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .lamaran-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .lamaran-status {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .lamaran-detail {
            display: flex;
            margin-bottom: 10px;
        }

        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }

        .detail-value {
            flex: 1;
        }

        .action-buttons {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
            color: white;
        }

        .btn-primary {
            background-color: #001f54;
        }

        .btn-primary:hover {
            background-color: #000b1d;
        }

        .btn-success {
            background-color: #4CAF50;
        }

        .btn-success:hover {
            background-color: #388E3C;
        }

        .btn-danger {
            background-color: #e53935;
        }

        .btn-danger:hover {
            background-color: #c62828;
        }

        .btn-secondary {
            background-color: #9e9e9e;
        }

        .btn-secondary:hover {
            background-color: #757575;
        }


        .lowongan-info {
            background-color: #f5f6f8;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .lowongan-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .lowongan-meta {
            font-size: 14px;
            color: #666;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .empty-state img {
            width: 150px;
            margin-bottom: 20px;
        }

        /* Footer */
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

        .btn-danger {
            background-color: #e53935;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c62828;
        }
    </style>
</head>

<body>
    <div class="navbar-container">
        <nav class="navbar">
            <a href="dashboard_user.php" class="logo">
                <img src="assets/logo website/jobseeker.png" alt="JobSeeker Logo">
            </a>
            <div class="nav-right">
                <a href="dashboard_user.php" class="nav-item">Dashboard</a>
                <a href="lowongan_user.php" class="nav-item">Daftar Lowongan</a>
                <a href="lamaran_user.php" class="nav-item active">Lamaran Saya</a>
                <a href="profile_user.php" class="nav-item">Profil</a>
                <a href="logout.php" class="nav-item">Logout</a>
            </div>
        </nav>
    </div>

    <div class="container">
        <h1>Detail Lamaran</h1>

        <div class="lamaran-card">
            <div class="lamaran-header">
                <div class="lamaran-title"><?= htmlspecialchars($lamaran['nama']) ?></div>
                <div class="lamaran-status">Status: <?= htmlspecialchars($lamaran['status']) ?></div>
            </div>

            <div class="lamaran-detail">
                <div class="detail-label">Email</div>
                <div class="detail-value"><?= htmlspecialchars($lamaran['email']) ?></div>
            </div>
            <div class="lamaran-detail">
                <div class="detail-label">Telepon</div>
                <div class="detail-value"><?= htmlspecialchars($lamaran['telp']) ?></div>
            </div>
            <div class="lamaran-detail">
                <div class="detail-label">Alamat</div>
                <div class="detail-value"><?= htmlspecialchars($lamaran['alamat']) ?></div>
            </div>
            <div class="lamaran-detail">
                <div class="detail-label">Pendidikan</div>
                <div class="detail-value"><?= htmlspecialchars($lamaran['pendidikan']) ?></div>
            </div>
            <div class="lamaran-detail">
                <div class="detail-label">Institusi</div>
                <div class="detail-value"><?= htmlspecialchars($lamaran['institusi']) ?></div>
            </div>
            <div class="lamaran-detail">
                <div class="detail-label">Jurusan</div>
                <div class="detail-value"><?= htmlspecialchars($lamaran['jurusan']) ?></div>
            </div>
            <div class="lamaran-detail">
                <div class="detail-label">Pengalaman</div>
                <div class="detail-value"><?= htmlspecialchars($lamaran['pengalaman']) ?> tahun</div>
            </div>
            <div class="lamaran-detail">
                <div class="detail-label">Keterampilan</div>
                <div class="detail-value"><?= nl2br(htmlspecialchars($lamaran['keterampilan'])) ?></div>
            </div>

            <div class="lamaran-detail">
                <div class="detail-label">Lowongan</div>
                <div class="detail-value"><?= htmlspecialchars($lamaran['judul_lowongan']) ?> -
                    <?= htmlspecialchars($lamaran['perusahaan']) ?> (<?= htmlspecialchars($lamaran['lokasi']) ?>)
                </div>
            </div>

            <div class="action-buttons">
                <?php if (!empty($lamaran['cv_path'])): ?>
                    <a href="<?= htmlspecialchars($lamaran['cv_path']) ?>" class="btn btn-primary" download>Unduh CV</a>
                <?php endif; ?>
                <?php if (!empty($lamaran['ijazah_path'])): ?>
                    <a href="<?= htmlspecialchars($lamaran['ijazah_path']) ?>" class="btn btn-primary" download>Unduh
                        Ijazah</a>
                <?php endif; ?>
                <?php if (!empty($lamaran['transkrip_path'])): ?>
                    <a href="<?= htmlspecialchars($lamaran['transkrip_path']) ?>" class="btn btn-primary" download>Unduh
                        Transkrip</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>JobSeeker</h3>
                <p>Memudahkan pencarian kerja untuk masa depan yang lebih baik</p>
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
<?php mysqli_close($conn); ?>