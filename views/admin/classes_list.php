<?php
// views/admin/classes_list.php
?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Kelas</h1>
        <p class="text-gray-600">Daftar semua kelas yang tersedia</p>
    </div>

    <!-- Success Message -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p><?= htmlspecialchars($_SESSION['flash_success']);
                unset($_SESSION['flash_success']); ?></p>
        </div>
    <?php endif; ?>

    <!-- Add Button -->
    <a href="?p=admin/classesAdd" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
        ➕ Tambah Kelas
    </a>

    <!-- Classes List -->
    <?php if (empty($classes)): ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-600 text-lg">Belum ada kelas terdaftar</p>
        </div>
    <?php else: ?>
        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($classes as $c): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($c['name']) ?></td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="?p=admin/classesEdit&id=<?= $c['id'] ?>" class="inline-block bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded text-xs font-semibold transition">
                                    ✏️ Edit
                                </a>
                                <a href="?p=admin/classesDelete&id=<?= $c['id'] ?>" onclick="return confirm('Hapus kelas <?= htmlspecialchars($c['name']) ?>? Tindakan ini tidak dapat dibatalkan.')" class="inline-block bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded text-xs font-semibold transition">
                                    🗑️ Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            <?php foreach ($classes as $c): ?>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-l-green-500">
                    <div class="mb-3">
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($c['name']) ?></p>
                        <p class="text-xs text-gray-500 mt-1">Kelas #<?= $c['id'] ?></p>
                    </div>
                    <div class="space-y-2">
                        <a href="?p=admin/classesEdit&id=<?= $c['id'] ?>" class="block text-center bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded text-xs font-semibold transition">
                            ✏️ Edit
                        </a>
                        <a href="?p=admin/classesDelete&id=<?= $c['id'] ?>" onclick="return confirm('Hapus kelas <?= htmlspecialchars($c['name']) ?>? Tindakan ini tidak dapat dibatalkan.')" class="block text-center bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded text-xs font-semibold transition">
                            🗑️ Hapus
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>