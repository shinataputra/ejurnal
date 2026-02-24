<?php
$schoolName = (isset($this) && isset($this->settingsModel))
    ? ($this->settingsModel->get('school_name') ?? 'SMKN 1 Probolinggo')
    : 'SMKN 1 Probolinggo';
$isGuruBk = (($user['role'] ?? '') === 'guru_bk');
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Jurnal Mengajar <?= htmlspecialchars($month_display ?? '') ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 1.5cm 2cm 2cm 2cm;
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

        .judul {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

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

        .footer {
            margin-top: 20px;
            text-align: right;
            page-break-inside: avoid;
        }

        .ttd-box {
            display: inline-block;
            text-align: left;
            min-width: 220px;
        }

        .ttd-box .tanggal {
            font-size: 10px;
            margin-bottom: 6px;
        }

        .ttd-box .jabatan {
            font-size: 10px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .ttd-box .nama {
            font-size: 10px;
            text-decoration: underline;
            margin-bottom: 0px;
            display: block;
        }

        .ttd-box .nip {
            font-size: 10px;
            color: #000;
            margin-top: 0px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="kop-surat">
            <div class="sekolah"><?= htmlspecialchars($schoolName) ?></div>
            <div class="alamat">
                Jalan Mastrip Nomor 357, Telepon (0335) 421121 Probolinggo (67239)<br>
                Laman: smkn1probolinggo.sch.id &nbsp; Pos-el: smkn1_probolinggo@yahoo.co.id
            </div>
        </div>

        <div class="judul">
            Rekap Jurnal <?= $isGuruBk ? 'Layanan BK' : 'Mengajar' ?> Guru
        </div>

        <div class="info-guru">
            <div><span class="info-label">Nama Guru</span>: <?= htmlspecialchars($user['name'] ?? '-') ?></div>
            <div><span class="info-label">NIP</span>: <?= htmlspecialchars($user['nip'] ?? '-') ?></div>
            <div><span class="info-label">Periode</span>: <?= htmlspecialchars($month_display ?? '-') ?></div>
        </div>

        <?php if (empty($journals)): ?>
            <div class="no-data">
                Tidak terdapat jurnal mengajar pada periode <?= htmlspecialchars($month_display ?? '') ?>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th style="width:4%">No</th>
                        <th style="width:11%">Tanggal</th>
                        <th style="width:10%">Kelas</th>
                        <th style="width:7%">Jam</th>
                        <th style="width:16%">Mapel</th>
                        <?php if ($isGuruBk): ?>
                            <th style="width:20%">Sasaran</th>
                            <th style="width:16%">Layanan</th>
                            <th style="width:16%">Hasil</th>
                        <?php else: ?>
                            <th style="width:28%">Materi</th>
                        <?php endif; ?>
                        <th style="width:24%">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($journals as $j): ?>
                        <tr>
                            <td class="center"><?= $i++ ?></td>
                            <td class="center"><?= date('d/m/Y', strtotime($j['date'])) ?></td>
                            <td class="center"><?= htmlspecialchars(str_replace('-', ' ', $j['class_name'] ?? '-')) ?></td>
                            <td class="center"><?= htmlspecialchars((string)($j['jam_ke'] ?? '-')) ?></td>
                            <td><?= htmlspecialchars($j['subject_name'] ?? '-') ?></td>
                            <?php if ($isGuruBk): ?>
                                <td class="materi-cell"><?= htmlspecialchars($j['target_kegiatan'] ?? '-') ?></td>
                                <td class="materi-cell"><?= htmlspecialchars($j['kegiatan_layanan'] ?? '-') ?></td>
                                <td class="materi-cell"><?= htmlspecialchars($j['hasil_dicapai'] ?? $j['materi'] ?? '-') ?></td>
                            <?php else: ?>
                                <td class="materi-cell"><?= htmlspecialchars($j['materi'] ?? '-') ?></td>
                            <?php endif; ?>
                            <td class="catatan-cell"><?= htmlspecialchars($j['notes'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="footer">
                <?php
                $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tgl = date('d') . ' ' . $bulan[date('n') - 1] . ' ' . date('Y');
                ?>
                <div class="ttd-box">
                    <div class="tanggal">Probolinggo, <?= $tgl ?></div>
                    <div class="jabatan">Guru Pengajar</div>
                    <div class="nama"><?= htmlspecialchars($user['name'] ?? '') ?></div>
                    <div class="nip">NIP: <?= htmlspecialchars($user['nip'] ?? '-') ?></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
