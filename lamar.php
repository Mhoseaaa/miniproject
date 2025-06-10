<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Lamaran Kerja</title>
  <style>
    /* Layout: Header, Content, Footer */
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f5f8fa;
    }
    main.content {
      flex: 1;
      display: flex;
      justify-content: center;
      padding: 100px 0 40px; /* space for fixed navbar */
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
      padding: 10px 20px;
      max-width: 1200px;
      margin: 0 auto;
    }
    .logo img { width: 150px; height: auto; }
    .nav-right { display: flex; align-items: center; gap: 20px; }
    .outline-button {
      background: white;
      color: #001f54;
      border: 2px solid #001f54;
      padding: 8px 16px;
      border-radius: 5px;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }
    .outline-button:hover { background: #001f54; color: white; }
    .breadcrumb { list-style: none; margin: 0; padding: 0; }
    .breadcrumb li { display: inline; }
    .breadcrumb .nav-item {
      text-decoration: none;
      color: black;
      font-weight: bold;
      position: relative;
    }
    .breadcrumb .nav-item::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background: #001f54;
      transform: scaleX(0);
      transition: transform 0.3s;
    }
    .breadcrumb .active::after { transform: scaleX(1); }

    /* FORM */
    .form-container {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      padding: 32px;
      width: 100%;
      max-width: 700px;
    }
    .form-title {
      text-align: center;
      color: #0b1f47;
      margin-bottom: 24px;
      font-size: 24px;
    }
    .form-group { margin-bottom: 20px; display: flex; flex-direction: column; }
    .form-group label { font-weight: 600; margin-bottom: 6px; color: #0b1f47; }
    .form-group input,
    .form-group select,
    .form-group textarea {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      outline: none;
    }
    .form-group textarea { resize: vertical; min-height: 80px; }
    .form-row { display: flex; gap: 20px; flex-wrap: wrap; }
    .form-row .form-group { flex: 1 1 calc(50% - 20px); }
    .upload-section { margin: 40px 0 20px; text-align: center; }
    .upload-grid { display: inline-grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .upload-item {
      position: relative;
      overflow: hidden;
      border: 2px dashed #d1d5db;
      border-radius: 8px;
      background-color: #f9fafb;
      cursor: pointer;
      min-height: 160px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 16px;
    }
    .upload-item img.preview {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      object-fit: cover;
      filter: blur(8px);
      z-index: 1;
    }
    .upload-item span,
    .upload-item small { position: relative; z-index: 2; }
    .upload-item small { display: block; margin-top: 6px; color: #6b7280; font-weight: normal; }
    .upload-item input {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      opacity: 0;
      cursor: pointer;
      z-index: 3;
    }
    .btn-submit {
      display: block;
      width: 100%;
      padding: 14px;
      background-color: #ff007a;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
      font-size: 16px;
    }
    .btn-submit:hover { opacity: 0.9; }

    /* FOOTER */
    .footer {
      background-color: #001f54;
      color: white;
      width: 100%;
      padding: 20px 0;
      text-align: center;
    }
    .footer-container {
      max-width: 1200px;
      margin: auto;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      text-align: left;
    }
    .footer-section { flex: 1; min-width: 200px; margin: 10px; }
    .footer-section h3,
    .footer-section h4 { margin-bottom: 10px; font-size: 18px; }
    .footer-section ul { list-style: none; padding: 0; }
    .footer-section ul li { margin: 5px 0; }
    .footer-section ul li a { color: white; text-decoration: none; transition: color 0.3s; }
    .footer-section ul li a:hover { color: #ff007f; }
    .footer-copy { margin-top: 20px; font-size: 14px; opacity: 0.7; }
  </style>
</head>
<body>
  <!-- Navbar -->
  <div class="navbar-container">
    <nav class="navbar">
      <a href="index.php" class="logo"><img src="assets/logo website/jobseeker.png" alt="Logo Web"></a>
      <div class="nav-right">
        <a href="login_user.php" class="outline-button">Masuk</a>
        <ul class="breadcrumb">
          <li><a href="index.php" class="nav-item active">Beranda</a></li>
        </ul>
      </div>
    </nav>
  </div>

  <!-- Main Content -->
  <main class="content">
    <form class="form-container" action="lamar.php" method="POST" enctype="multipart/form-data">
      <h2 class="form-title">Form Lamaran Kerja</h2>
      <!-- Baris 1: Nama & Email -->
      <div class="form-row">
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input id="nama" name="nama" type="text" placeholder="Masukkan nama lengkap" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" placeholder="contoh@email.com" required>
        </div>
      </div>
      <!-- Baris 2: Telepon & Alamat -->
      <div class="form-row">
        <div class="form-group">
          <label for="telp">Nomor Telepon</label>
          <input id="telp" name="telp" type="tel" placeholder="081234567890" required>
        </div>
        <div class="form-group">
          <label for="alamat">Alamat Lengkap</label>
          <input id="alamat" name="alamat" type="text" placeholder="Jl. Contoh No. 123, Kota, Provinsi" required>
        </div>
      </div>
      <!-- Baris 3: Pendidikan & Institusi -->
      <div class="form-row">
        <div class="form-group">
          <label for="pendidikan">Pendidikan Terakhir</label>
          <select id="pendidikan" name="pendidikan" required>
            <option value="">Pilih Pendidikan</option>
            <option value="SMA">SMA/SMK</option>
            <option value="D3">D3</option>
            <option value="S1">S1</option>
            <option value="S2">S2</option>
            <option value="S3">S3</option>
          </select>
        </div>
        <div class="form-group">
          <label for="institusi">Nama Institusi</label>
          <input id="institusi" name="institusi" type="text" placeholder="Universitas Contoh" required>
        </div>
      </div>
      <!-- Jurusan -->
      <div class="form-group">
        <label for="jurusan">Jurusan</label>
        <input id="jurusan" name="jurusan" type="text" placeholder="Teknik Informatika" required>
      </div>
      <!-- Upload Dokumen -->
      <div class="upload-section">
        <div class="upload-grid">
          <label class="upload-item">
            <img id="prev-cv" class="preview" hidden>
            <span>Unggah CV/Resume</span>
            <input type="file" name="cv" accept="application/pdf,image/*" required>
            <small id="cvName">Belum ada file dipilih</small>
          </label>
          <label class="upload-item">
            <img id="prev-ijazah" class="preview" hidden>
            <span>Unggah Ijazah</span>
            <input type="file" name="ijazah" accept="application/pdf,image/*" required>
            <small id="ijazahName">Belum ada file dipilih</small>
          </label>
          <label class="upload-item">
            <img id="prev-transkrip" class="preview" hidden>
            <span>Unggah Transkrip (Opsional)</span>
            <input type="file" name="transkrip" accept="application/pdf,image/*">
            <small id="transkripName">Belum ada file dipilih</small>
          </label>
        </div>
      </div>
      <!-- Pengalaman & Keterampilan -->
      <div class="form-group">
        <label for="pengalaman">Pengalaman Kerja (tahun)</label>
        <input id="pengalaman" name="pengalaman" type="number" min="0" value="0" required>
      </div>
      <div class="form-group">
        <label for="keterampilan">Keterampilan/Kemampuan</label>
        <textarea id="keterampilan" name="keterampilan" placeholder="Contoh: PHP, MySQL, JavaScript, Desain Grafis" required></textarea>
      </div>
      <!-- Tombol Kirim -->
      <button type="submit" class="btn-submit">KIRIM LAMARAN</button>
    </form>
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
