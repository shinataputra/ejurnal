<?php
// views/admin/classes_add.php
?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah Kelas</h1>
        <p class="text-gray-600">Buat kelas baru</p>
    </div>

    <!-- Form -->
    <form method="post" action="?p=admin/classesAdd" class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <div class="space-y-4">
            <!-- Jenjang & Rombel -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenjang <span class="text-red-500">*</span></label>
                    <input type="text" name="jenjang" placeholder="Contoh: X, XI, XII" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rombel <span class="text-red-500">*</span></label>
                    <input type="text" name="rombel" placeholder="Contoh: RPL 1, IPA A" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                ✓ Simpan
            </button>
            <a href="?p=admin/classes" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-lg transition text-center">
                Batal
            </a>
        </div>
    </form>
</div>