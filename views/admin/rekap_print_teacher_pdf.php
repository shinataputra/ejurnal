<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #f0f0f0;
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h2>REKAP JURNAL MENGAJAR</h2>
    <div class="title">
        <p style="margin: 0; font-size: 14px;">
            <strong><?= htmlspecialchars($user['name']) ?></strong><br>
            <?= htmlspecialchars($user['username']) ?><br>
            <?= htmlspecialchars($month_display) ?>
        </p>
    </div>

    <?php if (!empty($journals)): ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 15%;">Kelas</th>
                    <th style="width: 18%;">Mata Pelajaran</th>
                    <th style="width: 8%;">Jam</th>
                    <th style="width: 25%;">Materi</th>
                    <th style="width: 12%;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($journals as $j): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($j['date'])) ?></td>
                        <td><?= htmlspecialchars($j['class_name']) ?></td>
                        <td><?= htmlspecialchars($j['subject_name']) ?></td>
                        <td style="text-align: center;"><?= $j['jam_ke'] ?></td>
                        <td><?= htmlspecialchars(substr($j['materi'], 0, 80)) ?></td>
                        <td style="font-size: 11px;"><?= htmlspecialchars(substr($j['notes'] ?? '', 0, 50)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; color: #666;">Tidak ada data jurnal untuk periode ini.</p>
    <?php endif; ?>

    <div class="footer">
        Dicetak pada: <?= date('d/m/Y H:i:s') ?>
    </div>
</body>

</html>