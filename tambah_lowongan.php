<?php
// === Koneksi ke Database ===
include 'koneksi.php';
session_start();

// === Fungsi Format Rupiah ===
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// === Proses Submit Form ===
$success = $error = '';

// Pastikan employer sudah login
if (!isset($_SESSION['employer_id'])) {
    header("Location: login_employer.php");
    exit();
}

$employer_id = $_SESSION['employer_id'];
$employer_id_safe = intval($employer_id);

// Ambil data employer termasuk logo dari database
$sql = "SELECT nama_perusahaan, logo FROM employers WHERE id = $employer_id_safe";
$resultEmployer = mysqli_query($conn, $sql);
$employer = mysqli_fetch_assoc($resultEmployer);

if (!$employer) {
    session_destroy();
    header("Location: login_employer.php");
    exit();
}

$nama_perusahaan = htmlspecialchars($employer['nama_perusahaan']);
$company_logo = $employer['logo']; // Ambil path logo dari tabel employers

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul         = mysqli_real_escape_string($conn, $_POST['judul'] ?? '');
    $perusahaan    = mysqli_real_escape_string($conn, $_POST['perusahaan'] ?? '');
    $kategori      = mysqli_real_escape_string($conn, $_POST['kategori'] ?? '');
    $lokasi        = mysqli_real_escape_string($conn, $_POST['lokasi'] ?? '');
    $gaji_min      = (int) str_replace('.', '', $_POST['gaji_min'] ?? '0');
    $gaji_max      = (int) str_replace('.', '', $_POST['gaji_max'] ?? '0');
    $tipe          = mysqli_real_escape_string($conn, $_POST['tipe'] ?? '');
    $deskripsi     = mysqli_real_escape_string($conn, $_POST['deskripsi'] ?? '');
    $kualifikasi   = mysqli_real_escape_string($conn, $_POST['kualifikasi'] ?? '');
    $batas_lamaran = mysqli_real_escape_string($conn, $_POST['batas_lamaran'] ?? null);

    $gaji_display  = formatRupiah($gaji_min) . " - " . formatRupiah($gaji_max) . " per month";

    // Gunakan logo perusahaan yang sudah ada
    $logo_path_rel = $company_logo;

    $sql_insert = "INSERT INTO lowongan 
        (employer_id, judul, perusahaan, kategori, lokasi, gaji_min, gaji_max, gaji_display, tipe, logo, deskripsi, kualifikasi, batas_lamaran) 
        VALUES (
            '$employer_id_safe', 
            '$judul', 
            '$perusahaan', 
            '$kategori', 
            '$lokasi', 
            '$gaji_min', 
            '$gaji_max', 
            '$gaji_display', 
            '$tipe', 
            '$logo_path_rel', 
            '$deskripsi', 
            '$kualifikasi', 
            '$batas_lamaran')";

    if (mysqli_query($conn, $sql_insert)) {
        $success = "Lowongan berhasil ditambahkan.";
        // Redirect atau tampilkan pesan sukses
        header("Location: dashboard_employer.php?success=1");
        exit();
    } else {
        $error = "Gagal menambahkan lowongan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Employer - Job Portal Indonesia</title>
    <link rel="stylesheet" href="navbar.css">
    <style>

        .register-wrapper {
            max-width: 550px;
            margin: 80px auto 0;
        }
        
        .jobseeker-link-container {
            text-align: right;
            margin-bottom: 10px;
        }
        
        .jobseeker-link {
            color: #001f54;
            font-weight: bold;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s;
            display: inline-block;
            margin-right:-50px;
        }

        .jobseeker-link:hover {
            text-decoration: underline;
        }

        .register-container {
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        
        .register-title {
            color: #001f54;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #001f54;
            outline: none;
        }
        
        .error-message {
            color: #ff007f;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .register-button {
            width: 100%;
            padding: 14px;
            background-color: #001f54;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .register-button:hover {
            background-color: #000e27;
        }
        
        .register-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .register-footer a {
            color: #001f54;
            text-decoration: none;
            font-weight: bold;
        }
        
        .register-footer a:hover {
            text-decoration: underline;
        }

        .outline-button {
            background: white;
            color: #001f54;
            border: 2px solid #001f54;
            padding: 8px 16px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .outline-button:hover {
            background: #001f54;
            color: white;
        }

        @media (max-width: 768px) {
            .jobseeker-link-container {
                text-align: center;
                margin-bottom: 20px;
            }
            
            .register-wrapper {
                margin-top: 40px;
                max-width: 90%;
            }
        }

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

    </style>
    <script>
        // Format input rupiah otomatis
        function formatRupiahInput(el) {
            el.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                value = new Intl.NumberFormat('id-ID').format(value);
                this.value = value;
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            formatRupiahInput(document.getElementById('gaji_min'));
            formatRupiahInput(document.getElementById('gaji_max'));
        });
    </script>
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
                <a href="tambah_lowongan.php" class="nav-item active">Tambah Lowongan</a>
                <a href="lamaran_employer.php" class="nav-item">Lamaran Diterima</a>
                <a href="profile_employer.php" class="nav-item">Profil</a>
                <a href="logout.php" class="nav-item">Logout</a>
            </div>
        </nav>
    </div>

<!-- Login Form for Employers -->
<div class="register-wrapper">
    <div class="jobseeker-link-container">
    </div>
    
    <div class="register-container">
        <h1 class="register-title">Tambah Lowongan Pekerjaan</h1>

        <?php if (!empty($company_logo)): ?>
            <div class="form-group">
                <label>Logo Perusahaan Saat Ini:</label><br>
                <img src="<?= htmlspecialchars($company_logo) ?>" alt="Logo Perusahaan" style="max-height: 100px;">
            </div>
            <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="error-message" style="text-align: center; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" onsubmit="prepareGaji()">
            <div class="form-group">
                <label>Judul Pekerjaan:</label><br>
                <input type="text" name="judul" required>
            </div>

            <div class="form-group">
                <label>Perusahaan:</label><br>
                <input type="text" name="perusahaan" value="<?= $nama_perusahaan ?>" readonly required>
            </div>
            
            <div class="form-group">
                <label>Kategori:</label><br>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="IT">IT</option>
                    <option value="Desain">Desain</option>
                    <option value="Ritel">Ritel</option>
                    <option value="Food & Beverage">Food & Beverage</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Kesehatan">Kesehatan</option>
                    <option value="Keuangan">Keuangan</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Teknik">Teknik</option>
                    <option value="Manufaktur">Manufaktur</option>
                    <option value="Transportasi">Transportasi</option>
                    <option value="Administrasi">Administrasi</option>
                    <option value="Hukum">Hukum</option>
                </select>
            </div>

            <div class="form-group">
                <label>Lokasi:</label><br>
                <input type="text" name="lokasi" required>
            </div>

            <div class="form-group"> 
                <label>Gaji Minimum:</label><br>
                <input type="text" id="gaji_min" name="gaji_min" placeholder="Contoh: 1.700.000" required>
            </div>

            <div class="form-group">
                <label>Gaji Maksimum:</label><br>
                <input type="text" id="gaji_max" name="gaji_max" placeholder="Contoh: 3.000.000" required>
            </div>

            <div class="form-group">
                <label>Tipe:</label><br>
                <select name="tipe" required>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Remote">Remote</option>
                    <option value="Freelance">Freelance</option>
                </select>
            </div>

            <div class="form-group">
                <label>Deskripsi Pekerjaan:</label><br>
                <textarea name="deskripsi" rows="5" cols="50" required></textarea>
            </div>

            <div class="form-group">
                <label>Kualifikasi:</label><br>
                <textarea name="kualifikasi" rows="5" cols="50" required></textarea>
            </div>

            <div class="form-group">
                <label>Batas Lamaran:</label><br>
                <input type="date" name="batas_lamaran" required>
            </div>

           


            
            <button type="submit" class="register-button">Upload</button>

            

        </form>

        <script>
                function prepareGaji() {
                    const min = document.getElementById('gaji_min');
                    const max = document.getElementById('gaji_max');
                    min.value = min.value.replace(/\./g, '');
                    max.value = max.value.replace(/\./g, '');
                }
        </script>
        
        <div class="register-footer">
            
        </div>
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