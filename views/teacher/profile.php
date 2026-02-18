<?php
// views/teacher/profile.php
?>
<div class="max-w-3xl mx-auto space-y-4">
    <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <h2 class="text-lg font-bold mb-3">Profil Akun</h2>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="p-3 bg-red-50 border border-red-200 text-red-700 rounded mb-3">
                <?= htmlspecialchars($_SESSION['flash_error']);
                unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="p-3 bg-green-50 border border-green-200 text-green-700 rounded mb-3">
                <?= htmlspecialchars($_SESSION['flash_success']);
                unset($_SESSION['flash_success']); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?p=teacher/profile" class="space-y-3">
            <input type="hidden" name="update_profile" value="1">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">NIP</label>
                <input type="text" name="nip" value="<?= htmlspecialchars($user['nip'] ?? '') ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Username</label>
                <div class="w-full border rounded px-3 py-2 bg-gray-50 text-gray-700"><?= htmlspecialchars($user['username'] ?? '') ?></div>
                <p class="text-xs text-gray-500 mt-1">Username tidak dapat diubah oleh guru.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Simpan</button>
                <a href="?p=teacher/dashboard" class="text-sm text-gray-600 hover:underline">Kembali</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <h3 class="text-md font-bold mb-3">Ubah Password</h3>
        <form method="post" action="index.php?p=teacher/profile" class="space-y-3">
            <input type="hidden" name="change_password" value="1">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="new_password" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Ulangi Password Baru</label>
                <input type="password" name="confirm_password" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Ganti Password</button>
            </div>
        </form>
    </div>
</div>