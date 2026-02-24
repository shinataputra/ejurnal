<?php
$page_title = 'Rekap Jurnal Per Guru';
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Rekap Jurnal Per Guru</h1>
        <a href="index.php?p=admin/rekap" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
            ← Kembali
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-6">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <input type="hidden" name="p" value="admin/rekap-by-teacher">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Guru</label>
                <select name="teacher_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">-- Pilih Guru --</option>
                    <?php foreach ($teachers as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ($t['id'] == $teacher_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars(($t['role'] ?? '') === 'guru_bk' ? 'Guru BK' : 'Guru') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
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
                <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <?php foreach ($yearOptions as $y): $selected = ($y == $year) ? 'selected' : ''; ?>
                        <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Weekly Data Display -->
    <?php if (!empty($weeklyData)):
        $selectedTeacher = null;
        foreach ($teachers as $t) {
            if ($t['id'] == $teacher_id) {
                $selectedTeacher = $t;
                break;
            }
        }
        $isGuruBk = (($selectedTeacher['role'] ?? '') === 'guru_bk');
    ?>
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800">
                <?= htmlspecialchars($selectedTeacher['name']) ?> - <?= htmlspecialchars($monthNames[$month]) ?> <?= $year ?>
            </h2>
            <a href="index.php?p=admin/rekap-print-teacher&teacher_id=<?= $teacher_id ?>&month_year=<?= $month . '-' . $year ?>&pdf=1" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition shadow-md hover:shadow-lg">
                ⬇️ Download PDF
            </a>
        </div>

        <div class="space-y-6">
            <?php foreach ($weeklyData as $week => $journals): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-green-600 mb-4">Minggu: <?= $week ?></h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold">Tanggal</th>
                                    <th class="px-3 py-2 text-left font-semibold">Kelas</th>
                                    <th class="px-3 py-2 text-left font-semibold">Mata Pelajaran</th>
                                    <th class="px-3 py-2 text-left font-semibold">Jam Ke</th>
                                    <?php if ($isGuruBk): ?>
                                        <th class="px-3 py-2 text-left font-semibold">Sasaran</th>
                                        <th class="px-3 py-2 text-left font-semibold">Layanan</th>
                                        <th class="px-3 py-2 text-left font-semibold">Hasil</th>
                                    <?php else: ?>
                                        <th class="px-3 py-2 text-left font-semibold">Materi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($journals as $j): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-3 py-2"><?= $j['date_display'] ?></td>
                                        <td class="px-3 py-2"><?= htmlspecialchars($j['class_name']) ?></td>
                                        <td class="px-3 py-2"><?= htmlspecialchars($j['subject_name']) ?></td>
                                        <td class="px-3 py-2 text-center"><?= $j['jam_ke'] ?></td>
                                        <?php if ($isGuruBk): ?>
                                            <td class="px-3 py-2 text-sm"><?= htmlspecialchars(substr((string)($j['target_kegiatan'] ?? ''), 0, 40)) ?></td>
                                            <td class="px-3 py-2 text-sm"><?= htmlspecialchars((string)($j['kegiatan_layanan'] ?? '-')) ?></td>
                                            <td class="px-3 py-2 text-sm"><?= htmlspecialchars(substr((string)($j['hasil_dicapai'] ?? $j['materi'] ?? ''), 0, 40)) ?></td>
                                        <?php else: ?>
                                            <td class="px-3 py-2 text-sm"><?= htmlspecialchars(substr($j['materi'], 0, 60)) . (strlen($j['materi']) > 60 ? '...' : '') ?></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($teacher_id): ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-gray-700">Tidak ada data jurnal untuk guru dan periode yang dipilih.</p>
        </div>
    <?php else: ?>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <p class="text-gray-700">Silakan pilih guru untuk melihat rekap jurnal.</p>
        </div>
    <?php endif; ?>
</div>
