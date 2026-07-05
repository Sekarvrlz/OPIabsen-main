<?php
$dateColumns = is_array($dateColumns ?? null) ? $dateColumns : [];
$matrixRows = is_array($matrixRows ?? null) ? $matrixRows : [];
$summaryTotals = is_array($summaryTotals ?? null) ? $summaryTotals : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Presensi</title>
    <link rel="stylesheet" href="<?= base_url('app-theme.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/print-presensi.css') ?>">
</head>
<body class="print-page">
    <main class="print-shell">
        <div class="print-actions noprint">
            <button class="btn btn-primary" type="button" onclick="window.print()">Print</button>
        </div>

        <div class="print-kop">
            <img class="print-logo" src="<?= base_url('assets/logo-sekolah.png') ?>" alt="Logo sekolah">
            <div class="print-kop-text">
                <p class="print-brand"><?= esc($namaSekolah ?? 'SMP Muhammadiyah 1 Pringsewu') ?></p>
                <p class="print-subbrand"><?= esc($statusAkreditasi ?? 'Terakreditasi A') ?> &bull; NPSN <?= esc($npsn ?? '10804837') ?> &bull; NSS <?= esc($nss ?? '202120600950') ?></p>
                <p class="print-address"><?= esc($alamatSekolah ?? 'Jl. Pirngadi No. 56 Pringsewu, Kab. Pringsewu') ?></p>
            </div>
        </div>
        <div class="print-kop-line-thick"></div>
        <div class="print-kop-line-thin"></div>

        <section class="print-heading">
            <h2>Daftar Hadir Peserta Didik</h2>
            <div class="print-heading-meta">
                <div class="print-heading-meta-inner">
                    <div class="print-meta-row">
                        <span class="print-meta-label">Periode</span><span class="print-meta-colon">:</span><span class="print-meta-value"><?= esc($mulai) ?> s.d. <?= esc($akhir) ?></span>
                    </div>
                    <div class="print-meta-row">
                        <span class="print-meta-label">Kelas</span><span class="print-meta-colon">:</span><span class="print-meta-value"><?= esc($kelasFilter !== '' ? $kelasFilter : 'Semua') ?></span>
                    </div>
                    <?php if (! empty($shiftStatusFilter ?? [])): ?>
                        <div class="print-meta-row">
                            <span class="print-meta-label">Jadwal/Waktu</span><span class="print-meta-colon">:</span><span class="print-meta-value"><?= esc(implode(', ', array_map(static fn ($status) => (string) (($shiftStatusOptions ?? [])[$status] ?? $status), $shiftStatusFilter))) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="print-meta-row">
                        <span class="print-meta-label">Legenda</span><span class="print-meta-colon">:</span><span class="print-meta-value">H = Hadir, I = Izin, S = Sakit, A = Alpa, - = Belum ada data</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="table-wrap">
            <?php $dateColCount = max(count($dateColumns), 1); $dateColWidthPct = round(33 / $dateColCount, 3); ?>
            <table class="data-table attendance-matrix print-matrix">
                <colgroup>
                    <col style="width: 4%">
                    <col style="width: 10%">
                    <col style="width: 26%">
                    <col style="width: 7%">
                    <?php for ($i = 0; $i < $dateColCount; $i++): ?>
                        <col style="width: <?= $dateColWidthPct ?>%">
                    <?php endfor; ?>
                    <col style="width: 5%">
                    <col style="width: 5%">
                    <col style="width: 5%">
                    <col style="width: 5%">
                </colgroup>
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">No Induk</th>
                        <th rowspan="2">Nama Siswa</th>
                        <th rowspan="2">Kelas</th>
                        <th colspan="<?= max(1, count($dateColumns)) ?>">Tanggal</th>
                        <th colspan="4">Rekap</th>
                    </tr>
                    <tr>
                        <?php if ($dateColumns !== []): ?>
                            <?php foreach ($dateColumns as $column): ?>
                                <th class="matrix-date" title="<?= esc((string) ($column['date'] ?? '')) ?>"><?= esc((string) ($column['day'] ?? '')) ?></th>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <th class="matrix-date">-</th>
                        <?php endif; ?>
                        <th class="matrix-total">H</th>
                        <th class="matrix-total">I</th>
                        <th class="matrix-total">S</th>
                        <th class="matrix-total">A</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($matrixRows)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($matrixRows as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc((string) ($row['no_induk'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nama_siswa'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['kelas'] ?? '-')) ?></td>
                                <?php if ($dateColumns !== []): ?>
                                    <?php foreach ($dateColumns as $column): ?>
                                        <?php
                                        $date = (string) ($column['date'] ?? '');
                                        $code = (string) (($row['cells'] ?? [])[$date] ?? '-');
                                        ?>
                                        <td class="matrix-cell"><?= esc($code) ?></td>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <td class="matrix-cell">-</td>
                                <?php endif; ?>
                                <td class="matrix-total"><?= esc((string) (($row['summary'] ?? [])['H'] ?? 0)) ?></td>
                                <td class="matrix-total"><?= esc((string) (($row['summary'] ?? [])['I'] ?? 0)) ?></td>
                                <td class="matrix-total"><?= esc((string) (($row['summary'] ?? [])['S'] ?? 0)) ?></td>
                                <td class="matrix-total"><?= esc((string) (($row['summary'] ?? [])['A'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= 8 + count($dateColumns) ?>">Tidak ada data pada periode ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if (! empty($matrixRows)): ?>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total</th>
                            <?php if ($dateColumns !== []): ?>
                                <?php foreach ($dateColumns as $column): ?>
                                    <th class="matrix-cell">-</th>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <th class="matrix-cell">-</th>
                            <?php endif; ?>
                            <th class="matrix-total"><?= esc((string) ($summaryTotals['H'] ?? 0)) ?></th>
                            <th class="matrix-total"><?= esc((string) ($summaryTotals['I'] ?? 0)) ?></th>
                            <th class="matrix-total"><?= esc((string) ($summaryTotals['S'] ?? 0)) ?></th>
                            <th class="matrix-total"><?= esc((string) ($summaryTotals['A'] ?? 0)) ?></th>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>

        <section class="print-signature">
            <div class="print-signature-block">
                <p><?= esc($tempatCetak ?? 'Pringsewu') ?>, <?= esc($tanggalCetak ?? date('d F Y')) ?></p>
                <p>Mengetahui,<br>Kepala Sekolah</p>
                <div class="print-signature-space">&nbsp;</div>
                <p class="print-signature-name">
                    <strong><?= esc($namaKepalaSekolah ?? 'ANTON HENDRO WIJOYO, S.Kom') ?></strong><br>
                    NBM. <?= esc($nbmKepalaSekolah ?? '862 883') ?>
                </p>
            </div>
        </section>
        <!--
            Catatan: .print-signature memakai "page-break-inside: avoid" (dan
            "break-inside: avoid-page" untuk browser modern) sehingga kalau
            blok tanda tangan ini tidak cukup ruang di halaman terakhir tabel,
            browser akan otomatis mendorongnya utuh ke halaman berikutnya
            alih-alih memotongnya di tengah saat dicetak.
        -->
    
    </main>

    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>