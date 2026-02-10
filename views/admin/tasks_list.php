<?php
// views/admin/tasks_list.php
?>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">✏️ Manajemen Tugas Guru</h1>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
            <?php unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="text-2xl font-bold text-yellow-600"><?= $pending_count ?></div>
            <div class="text-sm text-gray-600">Menunggu Verifikasi</div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="text-2xl font-bold text-green-600"><?= $verified_count ?></div>
            <div class="text-sm text-gray-600">Terverifikasi</div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="text-2xl font-bold text-red-600"><?= $rejected_count ?></div>
            <div class="text-sm text-gray-600">Ditolak</div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <form method="GET" class="space-y-4">
            <input type="hidden" name="p" value="admin/tasks">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" <?= $filter_status === 'pending' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="verified" <?= $filter_status === 'verified' ? 'selected' : '' ?>>Terverifikasi</option>
                        <option value="rejected" <?= $filter_status === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>

                <!-- Start Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($filter_start_date ?? '') ?>">
                </div>

                <!-- End Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($filter_end_date ?? '') ?>">
                </div>

                <!-- Search Button -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        🔍 Filter
                    </button>
                    <a href="?p=admin/tasks" class="text-gray-600 hover:text-gray-800 font-semibold px-3 py-2 rounded-lg">
                        ✕
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tasks Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-100 border-b-2 border-gray-300">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-700">Guru</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Kelas</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Tanggal</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Jam Ke</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Tidak ada tugas ditemukan
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="font-medium"><?= htmlspecialchars($task['teacher_name']) ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <span><?= htmlspecialchars($task['class_name']) ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <span><?= date('d/m/Y', strtotime($task['date'])) ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <span>Jam ke-<?= htmlspecialchars($task['jam_ke']) ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <?php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'verified' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabels = [
                                    'pending' => '⏳ Menunggu',
                                    'verified' => '✓ Terverifikasi',
                                    'rejected' => '✗ Ditolak'
                                ];
                                $class = $statusClasses[$task['status']] ?? 'bg-gray-100';
                                $label = $statusLabels[$task['status']] ?? $task['status'];
                                ?>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?= $class ?>">
                                    <?= $label ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="?p=admin/task-view&id=<?= $task['id'] ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                    👁 Lihat
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>