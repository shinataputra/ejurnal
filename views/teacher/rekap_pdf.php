<?php
// views/teacher/rekap_pdf.php
$schoolName = isset($settingsModel)
    ? $settingsModel->get('school_name')
    : 'SMKN 1 Probolinggo';
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Jurnal Mengajar <?= htmlspecialchars($month_display_local ?? '') ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        /* === HALAMAN === */
        @page {
            size: A4 portrait;
            margin: 2cm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            width: 100%;
        }

        /* === KOP SURAT === */
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat .sekolah {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-surat .alamat {
            font-size: 10px;
            margin-top: 3px;
        }

        /* === JUDUL === */
        .judul {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        /* === INFO GURU === */
        .info-guru {
            font-size: 11px;
            margin-bottom: 15px;
        }

        .info-guru div {
            margin-bottom: 4px;
        }

        .info-label {
            display: inline-block;
            width: 130px;
            font-weight: bold;
        }

        /* === TABEL === */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 10px;
        }

        thead {
            background-color: #e0e0e0;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 4px;
            vertical-align: top;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        td.center {
            text-align: center;
        }

        .materi-cell {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .catatan-cell {
            font-size: 9px;
            font-style: italic;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            margin-top: 20px;
        }

        /* === TTD === */
        .footer {
            margin-top: 35px;
            text-align: right;
            page-break-inside: avoid;
        }

        .ttd-box {
            display: inline-block;
            text-align: center;
            min-width: 250px;
        }

        .ttd-box .tanggal {
            font-size: 10px;
            margin-bottom: 25px;
        }

        .ttd-box .jabatan {
            font-size: 9px;
            margin-bottom: 40px;
        }

        .ttd-box .nama {
            font-size: 10px;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- KOP -->
        <div class="kop-surat">
            <div class="sekolah"><?= htmlspecialchars($schoolName) ?></div>
            <div class="alamat">
                Jl. Mayjen Panjaitan No. 106, Probolinggo, Jawa Timur 67214
            </div>
        </div>

        <!-- JUDUL -->
        <div class="judul">
            Rekap Jurnal Mengajar Bulan <?= htmlspecialchars($month_display_local ?? '') ?>
        </div>

        <!-- INFO -->
        <div class="info-guru">
            <div><span class="info-label">Nama Guru</span>: <?= htmlspecialchars($current_user_local['name'] ?? '-') ?></div>
            <div><span class="info-label">NIP</span>: <?= htmlspecialchars($current_user_local['nip'] ?? '-') ?></div>
            <div><span class="info-label">Periode</span>: <?= htmlspecialchars($month_display_local ?? '-') ?></div>
            <div><span class="info-label">Tanggal Cetak</span>: <?= date('d F Y') ?></div>
        </div>

        <?php if (empty($journals_local)): ?>
            <div class="no-data">
                Tidak terdapat jurnal mengajar pada periode <?= htmlspecialchars($month_display_local ?? '') ?>
            </div>
        <?php else: ?>

            <!-- TABEL -->
            <table>
                <thead>
                    <tr>
                        <th style="width:4%">No</th>
                        <th style="width:13%">Tanggal</th>
                        <th style="width:12%">Kelas</th>
                        <th style="width:12%">Mapel</th>
                        <th style="width:7%">Jam</th>
                        <th style="width:28%">Materi</th>
                        <th style="width:24%">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($journals_local as $j): ?>
                        <tr>
                            <td class="center"><?= $i++ ?></td>
                            <td class="center"><?= date('d/m/Y', strtotime($j['date'])) ?></td>
                            <td><?= htmlspecialchars(str_replace('-', ' ', $j['class_name'])) ?></td>
                            <td><?= htmlspecialchars($j['subject_name']) ?></td>
                            <td class="center"><?= htmlspecialchars($j['jam_ke']) ?></td>
                            <td class="materi-cell"><?= htmlspecialchars($j['materi'] ?? '-') ?></td>
                            <td class="catatan-cell"><?= htmlspecialchars($j['notes'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- TTD -->
            <div class="footer">
                <?php
                $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tgl = date('d') . ' ' . $bulan[date('n') - 1] . ' ' . date('Y');
                ?>
                <div class="ttd-box">
                    <div class="tanggal">Probolinggo, <?= $tgl ?></div>
                    <div class="jabatan">Guru Pengajar</div>
                    <div class="nama"><?= htmlspecialchars($current_user_local['name'] ?? '') ?></div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</body>

</html>