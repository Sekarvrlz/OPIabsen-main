<?= view('partials/auth_start', [
    'title' => 'Login - SmartPresence',
    'navTarget' => '',
    'navLabel' => 'Beranda',
]) ?>

<main class="login-shell">
    <section class="login-card">
        <h2>Masuk ke SmartPresence</h2>
        <p class="login-subtitle">Gunakan akun admin atau guru yang sudah didaftarkan.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <div class="field-block">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" value="<?= esc(old('username')) ?>" required>
            </div>

            <div class="field-block">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="field-block">
                    <label for="captcha">Masukkan Captcha</label>
                    <div class="captcha-row">
                        <img id="captchaImg" src="<?= $captchaImage ?>" alt="captcha">
                        <button type="button" id="btnRefreshCaptcha" title="Refresh captcha">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M21 12a9 9 0 1 1-2.64-6.36" />
                                <polyline points="21 3 21 9 15 9" />
                            </svg>
                        </button>
                    </div>
                    <input id="captcha" type="text" name="captcha"
                        maxlength="5" required autocomplete="off"
                        placeholder="Ketik 5 karakter di atas">
                </div>

                <script>
                document.getElementById('btnRefreshCaptcha').addEventListener('click', function () {
                    fetch('<?= base_url('captcha/refresh') ?>')
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('captchaImg').src = data.image;
                            document.getElementById('captcha').value = '';
                        });
                });
                </script>

            <button type="submit" class="auth-btn primary block">Login</button>

            <p class="helper-text">Menu dan laporan otomatis mengikuti role akun yang digunakan.</p>
        </form>
    </section>
</main>

<?= view('partials/auth_end') ?>
