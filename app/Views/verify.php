<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi OTP</title>
    <link rel="stylesheet" href="<?= base_url('style.css') ?>">
</head>
<body>

<div class="card">
    <h2>Verifikasi OTP</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <p style="color:red"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="post" action="<?= base_url('verifyProcess') ?>">
        <input type="text" name="otp" placeholder="Masukkan 6 digit OTP" maxlength="6" required>
        <button type="submit">Verifikasi</button>
    </form>
</div>

</body>
</html>