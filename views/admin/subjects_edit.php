<?php
// views/admin/subjects_edit.php
?>
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Mata Pelajaran</h1>
        <p class="text-gray-600">Perbarui data mata pelajaran</p>
    </div>

    <!-- Error Message -->
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <p><?= htmlspecialchars($_SESSION['flash_error']);
                unset($_SESSION['flash_error']); ?></p>
        </div>
    <?php endif; ?>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow p-6 md:p-8">
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Mata Pelajaran <span class="text-red-600">*</span></label>
                <input type="text" name="name" value="<?= htmlspecialchars($subject['name']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Matematika, Bahasa Indonesia">
                <p class="text-xs text-gray-500 mt-1">Masukkan nama mata pelajaran</p>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 pt-6 mt-6 flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    💾 Simpan Perubahan
                </button>
                <a href="?p=admin/subjects" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition text-center">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>
</div>