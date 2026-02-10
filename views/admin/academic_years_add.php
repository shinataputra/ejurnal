<?php
// views/admin/academic_years_add.php
?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Tahun Pelajaran</h1>
        <p class="text-gray-600">Buat tahun pelajaran baru</p>
    </div>

    <!-- Form -->
    <form method="post" action="?p=admin/academicYearsAdd" class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <div class="space-y-4">
            <!-- Year Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tahun <span class="text-red-500">*</span></label>
                <input type="text" name="name" placeholder="Contoh: 2025/2026" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required>
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required>
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required>
            </div>

            <!-- Set as Active -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" class="w-4 h-4 rounded">
                <label for="is_active" class="text-sm text-gray-700">Set sebagai tahun pelajaran aktif</label>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                ✓ Simpan
            </button>
            <a href="?p=admin/academicYears" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-lg transition text-center">
                Batal
            </a>
        </div>
    </form>
</div>