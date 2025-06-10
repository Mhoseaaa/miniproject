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
        // Query biasa tanpa prepared statement
        $email_escaped = mysqli_real_escape_string($conn, $email);
        $query = "SELECT id, name, email, password FROM users WHERE email = '$email_escaped'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
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
    <title>Login - Job Portal Indonesia</title>
    <link rel="stylesheet" href="styles/logreg_user.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="styles/index.css?v=<?= time(); ?>">
    <style>
        .login-wrapper {
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

        .login-container {
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        
        .login-title {
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
            .employer-link-container {
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
            <a href="register_user.php"><button class="outline-button">Daftar</button></a>
            <ul class="breadcrumb">
                <li><a href="index.php" class="nav-item">Beranda</a></li>
                <li><span>/</span></li>
                <li><a href="login_user.php" class="nav-item active">Masuk</a></li>
            </ul>
        </div>
    </nav>
</div>

<!-- Login Form with Employer Link -->
<div class="login-wrapper">
    <div class="employer-link-container">
        <a href="register_employer.php" class="employer-link">Apakah Anda mencari karyawan?</a>
    </div>
    
    <div class="login-container">
        <h1 class="login-title">Masuk ke Akun Anda</h1>
        
        <?php if(isset($error)): ?>
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
            <a href="forgot-password.php">Lupa password?</a>
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