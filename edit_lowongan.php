<?php
session_start();

if (!isset($_SESSION['employer_id'])) {
    header("Location: login_employer.php");
    exit;
}

include 'koneksi.php';

// Get job ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch employer data
$employerId = $_SESSION['employer_id'];
$resultEmployer = mysqli_query($conn, "SELECT id, nama_perusahaan, email FROM employers WHERE id = $employerId");
$employer = mysqli_fetch_assoc($resultEmployer);

if (!$employer) {
    session_destroy();
    header("Location: login_employer.php");
    exit;
}

// Fetch job data
$sql = "SELECT * FROM lowongan WHERE id = $id AND employer_id = $employerId";
$result = mysqli_query($conn, $sql);
$job = mysqli_fetch_assoc($result);

if (!$job) {
    $_SESSION['error'] = "Lowongan tidak ditemukan atau Anda tidak memiliki akses";
    header("Location: dashboard_employer.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $perusahaan = mysqli_real_escape_string($conn, $_POST['perusahaan']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $gaji_min = mysqli_real_escape_string($conn, $_POST['gaji_min']);
    $gaji_max = mysqli_real_escape_string($conn, $_POST['gaji_max']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $kualifikasi = mysqli_real_escape_string($conn, $_POST['kualifikasi']);
    $batas_lamaran = mysqli_real_escape_string($conn, $_POST['batas_lamaran']);
    
    // Handle logo upload
    $logo = $job['logo'];
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "uploads/logos/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid('logo_', true) . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
                // Delete old logo if exists
                if ($logo && file_exists($logo)) {
                    unlink($logo);
                }
                $logo = $target_file;
            }
        }
    }
    
    // Update job in database
    $update_sql = "UPDATE lowongan SET 
                  judul = '$judul',
                  perusahaan = '$perusahaan',
                  lokasi = '$lokasi',
                  kategori = '$kategori',
                  tipe = '$tipe',
                  gaji_min = '$gaji_min',
                  gaji_max = '$gaji_max',
                  deskripsi = '$deskripsi',
                  kualifikasi = '$kualifikasi',
                  batas_lamaran = '$batas_lamaran',
                  logo = '$logo'
                  WHERE id = $id AND employer_id = $employerId";
    
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['success'] = "Lowongan berhasil diperbarui";
        header("Location: dashboard_employer.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal memperbarui lowongan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan - JobSeeker</title>
    <link rel="stylesheet" href="styles/edit_job.css?v=<?= time(); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        /* NAVBAR */
        .navbar-container {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 15px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-item {
            position: relative;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            color: black;
            padding-bottom: 5px;
        }

        .nav-item::after {
            content: "";
            display: block;
            width: 100%;
            height: 2px;
            background-color: #001f54;
            position: absolute;
            left: 0;
            bottom: 0;
            transform: scaleX(0);
            transition: transform 0.3s ease-in-out;
        }

        .nav-item.active::after,
        .nav-item:hover::after {
            transform: scaleX(1);
        }

        .logo img {
            width: 150px !important;
            height: auto;
        }

        /* MAIN CONTENT */
        .register-wrapper {
            max-width: 800px;
            margin: 100px auto 50px;
            padding: 0 20px;
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
        
        .logo-preview {
            max-width: 150px;
            max-height: 150px;
            margin: 10px 0;
            display: block;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 5px;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 14px 20px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
            flex: 1;
        }
        
        .btn-primary {
            background-color: #001f54;
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #00308a;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 16px;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* FOOTER */
        .footer {
            background-color: #001f54;
            color: white;
            padding: 30px 20px;
            text-align: center;
            margin-top: 50px;
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
            margin-bottom: 15px;
            font-size: 18px;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin: 8px 0;
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
            margin-top: 30px;
            font-size: 14px;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .register-wrapper {
                margin-top: 80px;
                max-width: 95%;
                padding: 0 10px;
            }
            
            .register-container {
                padding: 25px;
            }
            
            .navbar {
                flex-direction: column;
                padding: 10px;
            }
            
            .nav-right {
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
            }
            
            .button-group {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-container">
        <nav class="navbar">
            <a href="dashboard_employer.php" class="logo">
                <img src="assets/logo website/jobseeker.png" alt="Logo Web" />
            </a>
            <div class="nav-right">
                <span>Halo, <?= htmlspecialchars($employer['nama_perusahaan']) ?></span> |
                <a href="dashboard_employer.php" class="nav-item">Beranda</a> |
                <a href="profile_employer.php" class="nav-item">Profil</a> |
                <a href="tambah_lowongan.php" class="nav-item">Tambah Lowongan</a> |
                <a href="logout.php" class="nav-item">Logout</a>
            </div>
        </nav>
    </div>

    <div class="register-wrapper">
        <div class="register-container">
            <h1 class="register-title">Edit Lowongan Kerja</h1>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul">Judul Lowongan</label>
                    <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($job['judul']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="perusahaan">Nama Perusahaan</label>
                    <input type="text" id="perusahaan" name="perusahaan" value="<?= htmlspecialchars($job['perusahaan']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="logo">Logo Perusahaan</label>
                    <?php if ($job['logo']): ?>
                        <img src="<?= htmlspecialchars($job['logo']) ?>" alt="Logo Perusahaan" class="logo-preview">
                    <?php endif; ?>
                    <input type="file" id="logo" name="logo" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($job['lokasi']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select id="kategori" name="kategori" required>
                        <option value="IT" <?= $job['kategori'] == 'IT' ? 'selected' : '' ?>>IT</option>
                        <option value="Desain" <?= $job['kategori'] == 'Desain' ? 'selected' : '' ?>>Desain</option>
                        <option value="Ritel" <?= $job['kategori'] == 'Ritel' ? 'selected' : '' ?>>Ritel</option>
                        <option value="Food & Beverage" <?= $job['kategori'] == 'Food & Beverage' ? 'selected' : '' ?>>Food & Beverage</option>
                        <option value="Pendidikan" <?= $job['kategori'] == 'Pendidikan' ? 'selected' : '' ?>>Pendidikan</option>
                        <option value="Kesehatan" <?= $job['kategori'] == 'Kesehatan' ? 'selected' : '' ?>>Kesehatan</option>
                        <option value="Keuangan" <?= $job['kategori'] == 'Keuangan' ? 'selected' : '' ?>>Keuangan</option>
                        <option value="Marketing" <?= $job['kategori'] == 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                        <option value="Teknik" <?= $job['kategori'] == 'Teknik' ? 'selected' : '' ?>>Teknik</option>
                        <option value="Manufaktur" <?= $job['kategori'] == 'Manufaktur' ? 'selected' : '' ?>>Manufaktur</option>
                        <option value="Transportasi" <?= $job['kategori'] == 'Transportasi' ? 'selected' : '' ?>>Transportasi</option>
                        <option value="Administrasi" <?= $job['kategori'] == 'Administrasi' ? 'selected' : '' ?>>Administrasi</option>
                        <option value="Hukum" <?= $job['kategori'] == 'Hukum' ? 'selected' : '' ?>>Hukum</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tipe">Tipe Pekerjaan</label>
                    <select id="tipe" name="tipe" required>
                        <option value="Full-time" <?= $job['tipe'] == 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                        <option value="Part-time" <?= $job['tipe'] == 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                        <option value="Kontrak" <?= $job['tipe'] == 'Kontrak' ? 'selected' : '' ?>>Kontrak</option>
                        <option value="Freelance" <?= $job['tipe'] == 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                        <option value="Internship" <?= $job['tipe'] == 'Internship' ? 'selected' : '' ?>>Internship</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="gaji_min">Gaji Minimum (Rp)</label>
                    <input type="number" id="gaji_min" name="gaji_min" value="<?= htmlspecialchars($job['gaji_min']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="gaji_max">Gaji Maksimum (Rp)</label>
                    <input type="number" id="gaji_max" name="gaji_max" value="<?= htmlspecialchars($job['gaji_max']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Pekerjaan</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" required><?= htmlspecialchars($job['deskripsi']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="kualifikasi">Kualifikasi</label>
                    <textarea id="kualifikasi" name="kualifikasi" rows="5" required><?= htmlspecialchars($job['kualifikasi']) ?></textarea>
                </div>

                <div class="form-group">
                <label for="batas_lamaran">Batas Lamaran:</label>
                <input type="date" id="batas_lamaran" name="batas_lamaran" value="<?= htmlspecialchars($job['batas_lamaran']) ?>" required>
            </div>
                
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="delete_job.php?id=<?= $id ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">Hapus Lowongan</a>
                </div>
            </form>
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