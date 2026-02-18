<?php
// views/admin/dashboard.php
?>
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Admin</h1>
        <p class="text-gray-600">Kelola data sekolah dan monitor aktivitas jurnal</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Teachers Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-semibold mb-1">👥 TOTAL GURU</p>
                    <p class="text-3xl font-bold text-blue-900"><?php echo intval($teacher_count ?? 0); ?></p>
                    <p class="text-xs text-blue-600 mt-2">Guru aktif</p>
                </div>
            </div>
        </div>

        <!-- Total Classes Card -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-6 shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-green-600 font-semibold mb-1">🎓 TOTAL KELAS</p>
                    <p class="text-3xl font-bold text-green-900"><?php echo intval($class_count ?? 0); ?></p>
                    <p class="text-xs text-green-600 mt-2">Kelas terdaftar</p>
                </div>
            </div>
        </div>

        <!-- Active Academic Year Card -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6 shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-purple-600 font-semibold mb-1">📅 TAHUN AKTIF</p>
                    <p class="text-2xl font-bold text-purple-900"><?php echo htmlspecialchars($active_year['name'] ?? '-'); ?></p>
                    <p class="text-xs text-purple-600 mt-2"><?php echo isset($active_year['start_date']) ? htmlspecialchars($active_year['start_date']) . ' → ' . htmlspecialchars($active_year['end_date']) : 'Tahun pelajaran'; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Extended Statistics and Lists -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Tasks summary -->
        <div class="col-span-1 lg:col-span-1 bg-white border rounded-lg p-4 shadow">
            <h3 class="font-semibold mb-3">Ringkasan Tugas</h3>
            <ul class="space-y-2 text-sm">
                <li><strong>Menunggu:</strong> <?php echo intval($pending_count ?? 0); ?></li>
                <li><strong>Terverifikasi:</strong> <?php echo intval($verified_count ?? 0); ?></li>
                <li><strong>Ditolak:</strong> <?php echo intval($rejected_count ?? 0); ?></li>
            </ul>
        </div>

        <!-- Journals summary -->
        <div class="col-span-1 lg:col-span-1 bg-white border rounded-lg p-4 shadow">
            <h3 class="font-semibold mb-3">Ringkasan Jurnal</h3>
            <p class="text-sm"><strong>Total entri:</strong> <?php echo intval($total_journals ?? 0); ?></p>
            <p class="text-sm"><strong>Bulan ini:</strong> <?php echo intval($journals_this_month ?? 0); ?></p>
            <h4 class="mt-3 font-semibold">Top Guru (Bulan ini)</h4>
            <ol class="text-sm mt-2 space-y-1">
                <?php if (!empty($top_teachers)): foreach ($top_teachers as $t): ?>
                        <li><?php echo htmlspecialchars($t['name']); ?> — <span class="text-xs text-gray-600"><?php echo intval($t['total_entries']); ?> entri</span></li>
                    <?php endforeach;
                else: ?>
                    <li class="text-xs text-gray-500">Belum ada data.</li>
                <?php endif; ?>
            </ol>
        </div>

        <!-- Recent activity -->
        <div class="col-span-1 lg:col-span-1 bg-white border rounded-lg p-4 shadow">
            <h3 class="font-semibold mb-3">Aktivitas Terbaru</h3>
            <div class="text-sm">
                <p class="font-semibold">Jurnal Terbaru</p>
                <ul class="mt-2 space-y-1">
                    <?php if (!empty($recent_journals)): foreach ($recent_journals as $rj): ?>
                            <li class="text-xs"><strong><?php echo htmlspecialchars($rj['teacher_name']); ?></strong> — <?php echo htmlspecialchars($rj['class_name']); ?> — <?php echo htmlspecialchars($rj['date']); ?></li>
                        <?php endforeach;
                    else: ?>
                        <li class="text-xs text-gray-500">Tidak ada jurnal.</li>
                    <?php endif; ?>
                </ul>

                <p class="font-semibold mt-3">Tugas Terbaru</p>
                <ul class="mt-2 space-y-1">
                    <?php if (!empty($recent_tasks)): foreach ($recent_tasks as $rt): ?>
                            <li class="text-xs"><strong><?php echo htmlspecialchars($rt['teacher_name']); ?></strong> — <?php echo htmlspecialchars($rt['class_name']); ?> — <span class="text-gray-600"><?php echo htmlspecialchars($rt['status']); ?></span></li>
                        <?php endforeach;
                    else: ?>
                        <li class="text-xs text-gray-500">Tidak ada tugas.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Per-class recap table -->
    <div class="mt-6 bg-white border rounded-lg p-4 shadow">
        <h3 class="font-semibold mb-3">Rekap Per Kelas (<?php echo date('F Y'); ?>)</h3>
        <div class="overflow-auto">
            <table class="w-full text-sm table-auto">
                <thead>
                    <tr class="text-left text-xs text-gray-600">
                        <th class="px-2 py-1">Kelas</th>
                        <th class="px-2 py-1">Entri</th>
                        <th class="px-2 py-1">Guru</th>
                        <th class="px-2 py-1">Hari Terisi</th>
                        <th class="px-2 py-1">Mapel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($class_recap)): foreach ($class_recap as $c): ?>
                            <tr>
                                <td class="px-2 py-1"><?php echo htmlspecialchars($c['class_name']); ?></td>
                                <td class="px-2 py-1"><?php echo intval($c['total_entries']); ?></td>
                                <td class="px-2 py-1"><?php echo intval($c['total_teachers']); ?></td>
                                <td class="px-2 py-1"><?php echo intval($c['days_filled']); ?></td>
                                <td class="px-2 py-1"><?php echo htmlspecialchars($c['subjects_covered']); ?></td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td class="px-2 py-1 text-xs text-gray-500" colspan="5">Tidak ada data rekap.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>