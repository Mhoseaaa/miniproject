<?php
session_start();
include 'koneksi.php';

$errors = [];

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi
    if (empty($name)) {
        $errors['name'] = "Nama lengkap harus diisi";
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

    // Cek apakah email sudah digunakan
    if (empty($errors)) {
        $escaped_email = mysqli_real_escape_string($conn, $email);
        $sql_check = "SELECT id FROM users WHERE email = '$escaped_email'";
        $result_check = mysqli_query($conn, $sql_check);

        if (!$result_check) {
            die("Error checking email: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result_check) > 0) {
            $errors['email'] = "Email sudah terdaftar";
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $escaped_name = mysqli_real_escape_string($conn, $name);
        $escaped_email = mysqli_real_escape_string($conn, $email);
        $escaped_password = mysqli_real_escape_string($conn, $hashed_password);

        $sql_insert = "INSERT INTO users (name, email, password) VALUES ('$escaped_name', '$escaped_email', '$escaped_password')";

        if (mysqli_query($conn, $sql_insert)) {
            $_SESSION['registration_success'] = true;
            header("Location: login_user.php");
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
    <title>Daftar - Job Portal Indonesia</title>
    <link rel="stylesheet" href="navbar.css">
    <style>
        .register-wrapper {
            max-width: 550px;
            margin: 80px auto 0;
        }
        
        .employer-link-container {
            text-align: right;
            margin-bottom: 10px;
        }
        
        .employer-link {
            color: #001f54;
            font-weight: bold;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s;
            display: inline-block;
            margin-right:-50px;
        }

        .employer-link:hover {
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
            .employer-link-container {
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
            <a href="employer/dashboard_employer.php" class="logo">
                <img src="assets/logo website/jobseeker.png" alt="JobSeeker Logo">
            </a>
            <div class="nav-right">
                <a href="login_user.php" class="nav-item">Masuk</a>
                <a href="register_user.php" class="nav-item active">Daftar</a>
            </div>
        </nav>
    </div>

<!-- Registration Form with Employer Link -->
<div class="register-wrapper">
    <div class="employer-link-container">
        <a href="register_employer.php" class="employer-link">Apakah Anda mencari karyawan?</a>
    </div>
    
    <div class="register-container">
        <h1 class="register-title">Buat Akun Baru</h1>
        
        <form method="POST" action="register_user.php">
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required placeholder="Masukkan nama lengkap"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                <?php if(isset($errors['name'])): ?>
                    <div class="error-message"><?= $errors['name'] ?></div>
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

            
            <button type="submit" class="register-button">DAFTAR</button>
        </form>
        
        <div class="register-footer">
            Sudah punya akun? <a href="login_user.php">Masuk disini</a>
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