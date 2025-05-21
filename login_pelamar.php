<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link rel="stylesheet" href="/miniproject/styles/login.css?v=<?= time(); ?>">

</head>



<body>



<?php include 'navbar.php' ?>

<div class="login-container">
  <div class="top-link">
    <a href="register_employer.php">Apakah Anda mencari karyawan?</a>
  </div>

    <div class="container">
        <h2>Login</h2>
        <form action='proses_login.php' method='post'>
            <label for="email">Email : </label>
            <input type="email" id='email' name="email" required><br><br>

            <label for="password">Password : </label>
            <input type="password" id='password' name="password" required><br><br>

            <button type="submit">Masuk</button>

            <p>Belum punya akun? <a href="regis_user.php">Daftar</a></p>
        </form>
    </div>
</body>

</html>