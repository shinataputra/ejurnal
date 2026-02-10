<?php
// views/teacher/rekap_daily.php
?>
<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1 md:mb-2">📊 Rekap Jurnal Harian</h1>
        <p class="text-sm md:text-base text-gray-600">Lihat ringkasan jurnal mengajar per hari</p>
    </div>

    <!-- Print Button -->
    <div class="flex gap-2 justify-between">
        <div></div>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm md:text-base print:hidden flex items-center gap-2">
            🖨️ Cetak
        </button>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">⏳ Filter Tanggal</h2>
        <form method="GET" class="space-y-4">
            <input type="hidden" name="p" value="teacher/rekap">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                    <input
                        type="date"
                        name="start_date"
                        value="<?= htmlspecialchars($start_date) ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hingga Tanggal</label>
                    <input
                        type="date"
                        name="end_date"
                        value="<?= htmlspecialchars($end_date) ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        🔍 Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Box -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-4 md:p-6 shadow-lg">
            <p class="text-sm md:text-base opacity-90">Total Hari</p>
            <p class="text-3xl md:text-4xl font-bold mt-2"><?= count($recap) ?></p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-4 md:p-6 shadow-lg">
            <p class="text-sm md:text-base opacity-90">Total Jurnal</p>
            <p class="text-3xl md:text-4xl font-bold mt-2"><?= array_sum(array_map(fn($r) => $r['total_entries'], $recap)) ?></p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg p-4 md:p-6 shadow-lg">
            <p class="text-sm md:text-base opacity-90">Rata-rata per Hari</p>
            <p class="text-3xl md:text-4xl font-bold mt-2"><?= count($recap) > 0 ? round(array_sum(array_map(fn($r) => $r['total_entries'], $recap)) / count($recap), 1) : 0 ?></p>
        </div>
    </div>

    <!-- Recap Table -->
    <?php if (empty($recap)): ?>
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-12 text-center">
            <div class="bg-gray-100 rounded-full w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 md:mb-4 flex items-center justify-center">
                <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">Tidak ada data</h3>
            <p class="text-xs md:text-sm text-gray-600">Tidak ada jurnal dalam periode yang dipilih</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left text-sm md:text-base font-semibold">📅 Tanggal</th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-center text-sm md:text-base font-semibold">📝 Jurnal</th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left text-sm md:text-base font-semibold">🎓 Kelas</th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left text-sm md:text-base font-semibold">📚 Mapel</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($recap as $index => $r): ?>
                            <tr class="hover:bg-gray-50 transition <?= $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' ?>">
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <span class="text-sm md:text-base font-semibold text-gray-900 block">
                                        <?= date('d F Y', strtotime($r['date'])) ?>
                                    </span>
                                    <span class="text-xs text-gray-500 block mt-1">
                                        <?= date('l', strtotime($r['date'])) ?>
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4 text-center">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-sm md:text-base font-semibold px-3 py-1 rounded-full">
                                        <?= (int)$r['total_entries'] ?> Jurnal
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="text-xs md:text-sm text-gray-700 space-y-1">
                                        <?php
                                        $classes = explode(',', $r['classes']);
                                        foreach (array_slice($classes, 0, 3) as $class):
                                            $classFormatted = str_replace('-', ' ', trim($class));
                                        ?>
                                            <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded mr-1 mb-1">
                                                <?= htmlspecialchars($classFormatted) ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php if (count($classes) > 3): ?>
                                            <span class="text-gray-500 text-xs">+<?= count($classes) - 3 ?> lagi</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="text-xs md:text-sm text-gray-700 space-y-1">
                                        <?php
                                        $subjects = explode(',', $r['subjects']);
                                        foreach (array_slice($subjects, 0, 3) as $subject):
                                        ?>
                                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded mr-1 mb-1">
                                                <?= htmlspecialchars(trim($subject)) ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php if (count($subjects) > 3): ?>
                                            <span class="text-gray-500 text-xs">+<?= count($subjects) - 3 ?> lagi</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Link -->
        <div class="text-center">
            <a href="?p=teacher/rekap-monthly" class="text-blue-600 hover:text-blue-800 font-semibold text-sm md:text-base">
                📊 Lihat Rekap Bulanan →
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
    @media print {
        .print:hidden {
            display: none !important;
        }

        body {
            background: white;
            padding: 0;
            margin: 0;
        }

        .space-y-4 {
            margin-bottom: 1rem;
        }
    }
</style>