<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['employer_id'])) {
    $redirect = $_GET['redirect'] ?? 'dashboard_employer.php';
    header("Location: $redirect");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi";
    } else {
        // ——— QUERY BIASA TANPA PREPARED STATEMENT ———
        $email_safe = mysqli_real_escape_string($conn, $email);
        $sql        = "SELECT id, nama_perusahaan, email, password 
                       FROM employers 
                       WHERE email = '$email_safe'";
        $result     = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $employer = mysqli_fetch_assoc($result);

            if (password_verify($password, $employer['password'])) {
                $_SESSION['employer_id']    = $employer['id'];
                $_SESSION['employer_name']  = $employer['nama_perusahaan'];
                $_SESSION['employer_email'] = $employer['email'];

                $redirect = $_GET['redirect'] ?? 'dashboard_employer.php';
                header("Location: $redirect");
                exit;
            } else {
                $error = "Email atau password salah";
            }
        } else {
            $error = "Email atau password salah";
        }
    }
}

$redirect = $_GET['redirect'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Employer - JobSeeker</title>
    <link rel="stylesheet" href="styles/logreg_user.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="styles/index.css?v=<?= time(); ?>">
    <style>
        /* Make sticky footer layout */
        html, body { height: 100%; margin: 0; }
        body { display: flex; flex-direction: column; }
        main.main-content { flex: 1; padding-top: 80px; /* adjust for navbar */ }

        /* Navbar */
        .navbar-container {
            position: fixed; top: 0; left: 0; width: 100%;
            background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .navbar {
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            padding: 5px 15px;
        }
        .logo img { width: 150px; height: auto; }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .outline-button {
            padding: 8px 16px; border: 2px solid #001f54;
            background: #fff; color: #001f54; border-radius: 5px;
            font-weight: bold; cursor: pointer; text-decoration: none;
            transition: background 0.3s, color 0.3s;
        }
        .outline-button:hover { background: #001f54; color: #fff; }
        .breadcrumb { list-style: none; display: flex; gap: 5px; margin: 0; }
        .nav-item { position: relative; font-weight: bold; text-decoration: none; color: #000; padding-bottom: 5px; }
        .nav-item::after {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 100%; height: 2px; background: #001f54;
            transform: scaleX(0); transition: transform 0.3s;
        }
        .nav-item.active::after, .nav-item:hover::after { transform: scaleX(1); }

        /* Login form */
        .login-wrapper { max-width: 550px; margin: 0 auto; }
        .jobseeker-link-container { text-align: right; margin-bottom: 10px; }
        .jobseeker-link { color: #001f54; font-weight: bold; text-decoration: none; transition: 0.3s; }
        .jobseeker-link:hover { text-decoration: underline; }
        .login-container {
            background: #fff; padding: 40px;
            border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        .login-title { color: #001f54; text-align: center; margin-bottom: 30px; font-size: 28px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-group input {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;
            font-size: 16px; transition: border 0.3s;
        }
        .form-group input:focus { border-color: #001f54; outline: none; }
        .login-button {
            width: 100%; padding: 14px; background: #001f54; color: #fff;
            border: none; border-radius: 6px; font-weight: bold; cursor: pointer;
            transition: background 0.3s;
        }
        .login-button:hover { background: #000e27; }
        .login-footer { text-align: center; margin-top: 20px; color: #666; font-size: 14px; }
        .login-footer a { color: #001f54; font-weight: bold; text-decoration: none; }
        .login-footer a:hover { text-decoration: underline; }
        .error-message { color: #ff007f; text-align: center; margin-bottom: 20px; font-weight: bold; }

        /* Footer */
        .footer { background: #001f54; color: #fff; padding: 40px 20px; }
        .footer-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; flex-wrap: wrap; }
        .footer-section { flex: 1; min-width: 200px; margin: 0 10px; }
        .footer-section h3, .footer-section h4 { font-size: 18px; margin-bottom: 8px; }
        .footer-section p, .footer-section ul li { font-size: 14px; margin-bottom: 6px; }
        .footer-section ul { list-style: none; padding: 0; }
        .footer-section ul li a { color: #fff; text-decoration: none; transition: color 0.3s; }
        .footer-section ul li a:hover { color: #ff007f; }
        .footer-copy { background: #000e27; text-align: center; padding: 10px 0; font-size: 13px; opacity: 0.8; margin-top: 20px; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-container">
        <nav class="navbar">
            <a href="index.php" class="logo"><img src="assets/logo website/jobseeker.png" alt="Logo"></a>
            <div class="nav-right">
                <a href="register_employer.php" class="outline-button">Daftar</a>
                <ul class="breadcrumb">
                    <li><a href="index.php" class="nav-item">Beranda</a></li>
                    <li><span>/</span></li>
                    <li><a href="login_employer.php" class="nav-item active">Masuk Employer</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="login-wrapper">
            <div class="jobseeker-link-container">
                <a href="login_user.php" class="jobseeker-link">Apakah Anda mencari pekerjaan?</a>
            </div>
            <div class="login-container">
                <h1 class="login-title">Masuk Sebagai Employer</h1>
                <?php if (isset($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="login_employer.php<?= $redirect ? '?redirect=' . urlencode($redirect) : '' ?>">
                    <div class="form-group">
                        <label for="email">Email Perusahaan</label>
                        <input type="email" id="email" name="email" required placeholder="contoh@perusahaan.com">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Masukkan password">
                    </div>
                    <button type="submit" class="login-button">MASUK</button>
                </form>
                <div class="login-footer">
                    Belum punya akun employer? <a href="register_employer.php">Daftar disini</a><br>
                    <a href="forgot-password.php">Lupa password?</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
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
        <div class="footer-copy">&copy; 2025 JobSeeker Indonesia. All Rights Reserved.</div>
    </footer>
</body>
</html>