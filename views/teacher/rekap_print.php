<?php
// views/teacher/rekap_print.php
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Jurnal - <?= htmlspecialchars($month_display) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white;
                color: black;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 20mm;
            }

            .page-break {
                page-break-after: always;
            }
        }

        /* Mobile-friendly table: stack cells */
        @media (max-width: 640px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 12px;
                border: 1px solid #e5e7eb;
                padding: 8px;
            }

            td {
                display: block;
                text-align: left;
                padding: 6px 8px;
                border: none;
            }

            td[data-label]:before {
                content: attr(data-label) ": ";
                font-weight: 600;
                display: inline-block;
                width: 110px;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto">
        <!-- Print Header -->
        <div class="no-print bg-white rounded-t-lg shadow p-4 md:p-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">🖨️ Cetak Rekap Jurnal</h1>
                <p class="text-sm text-gray-600">Bulan: <?= htmlspecialchars($month_display) ?></p>
            </div>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 md:px-6 rounded-lg transition flex items-center gap-2">
                🖨️ Cetak
            </button>
        </div>

        <!-- Content for Printing -->
        <div class="bg-white shadow-lg p-6 md:p-10 print:shadow-none print:p-0">
            <!-- Header -->
            <div class="text-center mb-8 pb-6 border-b-2 border-gray-300">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">REKAP JURNAL MENGAJAR</h1>
                <p class="text-lg text-gray-700">
                    <span class="font-semibold"><?= htmlspecialchars($current_user['name'] ?? 'Guru') ?></span>
                    <?php if (!empty($current_user['nip'])): ?>
                        <br><span class="text-sm text-gray-600">NIP: <?= htmlspecialchars($current_user['nip']) ?></span>
                    <?php endif; ?>
                </p>
                <p class="text-base text-gray-600 mt-3">
                    Periode: <span class="font-semibold"><?= htmlspecialchars($month_display) ?></span>
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Cetak: <?= date('d F Y H:i') ?>
                </p>
            </div>

            <?php if (empty($journals)): ?>
                <!-- Empty State -->
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">Tidak ada jurnal pada bulan <?= htmlspecialchars($month_display) ?></p>
                </div>
            <?php else: ?>
                <!-- Summary Box -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="border-2 border-blue-500 rounded p-4 text-center bg-blue-50">
                        <p class="text-sm text-gray-600 font-semibold">TOTAL JURNAL</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2"><?= count($journals) ?></p>
                    </div>
                    <div class="border-2 border-green-500 rounded p-4 text-center bg-green-50">
                        <p class="text-sm text-gray-600 font-semibold">TOTAL HARI</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            <?php
                            $unique_dates = array_unique(array_map(fn($j) => $j['date'], $journals));
                            echo count($unique_dates);
                            ?>
                        </p>
                    </div>
                    <div class="border-2 border-purple-500 rounded p-4 text-center bg-purple-50">
                        <p class="text-sm text-gray-600 font-semibold">RATA-RATA</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">
                            <?php
                            $unique_dates = array_unique(array_map(fn($j) => $j['date'], $journals));
                            echo count($unique_dates) > 0 ? round(count($journals) / count($unique_dates), 1) : 0;
                            ?>
                        </p>
                    </div>
                </div>

                <!-- Detailed Table -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">📚 DAFTAR RINCI JURNAL</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-800 text-white">
                                    <th class="border border-gray-300 px-3 py-3 text-left text-sm font-semibold">NO</th>
                                    <th class="border border-gray-300 px-3 py-3 text-left text-sm font-semibold">TANGGAL</th>
                                    <th class="border border-gray-300 px-3 py-3 text-left text-sm font-semibold">KELAS</th>
                                    <th class="border border-gray-300 px-3 py-3 text-left text-sm font-semibold">MAPEL</th>
                                    <th class="border border-gray-300 px-3 py-3 text-left text-sm font-semibold">JAM KE</th>
                                    <th class="border border-gray-300 px-3 py-3 text-left text-sm font-semibold">MATERI</th>
                                    <th class="border border-gray-300 px-3 py-3 text-left text-sm font-semibold">CATATAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $current_date = null;
                                $row_number = 1;
                                foreach ($journals as $journal):
                                    $is_new_date = ($current_date !== $journal['date']);
                                    if ($is_new_date) {
                                        $current_date = $journal['date'];
                                    }
                                ?>
                                    <tr class="<?= $row_number % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>">
                                        <td class="border border-gray-300 px-3 py-3 text-sm" data-label="NO"><?= $row_number ?></td>
                                        <td class="border border-gray-300 px-3 py-3 text-sm<?= $is_new_date ? ' bg-gray-100 font-semibold' : '' ?>" data-label="TANGGAL">
                                            <?= date('d/m/Y', strtotime($journal['date'])) ?>
                                            <?php if ($is_new_date): ?>
                                                <br><span class="text-xs text-gray-600"><?= date('l', strtotime($journal['date'])) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="border border-gray-300 px-3 py-3 text-sm" data-label="KELAS">
                                            <?= htmlspecialchars(str_replace('-', ' ', $journal['class_name'])) ?>
                                        </td>
                                        <td class="border border-gray-300 px-3 py-3 text-sm" data-label="MAPEL">
                                            <?= htmlspecialchars($journal['subject_name']) ?>
                                        </td>
                                        <td class="border border-gray-300 px-3 py-3 text-sm text-center" data-label="JAM KE">
                                            <?= htmlspecialchars($journal['jam_ke']) ?>
                                        </td>
                                        <td class="border border-gray-300 px-3 py-3 text-sm" data-label="MATERI">
                                            <?= htmlspecialchars($journal['materi']) ?>
                                        </td>
                                        <td class="border border-gray-300 px-3 py-3 text-xs text-gray-700" data-label="CATATAN">
                                            <?= htmlspecialchars($journal['notes'] ?? '-') ?>
                                        </td>
                                    </tr>
                                    <?php $row_number++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Class & Subject Summary -->
                <div class="grid grid-cols-2 gap-6 mb-8 page-break">
                    <!-- Classes -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-purple-600">🎓 DAFTAR KELAS</h3>
                        <div class="space-y-2">
                            <?php
                            $classes = [];
                            foreach ($journals as $j) {
                                $class_name = str_replace('-', ' ', $j['class_name']);
                                if (!in_array($class_name, $classes)) {
                                    $classes[] = $class_name;
                                }
                            }
                            sort($classes);
                            foreach ($classes as $index => $class):
                            ?>
                                <p class="text-sm">
                                    <strong><?= $index + 1 . '.</strong> ' . htmlspecialchars($class) ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Subjects -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-green-600">📚 DAFTAR MAPEL</h3>
                        <div class="space-y-2">
                            <?php
                            $subjects = [];
                            foreach ($journals as $j) {
                                if (!in_array($j['subject_name'], $subjects)) {
                                    $subjects[] = $j['subject_name'];
                                }
                            }
                            sort($subjects);
                            foreach ($subjects as $index => $subject):
                            ?>
                                <p class="text-sm">
                                    <strong><?= $index + 1 . '.</strong> ' . htmlspecialchars($subject) ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t-2 border-gray-300 pt-8 mt-8">
                    <div class="grid grid-cols-2 gap-12">
                        <div>
                            <p class="text-sm text-gray-600 mb-8">Guru Yang Bersangkutan,</p>
                            <p class="text-sm font-semibold"><?= htmlspecialchars($current_user['name'] ?? 'Guru') ?></p>
                            <?php if (!empty($current_user['nip'])): ?>
                                <p class="text-xs text-gray-600">NIP: <?= htmlspecialchars($current_user['nip']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-8">Mengetahui,</p>
                            <p class="text-sm font-semibold">Kepala Sekolah</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Print Footer -->
        <div class="no-print bg-white rounded-b-lg shadow p-4 text-center">
            <p class="text-xs text-gray-600">
                Gunakan Ctrl+P atau klik tombol Cetak untuk mencetak dokumen ini
            </p>
        </div>
    </div>
</body>

</html>