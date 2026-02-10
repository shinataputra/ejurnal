<?php
// views/teacher/rekap_monthly.php
?>
<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1 md:mb-2">📅 Rekap Jurnal Bulanan</h1>
        <p class="text-sm md:text-base text-gray-600">Lihat ringkasan jurnal mengajar per bulan</p>
    </div>

    <!-- Year Filter -->
    <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <input type="hidden" name="p" value="teacher/rekap-monthly">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Tahun</label>
                <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php foreach (array_reverse($yearOptions) as $y): ?>
                        <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition w-full md:w-auto">
                🔍 Lihat
            </button>
        </form>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg p-4 md:p-6 shadow-lg">
            <p class="text-sm md:text-base font-semibold">Total Jurnal Tahun <?= $year ?></p>
            <p class="text-3xl md:text-4xl font-bold mt-2"><?= array_sum(array_map(fn($m) => $m['total_entries'], $monthlyData)) ?></p>
        </div>
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg p-4 md:p-6 shadow-lg">
            <p class="text-sm md:text-base font-semibold drop-shadow">Total Hari Aktivitas</p>
            <p class="text-3xl md:text-4xl font-bold mt-2 drop-shadow"><?= array_sum(array_map(fn($m) => $m['days_filled'], $monthlyData)) ?></p>
        </div>
    </div>

    <!-- Monthly Cards -->
    <?php if (empty($monthlyData)): ?>
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-12 text-center">
            <div class="bg-gray-100 rounded-full w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 md:mb-4 flex items-center justify-center">
                <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">Tidak ada data</h3>
            <p class="text-xs md:text-sm text-gray-600">Tidak ada jurnal pada tahun <?= $year ?></p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($monthlyData as $monthData): ?>
                <?php
                $month_num = $monthData['month'];
                $month_name = $monthNames[$month_num] ?? 'Unknown';
                $month_year = $monthData['month_year'];
                $total = $monthData['total_entries'];
                $days = $monthData['days_filled'];
                $classes = !empty($monthData['classes']) ? explode(',', $monthData['classes']) : [];
                $subjects = !empty($monthData['subjects']) ? explode(',', $monthData['subjects']) : [];
                ?>
                <a href="?p=teacher/rekap-print&month_year=<?= urlencode($month_year) ?>" class="block">
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4 md:p-6 border-t-4 border-t-blue-500 h-full">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg md:text-xl font-bold text-gray-900"><?= htmlspecialchars($month_name) ?></h3>
                                <p class="text-xs md:text-sm text-gray-600"><?= $year ?></p>
                            </div>
                            <span class="text-3xl">📋</span>
                        </div>

                        <div class="space-y-3">
                            <!-- Total Journal -->
                            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded">
                                <span class="text-xl">📝</span>
                                <div>
                                    <p class="text-xs text-gray-600">Total Jurnal</p>
                                    <p class="font-bold text-lg text-gray-900"><?= (int)$total ?></p>
                                </div>
                            </div>

                            <!-- Days Filled -->
                            <div class="flex items-center gap-3 p-3 bg-green-50 rounded">
                                <span class="text-xl">📅</span>
                                <div>
                                    <p class="text-xs text-gray-600">Hari Aktivitas</p>
                                    <p class="font-bold text-lg text-gray-900"><?= (int)$days ?></p>
                                </div>
                            </div>

                            <!-- Classes -->
                            <div class="p-3 bg-purple-50 rounded">
                                <p class="text-xs text-gray-600 font-semibold mb-2">🎓 Kelas (<?= count($classes) ?>)</p>
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach (array_slice($classes, 0, 2) as $class): ?>
                                        <span class="text-xs bg-purple-200 text-purple-900 px-2 py-1 rounded">
                                            <?= htmlspecialchars(str_replace('-', ' ', trim($class))) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (count($classes) > 2): ?>
                                        <span class="text-xs text-gray-600 px-2 py-1">+<?= count($classes) - 2 ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Subjects -->
                            <div class="p-3 bg-green-50 rounded">
                                <p class="text-xs text-gray-600 font-semibold mb-2">📚 Mapel (<?= count($subjects) ?>)</p>
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach (array_slice($subjects, 0, 2) as $subject): ?>
                                        <span class="text-xs bg-green-200 text-green-900 px-2 py-1 rounded">
                                            <?= htmlspecialchars(trim($subject)) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (count($subjects) > 2): ?>
                                        <span class="text-xs text-gray-600 px-2 py-1">+<?= count($subjects) - 2 ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-blue-600 font-semibold flex items-center gap-1 hover:text-blue-700">
                                🖨️ Cetak Bulan Ini →
                            </p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Back to Daily -->
        <div class="text-center pt-4">
            <a href="?p=teacher/rekap" class="text-blue-600 hover:text-blue-800 font-semibold text-sm md:text-base">
                ← Kembali ke Rekap Harian
            </a>
        </div>
    <?php endif; ?>
</div>