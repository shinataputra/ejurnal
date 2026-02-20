<?php
// views/teacher/dashboard.php
?>
<div class="space-y-4 md:space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg p-4 md:p-8 shadow-lg">
        <h1 class="text-base sm:text-lg md:text-2xl lg:text-3xl font-bold mb-1 md:mb-2 break-words leading-snug">Halo, <?= htmlspecialchars($current_user['name'] ?? 'Guru') ?>!</h1>
        <p class="text-xs sm:text-sm md:text-base text-blue-100">Kelola jurnal mengajar Anda dengan mudah</p>
    </div>

    <!-- Success Message -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="p-3 md:p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 md:w-6 md:h-6 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <div class="flex-1">
                <p class="font-semibold text-sm md:text-base">Berhasil!</p>
                <p class="text-xs md:text-sm"><?= htmlspecialchars($_SESSION['flash_success']);
                                                unset($_SESSION['flash_success']); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Today's Journal Status -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 <?= $today_filled ? 'border-l-green-500' : 'border-l-yellow-500' ?>">
            <div class="flex items-start md:items-center justify-between gap-3">
                <div class="flex-1">
                    <p class="text-xs md:text-sm text-gray-600 mb-1">Status Jurnal Hari Ini</p>
                    <h2 class="text-lg md:text-2xl font-bold <?= $today_filled ? 'text-green-600' : 'text-yellow-600' ?>">
                        <?= $today_filled ? 'Sudah Diisi ✓' : 'Belum Diisi' ?>
                    </h2>
                </div>
                <div class="<?= $today_filled ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' ?> rounded-full p-2 md:p-4 flex-shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 20 20">
                        <?php if ($today_filled): ?>
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 3.062v6.018a1 1 0 01-.227.519l-5.528 5.528a2 2 0 01-2.828 0l-5.528-5.528A1 1 0 012.455 12.5V6.482a3.066 3.066 0 012.812-3.062zm7.958 5.421a.75.75 0 00-1.064-1.06L9 10.938 7.854 9.792a.75.75 0 10-1.06 1.061l1.5 1.5a.75.75 0 001.06 0l3.5-3.5z" clip-rule="evenodd"></path>
                        <?php else: ?>
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 3.062v6.018a1 1 0 01-.227.519l-5.528 5.528a2 2 0 01-2.828 0l-5.528-5.528A1 1 0 012.455 12.5V6.482a3.066 3.066 0 012.812-3.062zM9 7a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                        <?php endif; ?>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Tanggal: <?= date('d F Y', strtotime('today')) ?></p>
        </div>

        <!-- Quick Stats & Primary Action -->
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-l-blue-500">
            <div>
                <p class="text-xs md:text-sm text-gray-600 mb-1">Aksi Cepat</p>
                <h2 class="text-base md:text-lg font-bold text-gray-900 mb-2 md:mb-4">Mulai Catat Jurnal</h2>
                <p class="text-xs md:text-sm text-gray-600 mb-4 md:mb-6">Pastikan Anda selalu mencatat jurnal mengajar setiap hari untuk kelengkapan administrasi.</p>

                <!-- Primary Action Button: Tambah Jurnal -->
                <a href="?p=teacher/add" class="primary-btn-add-journal" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 12px 24px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 1rem; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3); border: none; cursor: pointer; white-space: nowrap;">
                    <svg style="width: 24px; height: 24px; stroke: white; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span style="color: white; font-weight: 700;">Tambah Jurnal</span>
                </a>

                <p class="text-xs md:text-sm text-gray-500 mt-3">Isi jurnal harian mengajar untuk administrasi lengkap</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">

        <a href="?p=teacher/list" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-lg p-4 md:p-6 shadow-md transition active:scale-95 md:hover:scale-105 block touch-manipulation">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="bg-indigo-500 rounded-full p-2 md:p-3 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base md:text-lg font-bold">Lihat Jurnal</h3>
                    <p class="text-xs md:text-sm text-indigo-100">Review jurnal Anda</p>
                </div>
            </div>
        </a>

        <a href="?p=teacher/send-task" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-lg p-4 md:p-6 shadow-md transition active:scale-95 md:hover:scale-105 block touch-manipulation">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="bg-purple-500 rounded-full p-2 md:p-3 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.5 3A1.5 1.5 0 011 4.5v.006c0 .007 0 .015.002.023v10.971A1.5 1.5 0 002.5 17h15a1.5 1.5 0 001.5-1.5v-10.97c0-.008 0-.016.002-.023v-.006A1.5 1.5 0 0017.5 3h-15zm.5 2h14v.5H3v-.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base md:text-lg font-bold">Kirim Tugas Kelas</h3>
                    <p class="text-xs md:text-sm text-purple-100">Tugas dikirim ke kurikulum untuk diteruskan ke kelas</p>
                </div>
            </div>
        </a>

        <a href="?p=teacher/rekap-monthly" class="bg-gradient-to-r from-pink-600 to-pink-700 hover:from-pink-700 hover:to-pink-800 text-white rounded-lg p-4 md:p-6 shadow-md transition active:scale-95 md:hover:scale-105 block touch-manipulation">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="bg-pink-500 rounded-full p-2 md:p-3 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V7z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base md:text-lg font-bold">Rekap Bulanan</h3>
                    <p class="text-xs md:text-sm text-pink-100">Ringkasan jurnal per bulan</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 md:p-4 text-blue-900">
        <p class="font-semibold text-sm md:text-base mb-1">💡 Tips</p>
        <p class="text-xs md:text-sm">Isi jurnal setiap hari untuk menjaga kelengkapan data. Anda dapat melihat dan mencetak riwayat jurnal Anda kapan saja.</p>
    </div>
</div>