<?= view('partials/app_start', [
    'title' => 'Kelola Akun',
    'activeNav' => 'akun',
]) ?>

<div class="page-toolbar">
    <div class="page-toolbar-title">
        <h2>Akun</h2>
        <p>Kelola akun login admin dan guru.</p>
    </div>
    <a class="btn btn-primary" href="<?= base_url('admin/akun/tambah') ?>">Tambah Akun</a>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<section class="panel">
    <div class="table-wrap">
        <table class="data-table compact">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($akun)): ?>
                    <?php foreach ($akun as $row): ?>
                        <tr>
                            <td><?= esc(ucfirst((string) ($row['role'] ?? '-'))) ?></td>
                            <td><?= esc((string) ($row['id'] ?? '-')) ?></td>
                            <td><?= esc((string) ($row['display_name'] ?? '-')) ?></td>
                            <td><?= esc((string) ($row['username'] ?? '-')) ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="<?= base_url('admin/akun/edit/' . $row['role'] . '/' . $row['id']) ?>">Edit</a>
                                        <?php if (($row['role'] ?? '') === 'guru'): ?>
                                            <a class="danger" href="javascript:void(0)" onclick="return hapusGuru('form-hapus-guru-<?= (int) $row['id'] ?>')">Hapus</a>
                                            <form id="form-hapus-guru-<?= (int) $row['id'] ?>" method="post" action="<?= base_url('admin/akun/hapus/guru/' . $row['id']) ?>" style="display:none;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="delete_guru_data" value="">
                                            </form>
                                        <?php else: ?>
                                            <a class="danger" href="javascript:void(0)" onclick="return hapusAdmin('form-hapus-admin-<?= (int) $row['id'] ?>')">Hapus</a>
                                            <form id="form-hapus-admin-<?= (int) $row['id'] ?>" method="post" action="<?= base_url('admin/akun/hapus/admin/' . $row['id']) ?>" style="display:none;">
                                                <?= csrf_field() ?>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Belum ada akun.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
function hapusGuru(formId) {
    const choice = window.prompt('Hapus data guru juga? ketik "yes" untuk hapus data guru, ketik "no" untuk hapus akun saja.');
    if (choice !== 'yes' && choice !== 'no') {
        alert('Wajib pilih "yes" atau "no".');
        return false;
    }
    const ok = confirm(choice === 'yes'
        ? 'Akun dan data guru akan dihapus. Lanjutkan?'
        : 'Akun login guru akan dihapus, data guru tetap ada. Lanjutkan?');
    if (ok) {
        const form = document.getElementById(formId);
        form.querySelector('input[name="delete_guru_data"]').value = choice;
        form.submit();
    }
    return false;
}

function hapusAdmin(formId) {
    if (confirm('Yakin hapus akun admin ini?')) {
        document.getElementById(formId).submit();
    }
    return false;
}
</script>
<?= view('partials/app_end') ?>
