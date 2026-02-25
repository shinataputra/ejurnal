<?php
// views/teacher/view_task.php
?>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">📋 Detail Tugas</h1>
        <!-- <a href="?p=teacher/list-tasks" class="text-blue-600 hover:text-blue-800 font-semibold">
            ← Kembali
        </a> -->
    </div>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="md:col-span-2 space-y-6">
            <!-- Task Info Card -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900">Informasi Tugas</h2>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Kelas:</span>
                        <span class="text-gray-900 font-semibold"><?= htmlspecialchars($task['class_name']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Tanggal:</span>
                        <span class="text-gray-900 font-semibold"><?= date('d/m/Y', strtotime($task['date'])) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Jam Ke:</span>
                        <span class="text-gray-900 font-semibold"><?= htmlspecialchars($task['jam_ke']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Tingkat Kelas:</span>
                        <span class="text-gray-900 font-semibold"><?= htmlspecialchars($task['jenjang']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Dikirim Pada:</span>
                        <span class="text-gray-900 font-semibold"><?= date('d/m/Y H:i', strtotime($task['created_at'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- PDF File Card -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900">📄 File Tugas</h2>
                <?php if ($task['file_path']): ?>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <p class="text-gray-700 font-medium mb-2">
                                <?php
                                $filename = basename($task['file_path']);
                                echo htmlspecialchars($filename);
                                ?>
                            </p>
                            <a href="<?= htmlspecialchars($task['file_path']) ?>" download class="text-blue-600 hover:text-blue-800 font-semibold inline-block">
                                ⬇ Download PDF
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">File tidak tersedia</p>
                <?php endif; ?>
            </div>

            <!-- Status Info Card -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900">Status Verifikasi</h2>

                <?php
                $statusClasses = [
                    'pending' => 'bg-yellow-50 border-yellow-200',
                    'verified' => 'bg-green-50 border-green-200',
                    'rejected' => 'bg-red-50 border-red-200'
                ];
                $statusLabels = [
                    'pending' => '⏳ Menunggu Verifikasi',
                    'verified' => '✓ Terverifikasi',
                    'rejected' => '✗ Ditolak'
                ];
                $statusLabel = $statusLabels[$task['status']] ?? $task['status'];
                $statusClass = $statusClasses[$task['status']] ?? 'bg-gray-50';
                ?>

                <div class="border-2 <?= $statusClass ?> rounded-lg p-4 mb-4">
                    <p class="font-bold text-lg"><?= $statusLabel ?></p>
                </div>

                <?php if ($task['status'] !== 'pending'): ?>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Diverifikasi Pada:</span>
                            <p class="text-gray-900 font-medium"><?= date('d/m/Y H:i', strtotime($task['verified_at'])) ?></p>
                        </div>
                        <div>
                            <span class="text-gray-600">Diverifikasi Oleh:</span>
                            <p class="text-gray-900 font-medium"><?= htmlspecialchars($task['verified_by_name'] ?? 'Admin') ?></p>
                        </div>
                        <?php if ($task['status'] === 'rejected' && $task['admin_notes']): ?>
                            <div>
                                <span class="text-gray-600">Catatan Admin:</span>
                                <p class="text-gray-900 font-medium mt-1 p-3 bg-red-50 border border-red-200 rounded">
                                    <?= htmlspecialchars($task['admin_notes']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Quick Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-bold text-blue-900 mb-3">📝 Info Singkat</h3>
                <div class="text-sm space-y-2 text-blue-800">
                    <div>
                        <span class="font-medium">ID Tugas:</span>
                        <p class="text-xs text-gray-600">#<?= $task['id'] ?></p>
                    </div>
                    <div>
                        <span class="font-medium">Status:</span>
                        <p class="mt-1">
                            <?php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'verified' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800'
                            ];
                            $color = $statusColors[$task['status']] ?? 'bg-gray-100';
                            ?>
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-medium <?= $color ?>">
                                <?= $statusLabel ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2">
                <?php if ($task['status'] === 'pending'): ?>
                    <a href="?p=teacher/delete-task&id=<?= $task['id'] ?>" class="block text-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition" onclick="return confirm('Hapus tugas ini?')">
                        🗑 Hapus Tugas
                    </a>
                <?php endif; ?>
                <a href="?p=teacher/list-tasks" class="block text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>
</div>
