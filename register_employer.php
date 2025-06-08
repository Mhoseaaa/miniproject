<?php
// register_employer.php
session_start();
require_once 'koneksi.php';

// Check if employer is already logged in
if(isset($_SESSION['employer_id'])) {
    header("Location: employer_dashboard.php");
    exit;
}

// Handle registration form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = trim($_POST['company_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $industry = trim($_POST['industry'] ?? '');
    
    // Validasi
    $errors = [];
    
    if(empty($company_name)) {
        $errors['company_name'] = "Nama perusahaan harus diisi";
    }
    
    if(empty($email)) {
        $errors['email'] = "Email harus diisi";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format email tidak valid";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $conn->prepare("SELECT id FROM employers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if($stmt->num_rows > 0) {
            $errors['email'] = "Email ini sudah terdaftar";
        }
        $stmt->close();
    }
    
    if(empty($password)) {
        $errors['password'] = "Password harus diisi";
    } elseif(strlen($password) < 6) {
        $errors['password'] = "Password minimal 6 karakter";
    }
    
    if($password !== $confirm_password) {
        $errors['confirm_password'] = "Password tidak cocok";
    }
    
    if(empty($phone)) {
        $errors['phone'] = "Nomor telepon harus diisi";
    }
    
    // Jika tidak ada error, proses registrasi
    if(empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO employers (company_name, email, password, phone, address, industry) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $company_name, $email, $hashed_password, $phone, $address, $industry);
        
        if($stmt->execute()) {
            // Registrasi berhasil
            $_SESSION['registration_success'] = true;
            header("Location: login_employer.php");
            exit;
        } else {
            $errors['database'] = "Terjadi kesalahan. Silakan coba lagi.";
        }
        $stmt->close();
    }
}

// Update koneksi.php untuk membuat tabel employers jika belum ada
$sql = "CREATE TABLE IF NOT EXISTS employers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    industry VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating employers table: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Employer - Job Portal Indonesia</title>
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
            <a href="login_employer.php"><button class="outline-button">Masuk</button></a>
            <ul class="breadcrumb">
                <li><a href="index.php" class="nav-item">Beranda</a></li>
                <li><span>/</span></li>
                <li><a href="register_employer.php" class="nav-item active">Daftar Employer</a></li>
            </ul>
        </div>
    </nav>
</div>

<!-- Registration Form for Employers -->
<div class="register-wrapper">
    <div class="jobseeker-link-container">
        <a href="register.php" class="jobseeker-link">Apakah Anda mencari pekerjaan?</a>
    </div>
    
    <div class="register-container">
        <h1 class="register-title">Daftar Sebagai Employer</h1>
        
        <?php if(isset($errors['database'])): ?>
            <div class="error-message" style="text-align: center; margin-bottom: 20px;">
                <?= htmlspecialchars($errors['database']) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="register_employer.php">
            <div class="form-group">
                <label for="company_name">Nama Perusahaan</label>
                <input type="text" id="company_name" name="company_name" required 
                       placeholder="Masukkan nama perusahaan" value="<?= htmlspecialchars($_POST['company_name'] ?? '') ?>">
                <?php if(isset($errors['company_name'])): ?>
                    <div class="error-message"><?= $errors['company_name'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email Perusahaan</label>
                <input type="email" id="email" name="email" required 
                       placeholder="contoh@perusahaan.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <?php if(isset($errors['email'])): ?>
                    <div class="error-message"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Masukkan password (minimal 6 karakter)">
                <?php if(isset($errors['password'])): ?>
                    <div class="error-message"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required 
                       placeholder="Masukkan ulang password">
                <?php if(isset($errors['confirm_password'])): ?>
                    <div class="error-message"><?= $errors['confirm_password'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="tel" id="phone" name="phone" required 
                       placeholder="Masukkan nomor telepon" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                <?php if(isset($errors['phone'])): ?>
                    <div class="error-message"><?= $errors['phone'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="industry">Industri</label>
                <select id="industry" name="industry">
                    <option value="">Pilih industri</option>
                    <option value="IT" <?= ($_POST['industry'] ?? '') === 'IT' ? 'selected' : '' ?>>IT</option>
                    <option value="Keuangan" <?= ($_POST['industry'] ?? '') === 'Keuangan' ? 'selected' : '' ?>>Keuangan</option>
                    <option value="Manufaktur" <?= ($_POST['industry'] ?? '') === 'Manufaktur' ? 'selected' : '' ?>>Manufaktur</option>
                    <option value="Pendidikan" <?= ($_POST['industry'] ?? '') === 'Pendidikan' ? 'selected' : '' ?>>Pendidikan</option>
                    <option value="Kesehatan" <?= ($_POST['industry'] ?? '') === 'Kesehatan' ? 'selected' : '' ?>>Kesehatan</option>
                    <option value="Retail" <?= ($_POST['industry'] ?? '') === 'Retail' ? 'selected' : '' ?>>Retail</option>
                    <option value="Lainnya" <?= ($_POST['industry'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="address">Alamat Perusahaan</label>
                <textarea id="address" name="address" rows="3" 
                          placeholder="Masukkan alamat perusahaan"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" class="register-button">DAFTAR SEKARANG</button>
        </form>
        
        <div class="register-footer">
            Sudah punya akun employer? <a href="login_employer.php">Masuk disini</a>
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