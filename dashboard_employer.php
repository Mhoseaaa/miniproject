<?php
// dashboard_employer.php
include 'koneksi.php';
session_start();

// Pastikan employer sudah login
if (!isset($_SESSION['employer_id'])) {
    header("Location: login_employer.php");
    exit();
}

$employer_id = $_SESSION['employer_id'];
$employer_id_safe = intval($employer_id);

// Ambil data employer
$sql_employer = "SELECT nama_perusahaan, logo FROM employers WHERE id = $employer_id_safe";
$result_employer = mysqli_query($conn, $sql_employer);
$employer = mysqli_fetch_assoc($result_employer);

if (!$employer) {
    session_destroy();
    header("Location: login_employer.php");
    exit();
}

// Hitung jumlah lowongan
$sql_job_count = "SELECT COUNT(*) as total FROM lowongan WHERE employer_id = $employer_id_safe";
$result_job_count = mysqli_query($conn, $sql_job_count);
$job_count = mysqli_fetch_assoc($result_job_count)['total'];

// Hitung jumlah lamaran masuk
$sql_application_count = "SELECT COUNT(*) as total FROM lamaran l 
                          JOIN lowongan lo ON l.lowongan_id = lo.id 
                          WHERE lo.employer_id = $employer_id_safe";
$result_application_count = mysqli_query($conn, $sql_application_count);
$application_count = mysqli_fetch_assoc($result_application_count)['total'];

// Ambil daftar lowongan terbaru
$sql_jobs = "SELECT * FROM lowongan WHERE employer_id = $employer_id_safe ORDER BY created_at DESC LIMIT 5";
$result_jobs = mysqli_query($conn, $sql_jobs);
$jobs = mysqli_fetch_all($result_jobs, MYSQLI_ASSOC);

// Ambil daftar lamaran terbaru
$sql_applications = "SELECT l.*, lo.judul as job_title FROM lamaran l
                     JOIN lowongan lo ON l.lowongan_id = lo.id
                     WHERE lo.employer_id = $employer_id_safe";
