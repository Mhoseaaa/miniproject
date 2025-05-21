<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email        = $_POST['email'];
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $no_telepon   = $_POST['no_telepon'];
    $alamat       = $_POST['alamat'];
    $created_at   = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO users_pelamar (nama_lengkap, email, password, no_telepon, alamat, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama_lengkap, $email, $password, $no_telepon, $alamat, $created_at);

    if ($stmt->execute()) {
        echo "Registrasi berhasil. <a href='login_pelamar.php'>Masuk di sini</a>";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
