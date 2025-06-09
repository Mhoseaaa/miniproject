<?php
include 'koneksi.php';

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

if (!isset($_GET['id'])) {
    echo "ID lowongan tidak ditemukan.";
    exit;
}

$id = $_GET['id'];
$query = $conn->prepare("SELECT * FROM lowongan2 WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$lowongan = $result->fetch_assoc();

if (!$lowongan) {
    echo "Lowongan tidak ditemukan.";
    exit;
}

// Proses update jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul = $_POST['judul'];
    $perusahaan = $_POST['perusahaan'];
    $kategori = $_POST['kategori'];
    $lokasi = $_POST['lokasi'];
    $gaji_min = (int) str_replace('.', '', $_POST['gaji_min']);
    $gaji_max = (int) str_replace('.', '', $_POST['gaji_max']);
    $tipe = $_POST['tipe'];
    $deskripsi = $_POST['deskripsi'];
    $kualifikasi = $_POST['kualifikasi'];
    $batas_lamaran = $_POST['batas_lamaran'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul), '-'));
    $gaji = formatRupiah($gaji_min) . " - " . formatRupiah($gaji_max) . " per month";

    // Cek apakah user upload logo baru
    if ($_FILES['logo']['error'] === 0) {
        $upload_folder_rel = "assets/logo_perusahaan/";
        $upload_folder_abs = __DIR__ . '/' . $upload_folder_rel;

        if (!is_dir($upload_folder_abs)) {
            mkdir($upload_folder_abs, 0777, true);
        }

        $logo_file = basename($_FILES["logo"]["name"]);
        $logo_name = time() . "-" . preg_replace('/\s+/', '-', $logo_file);
        $logo_path_rel = $upload_folder_rel . $logo_name;
        $logo_path_abs = $upload_folder_abs . $logo_name;

        move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_path_abs);
    } else {
        $logo_path_rel = $lowongan['logo']; // pakai logo lama
    }

    $stmt = $conn->prepare("UPDATE lowongan SET judul=?, perusahaan=?, kategori=?, lokasi=?, gaji=?, tipe=?, deskripsi=?, kualifikasi=?, batas_lamaran=?, slug=?, logo=? WHERE id=?");
    $stmt->bind_param("sssssssssssi", $judul, $perusahaan, $kategori, $lokasi, $gaji, $tipe, $deskripsi, $kualifikasi, $batas_lamaran, $slug, $logo_path_rel, $id);
    $stmt->execute();

    echo "<p style='color:green;'>Lowongan berhasil diperbarui.</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Employer - Job Portal Indonesia</title>
    <link rel="stylesheet" href="styles/logreg_employer.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="styles/index.css?v=<?= time(); ?>">
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
        <a href="index.php" class="logo">
            <img src="assets/logo website/jobseeker.png" alt="Logo Web" />
        </a>
        <div class="nav-right">
            <a href="register_employer.php"><button class="outline-button">Daftar</button></a>
            <ul class="breadcrumb">
                <li><a href="index.php" class="nav-item">Beranda</a></li>
                <li><span>/</span></li>
                <li><a href="login_employer.php" class="nav-item active">Masuk Employer</a></li>
            </ul>
        </div>
    </nav>
</div>

<!-- Login Form for Employers -->
<div class="register-wrapper">
    <div class="jobseeker-link-container">
    </div>
    
    <div class="register-container">
        <h1 class="register-title">Tambah Lowongan Pekerjaan</h1>
        
        <?php if(isset($error)): ?>
            <div class="error-message" style="text-align: center; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
<form method="POST" enctype="multipart/form-data" onsubmit="prepareGaji()">
    <input type="hidden" name="id" value="<?= $lowongan['id'] ?>">
    <div class="form-group">
        <label>Judul:</label><br>
        <input type="text" name="judul" value="<?= $lowongan['judul'] ?>" required>
    </div>

    <div class="form-group">
        <label>Perusahaan:</label><br>
        <input type="text" name="perusahaan" value="<?= $lowongan['perusahaan'] ?>" required>
    </div>

    <div class="form-group">
        <label>Kategori:</label><br>
        <select name="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php
            $kategoriList = ["IT", "Desain", "Ritel", "Food & Beverage", "Pendidikan", "Kesehatan", "Keuangan", "Marketing", "Teknik", "Manufaktur", "Transportasi", "Administrasi", "Hukum"];
            foreach ($kategoriList as $kategori) {
                $selected = $lowongan['kategori'] == $kategori ? 'selected' : '';
                echo "<option value='$kategori' $selected>$kategori</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label>Lokasi:</label><br>
        <input type="text" name="lokasi" value="<?= $lowongan['lokasi'] ?>" required>
    </div>

    <div class="form-group">
        <label>Gaji Minimum:</label><br>
        <input type="text" id="gaji_min" name="gaji_min" value="<?= number_format($lowongan['gaji_min'], 0, ',', '.') ?>" required>
    </div>

    <div class="form-group">
        <label>Gaji Maksimum:</label><br>
        <input type="text" id="gaji_max" name="gaji_max" value="<?= number_format($lowongan['gaji_max'], 0, ',', '.') ?>" required>
    </div>

    <div class="form-group">
        <label>Tipe:</label><br>
        <select name="tipe" required>
            <?php
            $tipeList = ["Full-time", "Part-time", "Remote", "Freelance"];
            foreach ($tipeList as $tipe) {
                $selected = $lowongan['tipe'] == $tipe ? 'selected' : '';
                echo "<option value='$tipe' $selected>$tipe</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label>Deskripsi Pekerjaan:</label><br>
        <textarea name="deskripsi" rows="5" cols="50" required><?= $lowongan['deskripsi'] ?></textarea>
    </div>

    <div class="form-group">
        <label>Kualifikasi:</label><br>
        <textarea name="kualifikasi" rows="5" cols="50" required><?= $lowongan['kualifikasi'] ?></textarea>
    </div>

    <div class="form-group">
        <label>Batas Lamaran:</label><br>
        <input type="date" name="batas_lamaran" value="<?= $lowongan['batas_lamaran'] ?>" required>
    </div>

    <div class="form-group">
        <label>Upload Logo:</label><br>
        <input type="file" name="logo" accept="image/*">
        <br>
        <?php if ($lowongan['logo']) : ?>
            <img src="uploads/<?= $lowongan['logo'] ?>" alt="Logo" width="100">
            <input type="hidden" name="logo_lama" value="<?= $lowongan['logo'] ?>">
        <?php endif; ?>
    </div>

    <button type="submit" name="update" class="register-button">Update</button>
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