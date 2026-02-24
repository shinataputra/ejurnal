<?php
// views/teacher/list_journal.php
$isGuruBk = (($current_user['role'] ?? '') === 'guru_bk');
?>
<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1 md:mb-2">Daftar Jurnal Mengajar</h1>
        <p class="text-sm md:text-base text-gray-600">Review dan kelola semua jurnal Anda</p>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow p-3 md:p-4">
        <form method="GET" action="" class="flex gap-2 items-center">
            <input type="hidden" name="p" value="teacher/list">
            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter Tanggal:</label>
            <input type="date" name="date" value="<?= htmlspecialchars($filter_date) ?>" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            <button type="submit" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm whitespace-nowrap">
                Filter
            </button>
        </form>
    </div>

    <!-- Academic Year Info -->
    <?php if ($activeAY): ?>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 md:p-4 text-sm md:text-base">
            <p class="text-blue-900">
                <span class="font-semibold">Tahun Pelajaran Aktif:</span> <?= htmlspecialchars($activeAY['name']) ?>
                <br>
                <span class="text-xs md:text-sm text-blue-700 block mt-1">Periode: <?= date('d F Y', strtotime($activeAY['start_date'])) ?> - <?= date('d F Y', strtotime($activeAY['end_date'])) ?></span>
            </p>
        </div>
    <?php else: ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 md:p-4">
            <p class="text-xs md:text-sm text-yellow-900">⚠️ Tidak ada tahun pelajaran aktif. Hubungi admin untuk mengatur tahun pelajaran.</p>
        </div>
    <?php endif; ?>

    <!-- Empty State -->
    <?php if (empty($journals)): ?>
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-12 text-center">
            <div class="bg-gray-100 rounded-full w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 md:mb-4 flex items-center justify-center">
                <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">Belum ada jurnal</h3>
            <p class="text-xs md:text-sm text-gray-600">Tidak ada jurnal untuk tanggal yang dipilih. Coba ubah filter tanggal atau tambah jurnal baru.</p>
        </div>
    <?php else: ?>
        <!-- Journal Cards -->
        <div class="space-y-3 md:space-y-4">
            <?php foreach ($journals as $j): ?>
                <div class="bg-white rounded-lg shadow p-4 md:p-6 hover:shadow-lg transition border-l-4 border-l-blue-500">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4 mb-3 md:mb-4">
                        <!-- Date -->
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Tanggal</p>
                            <p class="text-sm md:text-lg font-semibold text-gray-900 mt-1"><?= date('d F Y', strtotime($j['date'])) ?></p>
                        </div>

                        <!-- Class & Subject -->
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Kelas & Mapel</p>
                            <p class="text-sm md:text-lg font-semibold text-gray-900 mt-1"><?= htmlspecialchars($j['class_name']) ?> - <?= htmlspecialchars($j['subject_name']) ?></p>
                        </div>

                        <!-- Jam -->
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Jam ke</p>
                            <p class="text-sm md:text-lg font-semibold text-gray-900 mt-1"><?= htmlspecialchars($j['jam_ke']) ?></p>
                        </div>
                    </div>

                    <!-- Materi / BK -->
                    <div class="mb-3 md:mb-4 pb-3 md:pb-4 border-t border-gray-200">
                        <?php if ($isGuruBk): ?>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Sasaran Kegiatan</p>
                            <p class="text-xs md:text-sm text-gray-700"><?= htmlspecialchars($j['target_kegiatan'] ?? '-') ?></p>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1 mt-2">Kegiatan Layanan</p>
                            <p class="text-xs md:text-sm text-gray-700"><?= htmlspecialchars($j['kegiatan_layanan'] ?? '-') ?></p>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1 mt-2">Hasil yang Dicapai</p>
                            <p class="text-xs md:text-sm text-gray-700 line-clamp-2"><?= htmlspecialchars($j['hasil_dicapai'] ?? $j['materi']) ?></p>
                        <?php else: ?>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Materi</p>
                            <p class="text-xs md:text-sm text-gray-700 line-clamp-2"><?= htmlspecialchars($j['materi']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 flex-wrap">
                        <a
                            href="?p=teacher/view&id=<?= $j['id'] ?>"
                            class="inline-block text-blue-600 hover:text-blue-800 font-semibold text-xs md:text-sm px-2 md:px-3 py-1 rounded hover:bg-blue-50 transition touch-manipulation">
                            👁️ Lihat Detail
                        </a>
                        <a
                            href="?p=teacher/edit&id=<?= $j['id'] ?>"
                            class="inline-block text-amber-600 hover:text-amber-800 font-semibold text-xs md:text-sm px-2 md:px-3 py-1 rounded hover:bg-amber-50 transition touch-manipulation">
                            ✏️ Edit
                        </a>
                        <a
                            href="?p=teacher/delete&id=<?= $j['id'] ?>"
                            onclick="return confirm('Hapus jurnal ini? Tindakan tidak dapat dibatalkan.');"
                            class="inline-block text-red-600 hover:text-red-800 font-semibold text-xs md:text-sm px-2 md:px-3 py-1 rounded hover:bg-red-50 transition touch-manipulation">
                            🗑️ Hapus
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Total Count -->
        <div class="text-center pt-3 md:pt-4 text-xs md:text-sm text-gray-600">
            Total jurnal: <span class="font-semibold"><?= count($journals) ?></span>
        </div>
    <?php endif; ?>

    <!-- Add Button -->
    <div class="flex gap-2">
        <a
            href="?p=teacher/add"
            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 md:py-3 text-sm md:text-base rounded-lg transition text-center touch-manipulation">
            + Tambah Jurnal Baru
        </a>
        <a
            href="?p=teacher/dashboard"
            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 md:py-3 text-sm md:text-base rounded-lg transition text-center touch-manipulation">
            Kembali
        </a>
    </div>
</div>