$result_applications = mysqli_query($conn, $sql_applications);
$applications = mysqli_fetch_all($result_applications, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="navbar.css">
    <title>Dashboard Employer - JobSeeker</title>
    <style>
        /* Main Content */
        .dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .welcome-section {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .welcome-text h1 {
            color: var(--primary);
            margin: 0;
        }
        
        .welcome-text p {
            color: var(--dark);
            margin: 5px 0 0;
        }
        
        .company-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 20px;
            border: 2px solid var(--primary);
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card h3 {
            color: var(--dark);
            margin-top: 0;
            font-size: 16px;
        }
        
        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: var(--primary);
            margin: 10px 0;
        }
        
        .stat-card .view-all {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 10px;
        }
        
        /* Recent Tables */
        .recent-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 768px) {
            .recent-container {
                grid-template-columns: 1fr;
            }
        }
        
        .recent-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .recent-card h2 {
            color: var(--primary);
            margin-top: 0;
            font-size: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: #f8f9fa;
            color: var(--dark);
            font-weight: 500;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .action-link {
            color: var(--secondary);
            text-decoration: none;
            margin-right: 10px;
        }
        
        .action-link:hover {
            text-decoration: underline;
        }
        
        /* Footer */
        .footer {
            background-color: var(--primary);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            padding: 0 20px;
        }
        
        .footer-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
        }
        
        .footer-section ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-section ul li {
            margin-bottom: 10px;
        }
        
        .footer-section ul li a {
            color: white;
            text-decoration: none;
        }
        
        .footer-section ul li a:hover {
            text-decoration: underline;
        }
        
        .footer-copy {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        /* Button */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #00123b;
        }
        
        .btn-secondary {
            background-color: var(--secondary);
        }
        
        .btn-secondary:hover {
            background-color: #d8006a;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-container">
        <nav class="navbar">
            <a href="employer/dashboard_employer.php" class="logo">
                <img src="assets/logo website/jobseeker.png" alt="JobSeeker Logo">
            </a>
            <div class="nav-right">
                <a href="employer/dashboard_employer.php" class="nav-item active">Dashboard</a>
                <a href="lowongan_employer.php" class="nav-item">Daftar Lowongan</a>
                <a href="tambah_lowongan.php" class="nav-item">Tambah Lowongan</a>
                <a href="lamaran_employer.php" class="nav-item">Lamaran Diterima</a>
                <a href="profile_employer.php" class="nav-item">Profil</a>
                <a href="logout.php" class="nav-item">Logout</a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-text">
                <h1>Selamat Datang, <?= htmlspecialchars($employer['nama_perusahaan']) ?></h1>
                <p>Kelola lowongan pekerjaan dan lamaran yang masuk</p>
            </div>
            <?php if (!empty($employer['logo'])): ?>
                <img src="<?= htmlspecialchars($employer['logo']) ?>" alt="Company Logo" class="company-logo">
            <?php endif; ?>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Lowongan</h3>
                <div class="number"><?= $job_count ?></div>
                <a href="lowongan_employer.php" class="view-all">Lihat Semua →</a>
            </div>
            
            <div class="stat-card">
                <h3>Lamaran Masuk</h3>
                <div class="number"><?= $application_count ?></div>
                <a href="lamaran_employer.php" class="view-all">Lihat Semua →</a>
            </div>
            
            <div class="stat-card">
                <h3>Lowongan Aktif</h3>
                <div class="number">
                    <?php 
                    $sql_active = "SELECT COUNT(*) as total FROM lowongan 
                                  WHERE employer_id = $employer_id_safe 
                                  AND batas_lamaran >= CURDATE()";
                    $result_active = mysqli_query($conn, $sql_active);
                    echo mysqli_fetch_assoc($result_active)['total'];
                    ?>
                </div>
                <a href="lowongan_aktif.php?filter=active" class="view-all">Lihat Semua →</a>
            </div>
            
            <div class="stat-card">
                <h3>Lowongan Ditutup</h3>
                <div class="number">
                    <?php 
                    $sql_closed = "SELECT COUNT(*) as total FROM lowongan 
                                  WHERE employer_id = $employer_id_safe 
                                  AND batas_lamaran < CURDATE()";
                    $result_closed = mysqli_query($conn, $sql_closed);
                    echo mysqli_fetch_assoc($result_closed)['total'];
                    ?>
                </div>
                <a href="lowongan_mati.php?filter=closed" class="view-all">Lihat Semua →</a>
            </div>
        </div>

        <!-- Recent Jobs and Applications -->
        <div class="recent-container">
            <!-- Recent Jobs -->
            <div class="recent-card">
                <h2>Lowongan Terbaru</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?= htmlspecialchars($job['judul']) ?></td>
                            <td><?= date('d M Y', strtotime($job['created_at'])) ?></td>
                            <td>
                                <a href="edit_lowongan.php?id=<?= $job['id'] ?>" class="action-link">Edit</a>
                                <a href="lowongan_employer.php?id=<?= $job['id'] ?>" class="action-link">Lihat</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="margin-top: 20px; text-align: center;">
                    <a href="tambah_lowongan.php" class="btn">Tambah Lowongan Baru</a>
                </div>
            </div>
            
            <!-- Recent Applications -->
            <div class="recent-card">
                <h2>Lamaran Terbaru</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Pelamar</th>
                            <th>Posisi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?= htmlspecialchars($app['nama']) ?></td>
                            <td><?= htmlspecialchars($app['job_title']) ?></td>
                            <td><?= date('d M Y', strtotime($app['tanggal_lamaran'])) ?></td>
                            <td>
                                <a href="lamaran_detail.php?id=<?= $app['id'] ?>" class="action-link">Lihat</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="margin-top: 20px; text-align: center;">
                    <a href="lamaran_employer.php" class="btn btn-secondary">Lihat Semua Lamaran</a>
                </div>
            </div>
        </div>
    </div>

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
</body>
</html>