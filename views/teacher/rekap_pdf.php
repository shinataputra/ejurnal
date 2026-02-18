<?php
// views/teacher/rekap_pdf.php
// Minimal, self-contained HTML for dompdf PDF rendering
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Jurnal <?= htmlspecialchars($month_display_local ?? '') ?></title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #222;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
        }

        .title {
            font-size: 16px;
            font-weight: 700;
        }

        .meta {
            font-size: 11px;
            color: #444;
        }

        .summary {
            display: flex;
            gap: 10px;
            margin: 12px 0 20px 0;
        }

        .box {
            flex: 1;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
            font-weight: 700;
        }

        @page {
            margin: 15mm;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">REKAP JURNAL MENGAJAR</div>
        <div class="meta"><?= htmlspecialchars($current_user_local['name'] ?? 'Guru') ?> — Periode <?= htmlspecialchars($month_display_local ?? '') ?></div>
    </div>

    <?php if (empty($journals_local)): ?>
        <p>Tidak ada jurnal pada periode <?= htmlspecialchars($month_display_local ?? '') ?></p>
    <?php else: ?>
        <div class="summary">
            <div class="box">
                <div style="font-size:18px;font-weight:700"><?= count($journals_local) ?></div>
                <div>Total Jurnal</div>
            </div>
            <?php $unique_dates = array_unique(array_map(fn($j) => $j['date'], $journals_local)); ?>
            <div class="box">
                <div style="font-size:18px;font-weight:700"><?= count($unique_dates) ?></div>
                <div>Total Hari</div>
            </div>
            <div class="box">
                <div style="font-size:18px;font-weight:700"><?= (count($unique_dates) > 0 ? round(count($journals_local) / count($unique_dates), 1) : 0) ?></div>
                <div>Rata-rata</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:16%">Tanggal</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th style="width:10%">Jam Ke</th>
                    <th>Materi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($journals_local as $j): ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= date('d/m/Y', strtotime($j['date'])) ?></td>
                        <td><?= htmlspecialchars(str_replace('-', ' ', $j['class_name'])) ?></td>
                        <td><?= htmlspecialchars($j['subject_name']) ?></td>
                        <td style="text-align:center"><?= htmlspecialchars($j['jam_ke']) ?></td>
                        <td><?= htmlspecialchars($j['materi']) ?></td>
                    </tr>
                <?php $i++;
                endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>