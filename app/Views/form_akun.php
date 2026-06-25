<?= view('partials/app_start', [
    'title' => (string) ($title ?? 'Form Akun'),
    'activeNav' => 'akun',
]) ?>

<section class="panel form-card">
    <h3><?= esc($title ?? 'Form Akun') ?></h3>
    <form action="<?= esc($action) ?>" method="post" class="form-grid">
        <?php
        $currentRole = (string) old('role', (string) ($akun['role'] ?? 'admin'));
        $isEditing = $akun !== null;
        ?>
        <div class="field">
            <label for="role">Role</label>
            <select id="role" name="role" <?= $isEditing ? 'disabled' : '' ?>>
                <option value="admin" <?= $currentRole === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="guru" <?= $currentRole === 'guru' ? 'selected' : '' ?>>Guru</option>
            </select>
            <?php if ($isEditing): ?>
                <input type="hidden" name="role" value="<?= esc($currentRole) ?>">
            <?php endif; ?>
        </div>

        <div class="field" id="namaField" <?= $currentRole === 'guru' ? '' : 'hidden' ?>>
            <label for="nama">Nama Guru</label>
            <input id="nama" type="text" name="nama" value="<?= esc(old('nama', $akun['nama'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" value="<?= esc(old('username', $akun['username'] ?? '')) ?>" required>
        </div>

        <div class="field">
            <label for="password">Password <?= $akun ? '(Kosongkan jika tidak diubah)' : '' ?></label>
            <input id="password" type="password" name="password" <?= $akun ? '' : 'required' ?>>
            <div id="password-strength" style="margin-top:6px; font-size:13px; font-weight:700; display:none;"></div>
            <div id="password-bar" style="height:4px; border-radius:4px; margin-top:4px; background:#e5e7eb; display:none;">
                <div id="password-bar-fill" style="height:100%; border-radius:4px; width:0%; transition:width 0.3s, background 0.3s;"></div>
            </div>
            <div id="password-hint" style="margin-top:6px; font-size:12px; color:#607276; display:none;">
                Gunakan minimal 8 karakter, huruf besar, angka, dan karakter spesial.
            </div>
        </div>

        <div class="btn-group">
            <button class="btn btn-primary" id="submit-btn" type="submit">Simpan</button>
            <a class="btn btn-muted" href="<?= base_url('admin/akun') ?>">Kembali</a>
        </div>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const roleEl = document.getElementById('role');
    const namaField = document.getElementById('namaField');
    const namaInput = document.getElementById('nama');
    const passwordInput = document.getElementById('password');
    const strengthText = document.getElementById('password-strength');
    const bar = document.getElementById('password-bar');
    const barFill = document.getElementById('password-bar-fill');
    const hint = document.getElementById('password-hint');
    const submitBtn = document.getElementById('submit-btn');
    const isEditing = <?= $isEditing ? 'true' : 'false' ?>;

    if (roleEl && namaField && namaInput) {
        const syncRole = () => {
            const isGuru = roleEl.value === 'guru';
            namaField.hidden = !isGuru;
            namaInput.required = isGuru;
            if (!isGuru) namaInput.value = '';
        };
        roleEl.addEventListener('change', syncRole);
        syncRole();
    }

    const checkStrength = (val) => {
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        return score;
    };

    passwordInput.addEventListener('input', () => {
        const val = passwordInput.value;

        if (!val) {
            bar.style.display = 'none';
            strengthText.style.display = 'none';
            hint.style.display = 'none';
            submitBtn.disabled = false;
            return;
        }

        bar.style.display = 'block';
        strengthText.style.display = 'block';

        const score = checkStrength(val);
        const levels = [
            { label: 'Sangat Lemah', color: '#b42318', width: '25%' },
            { label: 'Lemah',        color: '#d97706', width: '50%' },
            { label: 'Cukup',        color: '#ca8a04', width: '75%' },
            { label: 'Kuat',         color: '#157347', width: '100%' },
        ];
        const level = levels[score - 1] ?? levels[0];

        barFill.style.width = level.width;
        barFill.style.background = level.color;
        strengthText.style.color = level.color;
        strengthText.textContent = 'Password: ' + level.label;

        const isWeak = score < 3;
        submitBtn.disabled = isWeak;

        hint.style.display = isWeak ? 'block' : 'none';
    });
});
</script>

<?= view('partials/app_end') ?>