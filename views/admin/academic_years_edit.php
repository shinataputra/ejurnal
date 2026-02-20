<?php
// views/admin/academic_years_edit.php
?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Tahun Pelajaran</h1>
        <p class="text-gray-600">Ubah informasi tahun pelajaran</p>
    </div>

    <!-- Form -->
    <form method="post" action="?p=admin/academicYearsEdit&id=<?= $year['id'] ?>" class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <div class="space-y-4">
            <!-- Year Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tahun <span class="text-red-500">*</span></label>
                <input type="text" name="name" placeholder="Contoh: 2025/2026" value="<?= htmlspecialchars($year['name']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required>
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="<?= $year['start_date'] ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required>
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" value="<?= $year['end_date'] ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required>
            </div>

            <!-- Status Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-sm text-blue-800">
                    <?php if ($year['is_active']): ?>
                        <strong>Tahun pelajaran ini sedang aktif.</strong> Tidak dapat dihapus sampai tahun pelajaran lain menjadi aktif.
                    <?php else: ?>
                        Tahun pelajaran tidak aktif dan dapat dihapus.
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                ✓ Simpan Perubahan
            </button>
            <a href="?p=admin/academic_years" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-lg transition text-center">
                Batal
            </a>
        </div>
    </form>
</div>