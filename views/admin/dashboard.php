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
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
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

    <!-- Extended Statistics and Lists (improved visual accents, reduced icons) -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-start">
        <!-- Tasks summary (accent left) -->
        <div class="col-span-1 lg:col-span-1 bg-white border rounded-lg p-0 shadow overflow-hidden">
            <div class="flex">
                <div class="w-1 bg-yellow-400"></div>
                <div class="p-4 flex-1">
                    <h3 class="font-semibold text-gray-700">Ringkasan Tugas</h3>
                    <div class="mt-3 flex items-center gap-4">
                        <div>
                            <div class="text-2xl font-bold text-gray-900"><?php echo intval($pending_count ?? 0); ?></div>
                            <div class="text-xs text-gray-500">Menunggu</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-700"><?php echo intval($verified_count ?? 0); ?></div>
                            <div class="text-xs text-gray-500">Terverifikasi</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-600"><?php echo intval($rejected_count ?? 0); ?></div>
                            <div class="text-xs text-gray-500">Ditolak</div>
                        </div>
                    </div>
                    <p class="mt-3 text-sm text-gray-600">Menunjukkan status tugas yang dikumpulkan oleh guru pada periode berjalan.</p>
                </div>
            </div>
        </div>

        <!-- Journals summary (accent left) -->
        <div class="col-span-1 lg:col-span-1 bg-white border rounded-lg p-0 shadow overflow-hidden">
            <div class="flex">
                <div class="w-1 bg-indigo-400"></div>
                <div class="p-4 flex-1">
                    <h3 class="font-semibold text-gray-700">Ringkasan Jurnal</h3>
                    <div class="mt-3 grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-2xl font-bold text-indigo-900"><?php echo intval($total_journals ?? 0); ?></div>
                            <div class="text-xs text-gray-500">Total entri</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-indigo-700"><?php echo intval($journals_this_month ?? 0); ?></div>
                            <div class="text-xs text-gray-500">Bulan ini</div>
                        </div>
                    </div>
                    <h4 class="mt-3 font-semibold text-gray-700">Top Guru (Bulan ini)</h4>
                    <ol class="text-sm mt-2 space-y-1">
                        <?php if (!empty($top_teachers)): foreach ($top_teachers as $t): ?>
                                <li class="flex justify-between"><span><?php echo htmlspecialchars($t['name']); ?></span><span class="text-xs text-gray-600"><?php echo intval($t['total_entries']); ?> entri</span></li>
                            <?php endforeach;
                        else: ?>
                            <li class="text-xs text-gray-500">Belum ada data.</li>
                        <?php endif; ?>
                    </ol>
                    <p class="mt-3 text-sm text-gray-600">Data hanya menampilkan entri pada bulan berjalan (<?php echo date('F Y'); ?>).</p>
                </div>
            </div>
        </div>

        <!-- Recent activity (accent left) -->
        <div class="col-span-1 lg:col-span-1 bg-white border rounded-lg p-0 shadow overflow-hidden">
            <div class="flex">
                <div class="w-1 bg-green-400"></div>
                <div class="p-4 flex-1">
                    <h3 class="font-semibold text-gray-700">Aktivitas Terbaru</h3>
                    <div class="mt-3 text-sm">
                        <p class="font-semibold">Jurnal Terbaru</p>
                        <ul class="mt-2 space-y-1">
                            <?php if (!empty($recent_journals)): foreach ($recent_journals as $rj): ?>
                                    <li class="text-xs flex justify-between"><span><?php echo htmlspecialchars($rj['teacher_name']); ?> — <?php echo htmlspecialchars($rj['class_name']); ?></span><span class="text-gray-600"><?php echo htmlspecialchars($rj['date']); ?></span></li>
                                <?php endforeach;
                            else: ?>
                                <li class="text-xs text-gray-500">Tidak ada jurnal.</li>
                            <?php endif; ?>
                        </ul>

                        <p class="font-semibold mt-3">Tugas Terbaru</p>
                        <ul class="mt-2 space-y-1">
                            <?php if (!empty($recent_tasks)): foreach ($recent_tasks as $rt): ?>
                                    <li class="text-xs flex justify-between"><span><?php echo htmlspecialchars($rt['teacher_name']); ?> — <?php echo htmlspecialchars($rt['class_name']); ?></span><span class="text-gray-600"><?php echo htmlspecialchars($rt['status']); ?></span></li>
                                <?php endforeach;
                            else: ?>
                                <li class="text-xs text-gray-500">Tidak ada tugas.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Per-class recap removed as requested; dashboard cards now adapt to content width -->
</div>