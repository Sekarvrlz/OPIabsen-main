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

        <form action="<?= base_url('login/process') ?>" method="post">

            <div class="input-box">
                <input type="email" name="email" required>
                <label>Email</label>
            </div>

            <div class="input-box">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>

            <button type="submit" class="btn-login">
                Sign In
            </button>

            <div class="extra">
                <a href="#">Forgot password?</a>
            </div>

            <div class="register">
                Belum punya akun?
                <a href="<?= base_url('register') ?>">Register</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>