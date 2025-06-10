<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login_user.php");
    exit;
}

include 'koneksi.php';

$userEmail = $_SESSION['user_email'];

// Gunakan prepared statement untuk keamanan
$stmt = $conn->prepare("SELECT name, email FROM users WHERE email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$resultUser = $stmt->get_result();

if ($resultUser->num_rows === 0) {
    session_destroy();
    header("Location: login_user.php");
    exit;
}

$user = $resultUser->fetch_assoc();
$stmt->close();

// Ambil parameter filter dengan sanitasi
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$lokasi = isset($_GET['lokasi']) ? trim($_GET['lokasi']) : '';

// Query dengan prepared statement untuk keamanan
$sql = "SELECT * FROM lowongan WHERE 1=1";
$params = [];
$types = '';

if (!empty($keyword)) {
    $sql .= " AND (judul LIKE CONCAT('%', ?, '%') OR perusahaan LIKE CONCAT('%', ?, '%'))";
    $params[] = $keyword;
    $params[] = $keyword;
    $types .= 'ss';
}

if (!empty($kategori)) {
    $sql .= " AND kategori = ?";
    $params[] = $kategori;
    $types .= 's';
}

if (!empty($lokasi)) {
    $sql .= " AND lokasi LIKE CONCAT('%', ?, '%')";
    $params[] = $lokasi;
    $types .= 's';
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal Indonesia</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <!-- Navbar -->
    <div class="navbar-container">
        <nav class="navbar">
            <a href="dashboard_user.php" class="logo">
                <img src="assets/logo website/jobseeker.png" alt="JobSeeker Logo">
            </a>
            <div class="nav-right">
                <a href="dashboard_user.php" class="nav-item active">Dashboard</a>
                <a href="lowongan_user.php" class="nav-item">Daftar Lowongan</a>
                <a href="lamaran_user.php" class="nav-item">Lamaran Saya</a>
                <a href="profile_employer.php" class="nav-item">Profil</a>
                <a href="logout.php" class="nav-item">Logout</a>
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

    <!-- Main Content -->
    <div class="container">
        <div class="main-container">
            <!-- Job List Section -->
            <div class="job-list-container">
                <div class="job-list-header">
                    <h2>Lowongan Tersedia</h2>
                    <span class="job-count"><?= $result->num_rows ?> Lowongan</span>
                </div>

                <div class="job-list">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="job-card" data-id="<?= $row['id'] ?>" onclick="loadJobDetail(<?= $row['id'] ?>)">
                                <div class="job-card-header">
                                    <img src="<?= htmlspecialchars($row['logo'] ?? 'assets/default-logo.png') ?>"
                                        alt="<?= htmlspecialchars($row['perusahaan']) ?>" class="job-logo">
                                    <div>
                                        <h3 class="job-title"><?= htmlspecialchars($row['judul']) ?></h3>
                                        <p class="job-company"><?= htmlspecialchars($row['perusahaan']) ?></p>
                                    </div>
                                </div>

                                <div class="job-meta">
                                    <div class="job-meta-item">
                                        <i class="fas fa-briefcase"></i>
                                        <span><?= htmlspecialchars($row['kategori']) ?></span>
                                    </div>
                                    <div class="job-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= htmlspecialchars($row['lokasi']) ?></span>
                                    </div>
                                    <div class="job-meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span><?= htmlspecialchars($row['tipe']) ?></span>
                                    </div>
                                </div>

                                <p class="job-description"><?= htmlspecialchars(substr($row['deskripsi'], 0, 150)) ?>...</p>

                                <div class="job-footer">
                                    <span class="job-salary">Rp<?= number_format($row['gaji_min'], 0, ',', '.') ?> -
                                        Rp<?= number_format($row['gaji_max'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 40px;">
                            <i class="fas fa-search" style="font-size: 50px; color: #ddd; margin-bottom: 15px;"></i>
                            <h3>Tidak ada lowongan ditemukan</h3>
                            <p>Coba ubah kriteria pencarian Anda</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Job Detail Section -->
            <div class="job-detail-container" id="job-detail">
                <div class="job-detail-placeholder">
                    <i class="fas fa-search"></i>
                    <h3>Pilih lowongan kerja</h3>
                    <p>Detail lowongan akan ditampilkan di sini setelah Anda memilih dari daftar</p>
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

<script>
    // Fungsi untuk memuat detail pekerjaan
    function loadJobDetail(jobId) {
        // Highlight job card yang dipilih
        document.querySelectorAll('.job-card').forEach(card => {
            card.classList.remove('active');
            if (card.getAttribute('data-id') == jobId) {
                card.classList.add('active');
            }
        });

        // Load job detail via AJAX
        fetch(`detail_ajax.php?id=${jobId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('job-detail').innerHTML = html;

                // Scroll ke detail jika di mobile
                if (window.innerWidth <= 1024) {
                    document.getElementById('job-detail').scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            })
            .catch(error => {
                console.error('Error loading job detail:', error);
                document.getElementById('job-detail').innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 50px; color: #ff6b6b; margin-bottom: 15px;"></i>
                    <h3>Gagal memuat detail lowongan</h3>
                    <p>Silakan coba lagi nanti</p>
                </div>
            `;
            });
    }

    // Jika ada parameter ID di URL, load job detail tersebut
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const jobId = urlParams.get('job_id');

        if (jobId) {
            loadJobDetail(jobId);
        }
    });
</script>

<?php
$stmt->close();
$conn->close();
?>