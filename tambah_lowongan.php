<?php
// === Koneksi ke Database ===
include 'koneksi.php';

// === Fungsi Format Rupiah ===
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// === Proses Form Submit ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil Data dari Form
    $judul         = $_POST['judul'];
    $perusahaan    = $_POST['perusahaan'];
    $kategori      = $_POST['kategori'];
    $lokasi        = $_POST['lokasi'];
    $gaji_min      = (int) str_replace('.', '', $_POST['gaji_min']);
    $gaji_max      = (int) str_replace('.', '', $_POST['gaji_max']);
    $tipe          = $_POST['tipe'];
    $deskripsi     = $_POST['deskripsi'];
    $kualifikasi   = $_POST['kualifikasi'];
    $batas_lamaran = $_POST['batas_lamaran'];

    $slug       = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul), '-'));
    $created_at = date("Y-m-d H:i:s");
    $gaji       = formatRupiah($gaji_min) . " - " . formatRupiah($gaji_max) . " per month";

    // === Upload Logo ===
    $upload_folder_rel = "assets/logo_perusahaan/";
    $upload_folder_abs = __DIR__ . '/' . $upload_folder_rel;

    if (!is_dir($upload_folder_abs)) {
        mkdir($upload_folder_abs, 0777, true);
    }

    $logo_file = basename($_FILES["logo"]["name"]);
    $logo_name = time() . "-" . preg_replace('/\s+/', '-', $logo_file);
    $logo_path_rel = $upload_folder_rel . $logo_name;
    $logo_path_abs = $upload_folder_abs . $logo_name;

    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_path_abs)) {
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO lowongan (judul, perusahaan, kategori, lokasi, gaji, tipe, deskripsi, kualifikasi, batas_lamaran, slug, logo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", $judul, $perusahaan, $kategori, $lokasi, $gaji, $tipe, $deskripsi, $kualifikasi, $batas_lamaran, $slug, $logo_path_rel, $created_at);
        $stmt->execute();

        echo "<p style='color:green;'>Lowongan berhasil ditambahkan.</p>";
    } else {
        echo "<p style='color:red;'>Gagal mengunggah logo.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Lowongan</title>
    <link rel="stylesheet" href="/miniproject/styles/regis.css?v=<?= time(); ?>">
    <script>
        // Format input rupiah otomatis
        function formatRupiahInput(el) {
            el.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                value = new Intl.NumberFormat('id-ID').format(value);
                this.value = value;
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            formatRupiahInput(document.getElementById('gaji_min'));
            formatRupiahInput(document.getElementById('gaji_max'));
        });
    </script>
</head>
<body>
    <div class="container">
    <h2>Upload Lowongan Baru</h2>
    <form method="POST" enctype="multipart/form-data" onsubmit="prepareGaji()">
        <label>Judul:</label><br>
        <input type="text" name="judul" required><br><br>

        <label>Perusahaan:</label><br>
        <input type="text" name="perusahaan" required><br><br>

        <label>Kategori:</label><br>
        <select name="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="IT">IT</option>
            <option value="Desain">Desain</option>
            <option value="Ritel">Ritel</option>
            <option value="Food & Beverage">Food & Beverage</option>
            <option value="Pendidikan">Pendidikan</option>
            <option value="Kesehatan">Kesehatan</option>
            <option value="Keuangan">Keuangan</option>
            <option value="Marketing">Marketing</option>
            <option value="Teknik">Teknik</option>
            <option value="Manufaktur">Manufaktur</option>
            <option value="Transportasi">Transportasi</option>
            <option value="Administrasi">Administrasi</option>
            <option value="Hukum">Hukum</option>
        </select><br><br>

        <label>Lokasi:</label><br>
        <input type="text" name="lokasi" required><br><br>

        <label>Gaji Minimum:</label><br>
        <input type="text" id="gaji_min" name="gaji_min" placeholder="Contoh: 1.700.000" required><br><br>

        <label>Gaji Maksimum:</label><br>
        <input type="text" id="gaji_max" name="gaji_max" placeholder="Contoh: 3.000.000" required><br><br>

        <label>Tipe:</label><br>
        <select name="tipe" required>
            <option value="Full-time">Full-time</option>
            <option value="Part-time">Part-time</option>
            <option value="Remote">Remote</option>
            <option value="Freelance">Freelance</option>
        </select><br><br>

        <label>Deskripsi Pekerjaan:</label><br>
        <textarea name="deskripsi" rows="5" cols="50" required></textarea><br><br>

        <label>Kualifikasi:</label><br>
        <textarea name="kualifikasi" rows="5" cols="50" required></textarea><br><br>

        <label>Batas Lamaran:</label><br>
        <input type="date" name="batas_lamaran" required><br><br>
        
        <label>Upload Logo:</label><br>
        <input type="file" name="logo" accept="image/*" required><br><br>

        <button type="submit">Upload Lowongan</button>
    </form>

    <script>
        function prepareGaji() {
            const min = document.getElementById('gaji_min');
            const max = document.getElementById('gaji_max');
            min.value = min.value.replace(/\./g, '');
            max.value = max.value.replace(/\./g, '');
        }
    </script>
    </div>
</body>
</html>
