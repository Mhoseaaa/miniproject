<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Lamaran Kerja - JobSeeker</title>
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

        .file-upload {
            border: 1px dashed #ddd;
            padding: 20px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        
        .file-upload input {
            display: none;
        }
        
        .file-upload label {
            color: #001f54;
            font-weight: bold;
            cursor: pointer;
        }
        
        .file-name {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
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
                <a href="login_user.php"><button class="outline-button">Masuk</button></a>
                <ul class="breadcrumb">
                    <li><a href="index.php" class="nav-item">Beranda</a></li>
                    <li><span>/</span></li>
                    <li><a href="#" class="nav-item active">Form Lamaran</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Form Lamaran -->
    <div class="register-wrapper">
        <div class="register-container">
            <h1 class="register-title">Form Lamaran Kerja</h1>
            
            <form method="POST" action="proses_lamaran.php" enctype="multipart/form-data">
                <!-- Data Pribadi -->
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" required placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="contoh@email.com">
                </div>
                
                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input type="tel" id="telepon" name="telepon" required placeholder="081234567890">
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" required placeholder="Jl. Contoh No. 123, Kota, Provinsi"></textarea>
                </div>
                
                <!-- Pendidikan Terakhir -->
                <div class="form-group">
                    <label for="pendidikan">Pendidikan Terakhir</label>
                    <select id="pendidikan" name="pendidikan" required>
                        <option value="">Pilih Pendidikan</option>
                        <option value="SMA/SMK">SMA/SMK</option>
                        <option value="D3">Diploma (D3)</option>
                        <option value="S1/D4">Sarjana (S1/D4)</option>
                        <option value="S2">Magister (S2)</option>
                        <option value="S3">Doktor (S3)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="institusi">Nama Institusi Pendidikan</label>
                    <input type="text" id="institusi" name="institusi" required placeholder="Universitas Contoh">
                </div>
                
                <div class="form-group">
                    <label for="jurusan">Jurusan</label>
                    <input type="text" id="jurusan" name="jurusan" required placeholder="Teknik Informatika">
                </div>
                
                <!-- Upload Dokumen -->
                <div class="form-group">
                    <label>Upload Dokumen</label>
                    
                    <div class="file-upload">
                        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                        <label for="cv">Unggah CV/Resume</label>
                        <div class="file-name" id="cv-name">Belum ada file dipilih</div>
                    </div>
                    
                    <div class="file-upload">
                        <input type="file" id="ijazah" name="ijazah" accept=".pdf,.doc,.docx,.jpg,.png" required>
                        <label for="ijazah">Unggah Ijazah Terakhir</label>
                        <div class="file-name" id="ijazah-name">Belum ada file dipilih</div>
                    </div>
                    
                    <div class="file-upload">
                        <input type="file" id="transkrip" name="transkrip" accept=".pdf,.doc,.docx,.jpg,.png">
                        <label for="transkrip">Unggah Transkrip Nilai (Opsional)</label>
                        <div class="file-name" id="transkrip-name">Belum ada file dipilih</div>
                    </div>
                    
                    <div class="file-upload">
                        <input type="file" id="portofolio" name="portofolio" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                        <label for="portofolio">Unggah Portofolio (Opsional)</label>
                        <div class="file-name" id="portofolio-name">Belum ada file dipilih</div>
                    </div>
                </div>
                
                <!-- Pengalaman Kerja -->
                <div class="form-group">
                    <label for="pengalaman">Pengalaman Kerja (tahun)</label>
                    <input type="number" id="pengalaman" name="pengalaman" min="0" placeholder="0">
                </div>
                
                <div class="form-group">
                    <label for="skills">Keterampilan/Kemampuan</label>
                    <textarea id="skills" name="skills" rows="3" placeholder="Contoh: PHP, MySQL, JavaScript, Desain Grafis"></textarea>
                </div>
                
                <!-- Motivasi -->
                <div class="form-group">
                    <label for="motivasi">Surat Motivasi (Opsional)</label>
                    <textarea id="motivasi" name="motivasi" rows="5" placeholder="Ceritakan mengapa Anda cocok untuk posisi ini"></textarea>
                </div>
                
                <button type="submit" class="register-button">KIRIM LAMARAN</button>
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

    <script>
        // Menampilkan nama file yang diupload
        document.getElementById('cv').addEventListener('change', function(e) {
            document.getElementById('cv-name').textContent = e.target.files[0] ? e.target.files[0].name : 'Belum ada file dipilih';
        });
        
        document.getElementById('ijazah').addEventListener('change', function(e) {
            document.getElementById('ijazah-name').textContent = e.target.files[0] ? e.target.files[0].name : 'Belum ada file dipilih';
        });
        
        document.getElementById('transkrip').addEventListener('change', function(e) {
            document.getElementById('transkrip-name').textContent = e.target.files[0] ? e.target.files[0].name : 'Belum ada file dipilih';
        });
        
        document.getElementById('portofolio').addEventListener('change', function(e) {
            document.getElementById('portofolio-name').textContent = e.target.files[0] ? e.target.files[0].name : 'Belum ada file dipilih';
        });
    </script>
</body>
</html>