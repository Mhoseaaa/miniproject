<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/miniproject/styles/navbar.css?v=<?= time(); ?>">
</head>
<style>
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
</style>

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
                <li><a href="index.php" class="nav-item active">Beranda</a></li>
            </ul>
        </div>
    </nav>
</div>


    
</body>
</html>
