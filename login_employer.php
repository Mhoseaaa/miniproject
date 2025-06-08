<?php
// login_employer.php
session_start();
require_once 'koneksi.php';

// Check if employer is already logged in
if(isset($_SESSION['employer_id'])) {
    header("Location: employer_dashboard.php");
    exit;
}

// Handle login form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validasi
    if(empty($email) || empty($password)) {
        $error = "Email dan password harus diisi";
    } else {
        // Cek employer di database
        $stmt = $conn->prepare("SELECT id, company_name, email, password FROM employers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows === 1) {
            $employer = $result->fetch_assoc();
            
            // Verifikasi password
            if(password_verify($password, $employer['password'])) {
                // Login sukses
                $_SESSION['employer_id'] = $employer['id'];
                $_SESSION['employer_name'] = $employer['company_name'];
                $_SESSION['employer_email'] = $employer['email'];
                
                header("Location: employer_dashboard.php");
                exit;
            } else {
                $error = "Email atau password salah";
            }
        } else {
            $error = "Email atau password salah";
        }
        $stmt->close();
    }
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
        <a href="login-user.php" class="jobseeker-link">Apakah Anda mencari pekerjaan?</a>
    </div>
    
    <div class="register-container">
        <h1 class="register-title">Masuk Sebagai Employer</h1>
        
        <?php if(isset($error)): ?>
            <div class="error-message" style="text-align: center; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login_employer.php">
            <div class="form-group">
                <label for="email">Email Perusahaan</label>
                <input type="email" id="email" name="email" required placeholder="contoh@perusahaan.com">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password">
            </div>
            
            <button type="submit" class="register-button">MASUK</button>
        </form>
        
        <div class="register-footer">
            Belum punya akun employer? <a href="register_employer.php">Daftar disini</a><br>
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