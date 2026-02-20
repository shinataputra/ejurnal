<?php
// views/admin/academic_years_list.php
?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Tahun Pelajaran</h1>
        <p class="text-gray-600">Atur tahun pelajaran aktif</p>
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

    <!-- Add Button -->
    <a href="?p=admin/academicYearsAdd" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition">
        ➕ Tambah Tahun Pelajaran
    </a>

    <!-- Academic Years List -->
    <?php if (empty($years)): ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-600 text-lg">Belum ada tahun pelajaran terdaftar</p>
        </div>
    <?php else: ?>
        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($years as $ay): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($ay['name']) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('d M Y', strtotime($ay['start_date'])) ?> - <?= date('d M Y', strtotime($ay['end_date'])) ?></td>
                            <td class="px-6 py-4 text-sm">
                                <?php if ($ay['is_active']): ?>
                                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">✓ Aktif</span>
                                <?php else: ?>
                                    <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <?php if (!$ay['is_active']): ?>
                                    <a href="?p=admin/academicYearsSetActive&id=<?= $ay['id'] ?>" onclick="return confirm('Set tahun <?= htmlspecialchars($ay['name']) ?> sebagai tahun aktif?')" class="inline-block bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded text-xs font-semibold transition">
                                        📌 Set Aktif
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-500 text-xs">Tahun aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="?p=admin/academicYearsEdit&id=<?= $ay['id'] ?>" class="inline-block bg-amber-100 hover:bg-amber-200 text-amber-800 px-3 py-1 rounded text-xs font-semibold transition">
                                    ✏️ Edit
                                </a>
                                <?php if (!$ay['is_active']): ?>
                                    <a href="?p=admin/academicYearsDelete&id=<?= $ay['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus tahun <?= htmlspecialchars($ay['name']) ?>?')" class="inline-block bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded text-xs font-semibold transition">
                                        🗑️ Hapus
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            <?php foreach ($years as $ay): ?>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 <?= $ay['is_active'] ? 'border-l-green-500' : 'border-l-gray-500' ?>">
                    <div class="mb-3">
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($ay['name']) ?></p>
                        <p class="text-xs text-gray-500 mt-1">
                            <?= date('d M Y', strtotime($ay['start_date'])) ?> - <?= date('d M Y', strtotime($ay['end_date'])) ?>
                        </p>
                    </div>
                    <div class="mb-3">
                        <?php if ($ay['is_active']): ?>
                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">✓ Tahun Aktif</span>
                        <?php else: ?>
                            <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Tidak Aktif</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="?p=admin/academicYearsEdit&id=<?= $ay['id'] ?>" class="flex-1 text-center bg-amber-100 hover:bg-amber-200 text-amber-800 px-2 py-1 rounded text-xs font-semibold transition">
                            ✏️ Edit
                        </a>
                        <?php if (!$ay['is_active']): ?>
                            <a href="?p=admin/academicYearsDelete&id=<?= $ay['id'] ?>" onclick="return confirm('Apakah Anda yakin?')" class="flex-1 text-center bg-red-100 hover:bg-red-200 text-red-800 px-2 py-1 rounded text-xs font-semibold transition">
                                🗑️ Hapus
                            </a>
                        <?php endif; ?>
                        <?php if (!$ay['is_active']): ?>
                            <a href="?p=admin/academicYearsSetActive&id=<?= $ay['id'] ?>" onclick="return confirm('Set tahun sebagai aktif?')" class="flex-1 text-center bg-blue-100 hover:bg-blue-200 text-blue-800 px-2 py-1 rounded text-xs font-semibold transition">
                                📌 Aktif
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>