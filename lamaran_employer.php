<?php
session_start();

if (!isset($_SESSION['employer_id'])) {
    header("Location: login_employer.php");
    exit;
}

include 'koneksi.php';

$employerId = $_SESSION['employer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['lamaran_id'])) {
    $lamaranId = (int) $_POST['lamaran_id'];
    $action = $_POST['action'] === 'terima' ? 'diterima' : 'ditolak';
    $updateSql = "UPDATE lamaran SET status = '$action' WHERE id = $lamaranId";
    mysqli_query($conn, $updateSql);
    // Optional: redirect supaya tidak resubmit saat refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Ambil data employer
$resultEmployer = mysqli_query($conn, "SELECT id, nama_perusahaan, email FROM employers WHERE id = $employerId");
$employer = mysqli_fetch_assoc($resultEmployer);

if (!$employer) {
    session_destroy();
    header("Location: login_employer.php");
    exit;
}

// Query untuk mendapatkan lamaran yang diterima
$sql = "SELECT 
            l.id AS lamaran_id,
            l.tanggal_lamaran as tanggal_lamar,
            p.id AS pelamar_id,
            l.nama as nama_pelamar,
            l.email as email_pelamar,
            l.telp as telepon,
            l.status as status,
            l.cv_path,
            low.id AS lowongan_id,
            low.judul AS judul_lowongan,
            low.perusahaan,
            low.lokasi
        FROM lamaran l
        JOIN users p ON l.user_id = p.id
        JOIN lowongan low ON l.lowongan_id = low.id
        WHERE low.employer_id = $employerId
        ORDER BY l.tanggal_lamaran DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamaran Diterima - Job Portal Indonesia</title>
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
<!-- Navbar -->
    <div class="navbar-container">
        <nav class="navbar">
            <a href="dashboard_employer.php" class="logo">
                <img src="assets/logo website/jobseeker.png" alt="JobSeeker Logo">
            </a>
            <div class="nav-right">
                <a href="dashboard_employer.php" class="nav-item">Dashboard</a>
                <a href="lowongan_employer.php" class="nav-item">Daftar Lowongan</a>
                <a href="tambah_lowongan.php" class="nav-item">Tambah Lowongan</a>
                <a href="lamaran_employer.php" class="nav-item active">Lamaran Diterima</a>
                <a href="profile_employer.php" class="nav-item">Profil</a>
                <a href="logout.php" class="nav-item">Logout</a>
            </div>
        </nav>
    </div>

    <div class="container">
        <h1>Lamaran yang Diterima</h1>
        <p>Berikut adalah daftar lamaran kerja yang telah Anda terima.</p>

        <div class="lamaran-list">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="lamaran-card">
                        <div class="lamaran-header">
                            <div class="lamaran-title"><?= htmlspecialchars($row['nama_pelamar']) ?></div>
                            <div class="lamaran-status"><?= htmlspecialchars($row['status']) ?></div>
                        </div>

                        <div class="lamaran-detail">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?= htmlspecialchars($row['email_pelamar']) ?></div>
                        </div>

                        <div class="lamaran-detail">
                            <div class="detail-label">Telepon</div>
                            <div class="detail-value"><?= htmlspecialchars($row['telepon']) ?></div>
                        </div>

                        <div class="lamaran-detail">
                            <div class="detail-label">Tanggal Lamar</div>
                            <div class="detail-value"><?= date('d F Y', strtotime($row['tanggal_lamar'])) ?></div>
                        </div>

                        <div class="lowongan-info">
                            <div class="lowongan-title">Lowongan: <?= htmlspecialchars($row['judul_lowongan']) ?></div>
                            <div class="lowongan-meta">
                                <?= htmlspecialchars($row['perusahaan']) ?> - <?= htmlspecialchars($row['lokasi']) ?>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="<?= htmlspecialchars($row['cv_path']) ?>" class="btn btn-primary" download>Unduh CV</a>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="lamaran_id" value="<?= $row['lamaran_id'] ?>">
                                <input type="hidden" name="action" value="terima">
                                <button type="submit" class="btn btn-success">Terima</button>
                            </form>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="lamaran_id" value="<?= $row['lamaran_id'] ?>">
                                <input type="hidden" name="action" value="tolak">
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </form>

                            <a href="lamaran_detail.php?id=<?= $row['lamaran_id'] ?>" class="btn btn-secondary">Lihat Detail</a>
                        </div>


                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <img src="assets/background/empty.png" alt="Tidak ada data">
                    <h3>Belum ada lamaran yang diterima</h3>
                    <p>Anda belum menerima lamaran kerja untuk lowongan yang Anda buka.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

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

<?php mysqli_close($conn); ?>