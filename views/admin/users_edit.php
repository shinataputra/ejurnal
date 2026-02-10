<?php
// views/admin/users_edit.php
?>
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Guru</h1>
        <p class="text-gray-600">Perbarui data guru</p>
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
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Guru <span class="text-red-600">*</span></label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nama lengkap guru">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">NIP <span class="text-red-600">*</span></label>
                <input type="text" name="nip" value="<?= htmlspecialchars($user['nip']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nomor Induk Pegawai">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-red-600">*</span></label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Username untuk login">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role <span class="text-red-600">*</span></label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Guru (Teacher)</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Tentukan peran pengguna dalam sistem</p>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 pt-6 mt-6 flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    💾 Simpan Perubahan
                </button>
                <a href="?p=admin/users" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition text-center">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Password Reset Warning -->
    <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-lg">
        <p class="text-sm"><strong>💡 Catatan:</strong> Untuk mengganti password guru, gunakan fitur "Reset Password" di halaman daftar guru.</p>
    </div>
</div>