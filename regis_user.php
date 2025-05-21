<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email        = $_POST['email'];
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $no_telepon   = $_POST['no_telepon'];
    $alamat       = $_POST['alamat'];
    $created_at   = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO user_pelamar (nama_lengkap, email, password, no_telepon, alamat, created_at) VALUES (?, ?, ?, ?, ?, ?)");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link rel="stylesheet" href="/miniproject/styles/regis.css">
</head>

<body>


<?php include 'navbar.php' ?>

    <div class="container">
        <h2>Form Registrasi</h2>
        <form action='proses_registrasi.php' method='post'>

            <label for="nama">Nama Lengkap : </label>
            <input type="text" id='nama' name="nama_lengkap" required><br><br>

            <label for="email">Email : </label>
            <input type="email" id='email' name="email" required><br><br>

            <label for="password">Password : </label>
            <input type="password" id='password' name="password" required><br><br>

            <label>No. Telepon</label>
            <input type="text" name="no_telepon" required>

            <label>Alamat</label>
            <textarea name="alamat" required></textarea>

            <button type="submit">Daftar</button>

            <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
        </form>
    </div>
</body>

</html>