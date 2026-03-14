<!DOCTYPE html>
<html>
<head>
    <title>Login - SmartPresence</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('login.css') ?>">
</head>
<body>

<div class="overlay"></div>

<div class="login-container">

    <div class="login-card">
        <h2>Welcome Back</h2>
        <p class="subtitle">Masuk untuk melanjutkan ke sistem presensi</p>

        <!-- ERROR MESSAGE -->
        <?php if(session()->getFlashdata('error')): ?>
            <p style="color:red; margin-bottom:15px;">
                <?= session()->getFlashdata('error') ?>
            </p>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">

            <!-- EMAIL -->
            <div class="input-box">
                <input type="text" name="username" required>
                <label>Email</label>
            </div>

            <!-- PASSWORD -->
            <div class="input-box">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>

            <!-- CAPTCHA INPUT -->
            <div class="input-box">
                <input type="text" name="captcha" required>
                <label>Masukkan Captcha</label>
            </div>

            <!-- CAPTCHA NUMBER -->
            <div style="
                margin-bottom:15px;
                font-size:22px;
                font-weight:bold;
                letter-spacing:4px;
                background:#f3f3f3;
                padding:8px;
                border-radius:6px;
                text-align:center;
            ">
                <?= $captcha ?>
            </div>

            <!-- LOGIN BUTTON -->
            <button type="submit" class="btn-login">
                Sign In
            </button>

            <!-- EXTRA -->
            <div class="extra">
                <a href="#">Forgot password?</a>
            </div>

            <!-- REGISTER -->
            <div class="register">
                Belum punya akun?
                <a href="<?= base_url('register') ?>">Register</a>
            </div>

        </form>

    </div>

</div>

</body>
</html>