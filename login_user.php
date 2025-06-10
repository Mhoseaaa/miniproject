<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user_email'])) {
    $redirect = $_GET['redirect'] ?? 'dashboard_user.php';
    header("Location: $redirect");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi";
    } else {
        $email_escaped = mysqli_real_escape_string($conn, $email);
        $query = "SELECT id, name, email, password FROM users WHERE email = '$email_escaped'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                $redirect = $_GET['redirect'] ?? 'dashboard_user.php';
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
    <title>Masuk - JobSeeker</title>
    <link rel="stylesheet" href="navbar.css">
    <style>
        /* Layout: sticky footer */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        
        .login-button {
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
        
        .login-button:hover {
            background-color: #000e27;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-footer a {
            color: #001f54;
            text-decoration: none;
            font-weight: bold;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: #ff007f;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .outline-button {
            background: white;
            color: #001f54;
            border: 2px solid #001f54;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .outline-button:hover {
            background: #001f54;
            color: white;
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

        @media (max-width: 768px) {
            .jobseeker-link-container {
                text-align: center;
                margin-bottom: 20px;
            }
            
            .login-wrapper {
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
        }
        .outline-button:hover { background: #001f54; color: white; }
        .breadcrumb { list-style: none; display: flex; gap: 5px; margin: 0; }
        .nav-item { position: relative; font-weight: bold; text-decoration: none; color: black; padding-bottom: 5px; }
        .nav-item::after { content: ""; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background-color: #001f54; transform: scaleX(0); transition: transform 0.3s; }
        .nav-item.active::after, .nav-item:hover::after { transform: scaleX(1); }
        /* Login form */
        .login-wrapper { max-width: 550px; margin: 0 auto; }
        .employer-link-container { text-align: right; margin-bottom: 10px; }
        .employer-link { color: #001f54; font-weight: bold; text-decoration: none; font-size: 16px; transition: 0.3s; }
        .employer-link:hover { text-decoration: underline; }
        .login-container {
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        .login-title { color: #001f54; text-align: center; margin-bottom: 30px; font-size: 28px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s; }
        .form-group input:focus { border-color: #001f54; outline: none; }
        .login-button { width: 100%; padding: 14px; background-color: #001f54; color: #fff; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        .login-button:hover { background-color: #000e27; }
        .login-footer { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .login-footer a { color: #001f54; font-weight: bold; text-decoration: none; }
        .login-footer a:hover { text-decoration: underline; }
        .error-message { color: #ff007f; text-align: center; margin-bottom: 20px; font-weight: bold; }
        /* Footer */
        .footer { background-color: #001f54; color: #fff; padding: 40px 20px; }
        .footer-container { display: flex; justify-content: space-between; flex-wrap: wrap; max-width: 1200px; margin: 0 auto; }
        .footer-section { flex: 1; min-width: 200px; margin: 0 10px; }
        .footer-section h3, .footer-section h4 { font-size: 18px; margin-bottom: 8px; }
        .footer-section p, .footer-section ul li { font-size: 14px; margin-bottom: 6px; }
        .footer-section ul { list-style: none; padding: 0; }
        .footer-section ul li a { color: #fff; text-decoration: none; transition: color 0.3s; }
        .footer-section ul li a:hover { color: #ff007f; }
        .footer-copy { background-color: #000e27; text-align: center; padding: 10px 0; font-size: 13px; opacity: 0.8; margin-top: 20px; }
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
                <a href="login_user.php" class="nav-item active">Masuk</a>
                <a href="register_user.php" class="nav-item">Daftar</a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="login-wrapper">
            <div class="employer-link-container">
                <a href="register_employer.php" class="employer-link">Apakah Anda mencari karyawan?</a>
            </div>
            <div class="login-container">
                <h1 class="login-title">Masuk ke Akun Anda</h1>
                <?php if (isset($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="login_user.php<?= $redirect ? '?redirect=' . urlencode($redirect) : '' ?>">
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" required placeholder="contoh@email.com">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Masukkan password">
                    </div>
                    <button type="submit" class="login-button">MASUK</button>
                </form>
                <div class="login-footer">
                    Belum punya akun? <a href="register_user.php">Daftar disini</a><br>
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
