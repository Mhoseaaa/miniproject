<?php
session_start();
include 'koneksi.php';

$errors = [];

if (isset($_SESSION['employer_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_perusahaan = trim($_POST['nama_perusahaan'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $no_telepon = trim($_POST['no_telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $logo_path = null;

    // Validasi
    if (empty($nama_perusahaan)) {
        $errors['nama_perusahaan'] = "Nama perusahaan harus diisi";
    }

    if (empty($email)) {
        $errors['email'] = "Email harus diisi";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format email tidak valid";
    }

    if (empty($password)) {
        $errors['password'] = "Password harus diisi";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password minimal 6 karakter";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Password tidak cocok";
    }

    if (empty($no_telepon)) {
        $errors['no_telepon'] = "Nomor telepon harus diisi";
    }

    if (empty($alamat)) {
        $errors['alamat'] = "Alamat harus diisi";
    }

    // Cek apakah email sudah digunakan
    if (empty($errors)) {
        $sql = "SELECT id FROM employers WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query error: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            $errors['email'] = "Email sudah terdaftar";
        }
    }

    // Proses upload logo
    $upload_folder_rel = "assets/logo_perusahaan/";
    $upload_folder_abs = __DIR__ . '/' . $upload_folder_rel;

    if (!is_dir($upload_folder_abs)) {
        mkdir($upload_folder_abs, 0777, true);
    }

    if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES["logo"]["type"];
        
        if (in_array($file_type, $allowed_types)) {
            $logo_file = basename($_FILES["logo"]["name"]);
            $logo_name = time() . "-" . preg_replace('/\s+/', '-', $logo_file);
            $logo_path_rel = $upload_folder_rel . $logo_name;
            $logo_path_abs = $upload_folder_abs . $logo_name;

            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_path_abs)) {
                $logo_path = $logo_path_rel;
            } else {
                $errors['logo'] = "Gagal mengupload logo";
            }
        } else {
            $errors['logo'] = "Format file tidak didukung. Hanya JPEG, PNG, dan GIF yang diperbolehkan";
        }
    } else {
        // Logo tidak wajib diisi, jadi tidak perlu error jika tidak diupload
    }

    // Simpan ke database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO employers (nama_perusahaan, email, password, no_telepon, alamat, logo) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $nama_perusahaan, $email, $hashed_password, $no_telepon, $alamat, $logo_path);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['registration_success'] = true;
            header("Location: login_employer.php");
            exit;
        } else {
            $errors['general'] = "Gagal mendaftar. Error: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Employer - Job Portal Indonesia</title>
    <link rel="stylesheet" href="styles/logreg_user.css?v=<?= time(); ?>">
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
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        .form-group input:focus {
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
</head>
<body>

<!-- Navbar -->
<div class="navbar-container">
    <nav class="navbar">
        <a href="index.php" class="logo">
            <img src="assets/logo website/jobseeker.png" alt="Logo Web" />
        </a>
        <div class="nav-right">
            <a href="login_employer.php"><button class="outline-button">Masuk</button></a>
            <ul class="breadcrumb">
                <li><a href="index.php" class="nav-item">Beranda</a></li>
                <li><span>/</span></li>
                <li><a href="register_employer.php" class="nav-item active">Daftar Employer</a></li>
            </ul>
        </div>
    </nav>
</div>

<!-- Registration Form with Jobseeker Link -->
<div class="register-wrapper">
    <div class="jobseeker-link-container">
        <a href="register_user.php" class="jobseeker-link">Apakah Anda mencari pekerjaan?</a>
    </div>
    
    <div class="register-container">
        <h1 class="register-title">Daftar Sebagai Employer</h1>
        
        <form method="POST" action="register_employer.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_perusahaan">Nama Perusahaan</label>
                <input type="text" id="nama_perusahaan" name="nama_perusahaan" required placeholder="Masukkan nama perusahaan"
                       value="<?= htmlspecialchars($_POST['nama_perusahaan'] ?? '') ?>">
                <?php if(isset($errors['nama_perusahaan'])): ?>
                    <div class="error-message"><?= $errors['nama_perusahaan'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" required placeholder="contoh@email.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <?php if(isset($errors['email'])): ?>
                    <div class="error-message"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password (minimal 6 karakter)">
                <?php if(isset($errors['password'])): ?>
                    <div class="error-message"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Ulangi password">
                <?php if(isset($errors['confirm_password'])): ?>
                    <div class="error-message"><?= $errors['confirm_password'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="no_telepon">Nomor Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" required placeholder="Masukkan nomor telepon"
                       value="<?= htmlspecialchars($_POST['no_telepon'] ?? '') ?>">
                <?php if(isset($errors['no_telepon'])): ?>
                    <div class="error-message"><?= $errors['no_telepon'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="alamat">Alamat Perusahaan</label>
                <input type="text" id="alamat" name="alamat" required placeholder="Masukkan alamat perusahaan"
                       value="<?= htmlspecialchars($_POST['alamat'] ?? '') ?>">
                <?php if(isset($errors['alamat'])): ?>
                    <div class="error-message"><?= $errors['alamat'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="logo">Logo Perusahaan</label>
                <input type="file" id="logo" name="logo" accept="image/jpeg, image/png, image/gif"  required>
                <?php if(isset($errors['logo'])): ?>
                    <div class="error-message"><?= $errors['logo'] ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="register-button">DAFTAR SEKARANG</button>
        </form>
        
        <div class="register-footer">
            Sudah punya akun? <a href="login_employer.php">Masuk disini</a>
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