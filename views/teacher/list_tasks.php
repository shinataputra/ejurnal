<?php
// views/teacher/list_tasks.php
?>
<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">📋 Daftar Tugas Saya</h1>
            <a href="?p=teacher/send-task&form=1" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                ➕ Kirim Tugas Baru
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-4">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
            <?php unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 md:p-6">
            <div class="text-2xl md:text-3xl font-bold text-yellow-600"><?= $pending_count ?></div>
            <div class="text-xs md:text-sm text-gray-600">Menunggu Verifikasi</div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 md:p-6">
            <div class="text-2xl md:text-3xl font-bold text-green-600"><?= $verified_count ?></div>
            <div class="text-xs md:text-sm text-gray-600">Terverifikasi</div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 md:p-6">
            <div class="text-2xl md:text-3xl font-bold text-red-600"><?= $rejected_count ?></div>
            <div class="text-xs md:text-sm text-gray-600">Ditolak</div>
        </div>
    </div>

    <!-- Tasks List: Table on Desktop, Cards on Mobile -->

    <!-- Desktop Table View (hidden on mobile) -->
    <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-100 border-b-2 border-gray-300">
                <tr>
                    <th class="px-4 py-3 text-xs md:text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="px-4 py-3 text-xs md:text-sm font-semibold text-gray-700">Kelas</th>
                    <th class="px-4 py-3 text-xs md:text-sm font-semibold text-gray-700">Jam Ke</th>
                    <th class="px-4 py-3 text-xs md:text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 text-xs md:text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Tidak ada tugas. <a href="?p=teacher/send-task" class="text-blue-600 hover:underline">Kirim tugas sekarang</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="font-medium"><?= date('d/m/Y', strtotime($task['date'])) ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <span><?= htmlspecialchars($task['class_name']) ?></span>
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
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium <?= $class ?>">
                                    <?= $label ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="?p=teacher/view-task&id=<?= $task['id'] ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        👁 Lihat
                                    </a>
                                    <?php if ($task['status'] === 'pending'): ?>
                                        <a href="?p=teacher/delete-task&id=<?= $task['id'] ?>" class="text-red-600 hover:text-red-800 font-medium text-sm" onclick="return confirm('Hapus tugas ini?')">
                                            🗑 Hapus
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View (hidden on desktop) -->
    <div class="md:hidden space-y-3">
        <?php if (empty($tasks)): ?>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-500 text-sm">
                    Tidak ada tugas. <a href="?p=teacher/send-task" class="text-blue-600 hover:underline font-medium">Kirim tugas sekarang</a>
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 <?=
                                                                            $task['status'] === 'pending' ? 'border-l-yellow-500' : ($task['status'] === 'verified' ? 'border-l-green-500' : 'border-l-red-500')
                                                                            ?>">
                    <!-- Header Section -->
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">
                                📅 <?= date('d/m/Y', strtotime($task['date'])) ?>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?= date('l', strtotime($task['date'])) ?>
                            </div>
                        </div>
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
                        <span class="inline-block px-2 py-1 rounded text-xs font-medium <?= $class ?>">
                            <?= $label ?>
                        </span>
                    </div>

                    <!-- Details Section -->
                    <div class="space-y-2 mb-4 text-sm">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600 font-medium">Kelas:</span>
                            <span class="text-gray-900 font-medium"><?= htmlspecialchars($task['class_name']) ?></span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-600 font-medium">Jam Ke:</span>
                            <span class="text-gray-900 font-medium"><?= htmlspecialchars($task['jam_ke']) ?></span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 pt-3 border-t border-gray-200">
                        <a href="?p=teacher/view-task&id=<?= $task['id'] ?>" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm py-2 rounded transition">
                            👁 Lihat
                        </a>
                        <?php if ($task['status'] === 'pending'): ?>
                            <a href="?p=teacher/delete-task&id=<?= $task['id'] ?>" class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white font-medium text-sm py-2 rounded transition" onclick="return confirm('Hapus tugas ini?')">
                                🗑 Hapus
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>