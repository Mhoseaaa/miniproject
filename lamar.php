<?php
session_start();
include 'koneksi.php';

$errors = [];

if (!isset($_SESSION['user_email'])) {
  header("Location: login_user.php");
  exit;
}

$userEmail = $_SESSION['user_email'];

// Ambil data user dari database TANPA prepared statement
$userEmailEsc = $conn->real_escape_string($userEmail);
$sqlUser = "SELECT name, email FROM users WHERE email = '$userEmailEsc'";
$resultUser = $conn->query($sqlUser);

if (!$resultUser || $resultUser->num_rows === 0) {
    session_destroy();
    header("Location: login_user.php");
    exit;
}

$user = $resultUser->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $lowongan_id = $_POST['lowongan_id'] ?? ($_GET['id'] ?? '');
  $nama = trim($_POST['nama'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $telp = trim($_POST['telp'] ?? '');
  $alamat = trim($_POST['alamat'] ?? '');
  $pendidikan = $_POST['pendidikan'] ?? '';
  $institusi = trim($_POST['institusi'] ?? '');
  $jurusan = trim($_POST['jurusan'] ?? '');
  $pengalaman = $_POST['pengalaman'] ?? 0;
  $keterampilan = trim($_POST['keterampilan'] ?? '');

  $cv_path = null;
  $ijazah_path = null;
  $transkrip_path = null;

  // Validasi
  if (empty($nama))
    $errors['nama'] = "Nama wajib diisi";
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors['email'] = "Email tidak valid";
  if (empty($telp))
    $errors['telp'] = "Nomor telepon wajib diisi";
  if (empty($alamat))
    $errors['alamat'] = "Alamat wajib diisi";
  if (empty($pendidikan))
    $errors['pendidikan'] = "Pendidikan wajib dipilih";
  if (empty($institusi))
    $errors['institusi'] = "Institusi wajib diisi";
  if (empty($jurusan))
    $errors['jurusan'] = "Jurusan wajib diisi";
  if (empty($keterampilan))
    $errors['keterampilan'] = "Keterampilan wajib diisi";
  if (!is_numeric($pengalaman) || $pengalaman < 0)
    $pengalaman = 0;

  // Folder upload
  $upload_folder_rel = "assets/berkas_lamaran/";
  $upload_folder_abs = __DIR__ . '/' . $upload_folder_rel;

  if (!is_dir($upload_folder_abs)) {
    mkdir($upload_folder_abs, 0777, true);
  }

  // Fungsi upload
  function uploadBerkas($field_name, $prefix)
  {
    global $errors, $upload_folder_rel, $upload_folder_abs;

    if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] === UPLOAD_ERR_OK) {
      $allowed_types = ['application/pdf'];
      $file_type = $_FILES[$field_name]['type'];

      if (!in_array($file_type, $allowed_types)) {
        $errors[$field_name] = "Hanya file PDF yang diperbolehkan";
        return null;
      }

      $original_name = basename($_FILES[$field_name]['name']);
      $new_name = $prefix . '-' . time() . '-' . preg_replace('/\s+/', '-', $original_name);
      $path_rel = $upload_folder_rel . $new_name;
      $path_abs = $upload_folder_abs . $new_name;

      if (move_uploaded_file($_FILES[$field_name]['tmp_name'], $path_abs)) {
        return $path_rel;
      } else {
        $errors[$field_name] = "Gagal mengupload $field_name";
      }
    } else {
      $errors[$field_name] = ucfirst($field_name) . " wajib diupload";
    }
    return null;
  }

  // Proses upload berkas
  $cv_path = uploadBerkas("cv", "CV");
  $ijazah_path = uploadBerkas("ijazah", "Ijazah");

  if (isset($_FILES['transkrip']) && $_FILES['transkrip']['error'] !== UPLOAD_ERR_NO_FILE) {
    $transkrip_path = uploadBerkas("transkrip", "Transkrip");
  }

  // Insert ke database
  if (empty($errors)) {
    $sql = "INSERT INTO lamaran (
                    user_id, lowongan_id, nama, email, telp, alamat,
                    pendidikan, institusi, jurusan, cv_path, ijazah_path, transkrip_path,
                    pengalaman, keterampilan
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
      $stmt,
      "iisssssssssiss",
      $user_id,
      $lowongan_id,
      $nama,
      $email,
      $telp,
      $alamat,
      $pendidikan,
      $institusi,
      $jurusan,
      $cv_path,
      $ijazah_path,
      $transkrip_path,
      $pengalaman,
      $keterampilan
    );

    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['lamaran_success'] = "Lamaran berhasil dikirim!";
      header("Location: dashboard_user.php");
      exit;
    } else {
      $errors['general'] = "Gagal menyimpan lamaran: " . mysqli_error($conn);
    }
  }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Lamaran Kerja</title>
  <style>
    .upload-item {
      transition: all 0.3s ease;
    }

    .upload-item:hover {
      border-color: #001f54;
      background-color: #f0f4f8;
    }

    .upload-item.has-file {
      border-color: #4CAF50;
      background-color: #f0fff4;
    }

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
      padding: 100px 0 40px;
      /* space for fixed navbar */
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

    .breadcrumb {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .breadcrumb li {
      display: inline;
    }

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

    .breadcrumb .active::after {
      transform: scaleX(1);
    }

    /* FORM */
    .form-container {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
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

    .form-group {
      margin-bottom: 20px;
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      font-weight: 600;
      margin-bottom: 6px;
      color: #0b1f47;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      outline: none;
    }

    .form-group textarea {
      resize: vertical;
      min-height: 80px;
    }

    .form-row {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .form-row .form-group {
      flex: 1 1 calc(50% - 20px);
    }

    .upload-section {
      margin: 40px 0 20px;
      text-align: center;
    }

    .upload-grid {
      display: inline-grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

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
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: blur(8px);
      z-index: 1;
    }

    .upload-item span,
    .upload-item small {
      position: relative;
      z-index: 2;
    }

    .upload-item small {
      display: block;
      margin-top: 6px;
      color: #6b7280;
      font-weight: normal;
    }

    .upload-item input {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
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

    .btn-submit:hover {
      opacity: 0.9;
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
        <a href="dashboard_user.php" class="logo">
            <img src="assets/logo website/jobseeker.png" alt="Logo Web" />
        </a>
        <div class="nav-right">
            <span>Halo, <?= htmlspecialchars($user['name']) ?></span> |
            <a href="dashboard_user.php" class="nav-item">Dashboard</a> |
            <a href="profile_user.php" class="nav-item">Profil</a> |
            <a href="logout.php" class="nav-item">Logout</a>
        </div>
    </nav>
</div>

  <!-- Main Content -->
  <main class="content">
    <form class="form-container" action="lamar.php" method="POST" enctype="multipart/form-data">
      <h2 class="form-title">Form Lamaran Kerja</h2>
      <input type="hidden" name="lowongan_id" value="<?php echo htmlspecialchars($_GET['id'] ?? '', ENT_QUOTES); ?>">
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
            <div id="pdf-icon-cv" style="display:none;font-size:40px;margin-top:10px;">📄</div>
          </label>
          <label class="upload-item">
            <img id="prev-ijazah" class="preview" hidden>
            <span>Unggah Ijazah</span>
            <input type="file" name="ijazah" accept="application/pdf,image/*" required>
            <small id="ijazahName">Belum ada file dipilih</small>
            <div id="pdf-icon-ijazah" style="display:none;font-size:40px;margin-top:10px;">📄</div>
          </label>
          <label class="upload-item">
            <img id="prev-transkrip" class="preview" hidden>
            <span>Unggah Transkrip (Opsional)</span>
            <input type="file" name="transkrip" accept="application/pdf,image/*">
            <small id="transkripName">Belum ada file dipilih</small>
            <div id="pdf-icon-transkrip" style="display:none;font-size:40px;margin-top:10px;">📄</div>
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
        <textarea id="keterampilan" name="keterampilan" placeholder="Contoh: PHP, MySQL, JavaScript, Desain Grafis"
          required></textarea>
      </div>
      <input type="hidden" name="lowongan_id" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
      <!-- Tombol Kirim -->
      <button type="submit" class="btn-submit">KIRIM LAMARAN</button>
    </form>
  </main>
  <!-- Footer -->
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
  function setupFileInput(inputId, previewId, nameSpanId) {
    const input = document.querySelector(`input[name="${inputId}"]`);
    const preview = document.getElementById(previewId);
    const nameSpan = document.getElementById(nameSpanId);
    const pdfIcon = document.getElementById(`pdf-icon-${inputId}`);
    const uploadItem = input.closest('.upload-item');

    input.addEventListener('change', function(e) {
      if (this.files && this.files[0]) {
        nameSpan.textContent = this.files[0].name;
        uploadItem.classList.add('has-file');

        if (this.files[0].type === 'application/pdf') {
          if (pdfIcon) pdfIcon.style.display = 'block';
          preview.hidden = true;
        } else if (this.files[0].type.startsWith('image/')) {
          if (pdfIcon) pdfIcon.style.display = 'none';
          const reader = new FileReader();
          reader.onload = function(e) {
            preview.src = e.target.result;
            preview.hidden = false;
          }
          reader.readAsDataURL(this.files[0]);
        } else {
          if (pdfIcon) pdfIcon.style.display = 'none';
          preview.hidden = true;
        }
      } else {
        nameSpan.textContent = "Belum ada file dipilih";
        uploadItem.classList.remove('has-file');
        if (pdfIcon) pdfIcon.style.display = 'none';
        preview.hidden = true;
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    setupFileInput('cv', 'prev-cv', 'cvName');
    setupFileInput('ijazah', 'prev-ijazah', 'ijazahName');
    setupFileInput('transkrip', 'prev-transkrip', 'transkripName');
  });
</script>