<?php
$page_title = 'Rekap Jurnal Per Kelas';
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Rekap Jurnal Per Kelas</h1>
        <a href="index.php?p=admin/rekap" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
            ← Kembali
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-6">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <input type="hidden" name="p" value="admin/rekap-by-class">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="class_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($c['id'] == $class_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php
                    $monthNames = [
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember'
                    ];
                    foreach ($monthNames as $m => $name):
                        $selected = ($m == $month) ? 'selected' : '';
                    ?>
                        <option value="<?= $m ?>" <?= $selected ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php foreach ($yearOptions as $y): $selected = ($y == $year) ? 'selected' : ''; ?>
                        <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Weekly Data Display -->
    <?php if (!empty($weeklyData)):
        $selectedClass = null;
        foreach ($classes as $c) {
            if ($c['id'] == $class_id) {
                $selectedClass = $c;
                break;
            }
        }
    ?>
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800">
                <?= htmlspecialchars($selectedClass['name']) ?> - <?= htmlspecialchars($monthNames[$month]) ?> <?= $year ?>
            </h2>
            <a href="index.php?p=admin/rekap-print-class&month_year=<?= $month . '-' . $year ?>&class_id=<?= $class_id ?>&pdf=1" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition shadow-md hover:shadow-lg">
                ⬇️ Download PDF
            </a>
        </div>

        <div class="space-y-6">
            <?php foreach ($weeklyData as $week => $journals): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-blue-600 mb-4">Minggu: <?= $week ?></h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold">Tanggal</th>
                                    <th class="px-3 py-2 text-left font-semibold">Guru</th>
                                    <th class="px-3 py-2 text-left font-semibold">Mata Pelajaran</th>
                                    <th class="px-3 py-2 text-left font-semibold">Jam Ke</th>
                                    <th class="px-3 py-2 text-left font-semibold">Materi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($journals as $j): ?>
                                    <?php
                                    $isBk = !empty($j['target_kegiatan']) || !empty($j['kegiatan_layanan']) || !empty($j['hasil_dicapai']);
                                    $materiDisplay = $isBk
                                        ? 'Sasaran: ' . ($j['target_kegiatan'] ?? '-') . ' | Layanan: ' . ($j['kegiatan_layanan'] ?? '-') . ' | Hasil: ' . ($j['hasil_dicapai'] ?? $j['materi'] ?? '-')
                                        : ($j['materi'] ?? '-');
                                    ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-3 py-2"><?= $j['date_display'] ?></td>
                                        <td class="px-3 py-2"><?= htmlspecialchars($j['teacher_name']) ?></td>
                                        <td class="px-3 py-2"><?= htmlspecialchars($j['subject_name']) ?></td>
                                        <td class="px-3 py-2 text-center"><?= $j['jam_ke'] ?></td>
                                        <td class="px-3 py-2 text-sm"><?= htmlspecialchars(substr((string)$materiDisplay, 0, 80)) . (strlen((string)$materiDisplay) > 80 ? '...' : '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($class_id): ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-gray-700">Tidak ada data jurnal untuk kelas dan periode yang dipilih.</p>
        </div>
    <?php else: ?>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <p class="text-gray-700">Silakan pilih kelas untuk melihat rekap jurnal.</p>
        </div>
    <?php endif; ?>
</div>
