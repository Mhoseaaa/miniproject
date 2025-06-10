<?php
session_start();

if (!isset($_SESSION['user_email']) || !isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

include 'koneksi.php';

$userId = $_SESSION['user_id'];

$sql = "SELECT 
            l.id AS lamaran_id,
            l.tanggal_lamaran,
            l.status,
            l.cv_path,
            low.judul AS judul_lowongan,
            low.perusahaan,
            low.lokasi
        FROM lamaran l
        JOIN lowongan low ON l.lowongan_id = low.id
        WHERE l.user_id = $userId
        ORDER BY l.tanggal_lamaran DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamaran Saya - Job Portal Indonesia</title>
    <link rel="stylesheet" href="navbar.css">
    <style>
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

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .lamaran-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #001f54;
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
            text-transform: capitalize;
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
            color: white;
            background-color: #001f54;
        }

        .btn:hover {
            background-color: #000b1d;
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
        <h1>Lamaran Saya</h1>
        <p>Berikut adalah daftar lamaran kerja yang telah Anda kirimkan.</p>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="lamaran-card">
                    <div class="lamaran-header">
                        <div class="lamaran-title">Lamaran ke: <?= htmlspecialchars($row['judul_lowongan']) ?></div>
                        <div class="lamaran-status"><?= htmlspecialchars($row['status']) ?></div>
                    </div>
                    <div class="lamaran-detail">
                        <div class="detail-label">Perusahaan</div>
                        <div class="detail-value"><?= htmlspecialchars($row['perusahaan']) ?></div>
                    </div>
                    <div class="lamaran-detail">
                        <div class="detail-label">Lokasi</div>
                        <div class="detail-value"><?= htmlspecialchars($row['lokasi']) ?></div>
                    </div>
                    <div class="lamaran-detail">
                        <div class="detail-label">Tanggal Lamar</div>
                        <div class="detail-value"><?= date('d F Y', strtotime($row['tanggal_lamaran'])) ?></div>
                    </div>
                    <div class="action-buttons">
                        <a href="<?= htmlspecialchars($row['cv_path']) ?>" class="btn" download>Unduh CV</a>
                        <a href="lamaran_detail_user.php?id=<?= $row['lamaran_id'] ?>" class="btn">Lihat Detail</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <img src="assets/background/empty.png" alt="Tidak ada data">
                <h3>Belum ada lamaran yang dikirim</h3>
                <p>Silakan lamar lowongan pekerjaan yang tersedia.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>JobSeeker</h3>
            <p>Memudahkan pencarian kerja untuk masa depan yang lebih baik</p>
        </div>
        <div class="footer-section">
            <h3>Perusahaan</h3>
            <ul>
                <li><a href="#">Tentang Kami</a></li>
                <li><a href="#">Karir</a></li>
                <li><a href="#">Blog</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Dukungan</h3>
            <ul>
                <li><a href="#">Pusat Bantuan</a></li>
                <li><a href="#">Kebijakan Privasi</a></li>
                <li><a href="#">Syarat & Ketentuan</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Kontak</h3>
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

</html>
<?php mysqli_close($conn); ?>