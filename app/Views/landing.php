<!DOCTYPE html>
<html>
<head>
    <title>Sistem Presensi Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('landing.css') ?>">
</head>
<body>

<header class="navbar">
    <div class="logo">SmartPresence</div>

    <nav class="center-nav">
        <a href="#">Beranda</a>
        <a href="#">Tentang</a>
        <a href="#">Fitur</a>
        <a href="#">Kontak</a>
    </nav>

    <a href="<?= base_url('login') ?>" class="btn-login">Login</a>
</header>

<section class="hero">

    <div class="overlay"></div>

    <div class="hero-inner">

        <div class="hero-content">
            <h1>
                Sistem Presensi Digital <br>
                Berbasis Two Factor Authentication
            </h1>

            <p>
                Presensi aman dan terintegrasi untuk meningkatkan
                kedisiplinan serta keamanan data siswa dan guru.
            </p>

        </div>

        <div class="info-wrapper">

            <div class="info-card">
                <h3>Langkah Presensi</h3>
                <ul>
                    <li>Login ke sistem</li>
                    <li>Arahkan wajah ke kamera</li>
                    <li>Sistem melakukan verifikasi</li>
                    <li>Presensi tercatat otomatis</li>
                </ul>
            </div>

            <div class="info-card">
                <h3>Keamanan Sistem</h3>
                <ul>
                    <li>Login menggunakan akun terdaftar</li>
                    <li>Verifikasi OTP melalui email</li>
                    <li>Data terenkripsi dalam database</li>
                    <li>Akses dibatasi sesuai role</li>
                </ul>
            </div>

        </div>

    </div>

</section>

</body>
</html>