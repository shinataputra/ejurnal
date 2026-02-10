<?php
// views/admin/task_view.php
?>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">👁 Detail Tugas</h1>
        <a href="?p=admin/tasks" class="text-blue-600 hover:text-blue-800 font-semibold">
            ← Kembali
        </a>
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
            <!-- Teacher Info Card -->
            <div class="border border-gray-200 rounded-lg p-6 bg-sky-50">
                <h2 class="text-xl font-bold mb-4 text-gray-900">👨‍🏫 Informasi Guru</h2>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Nama Guru:</span>
                        <span class="text-gray-900 font-semibold"><?= htmlspecialchars($task['teacher_name']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Task Info Card -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900">📋 Informasi Tugas</h2>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Kelas:</span>
                        <span class="text-gray-900 font-semibold"><?= htmlspecialchars($task['class_name']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Tingkat Kelas:</span>
                        <span class="text-gray-900 font-semibold"><?= htmlspecialchars($task['jenjang']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Tanggal Tugas:</span>
                        <span class="text-gray-900 font-semibold"><?= date('d/m/Y', strtotime($task['date'])) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 font-medium">Jam Ke:</span>
                        <span class="text-gray-900 font-semibold">Jam ke-<?= htmlspecialchars($task['jam_ke']) ?></span>
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
                    <?php if (file_exists(__DIR__ . '/../../' . $task['file_path'])): ?>
                        <!-- PDF Viewer -->
                        <div class="mt-4 border-t pt-4">
                            <p class="text-sm text-gray-600 mb-2">Preview PDF:</p>
                            <iframe src="<?= htmlspecialchars($task['file_path']) ?>#toolbar=0" class="w-full h-96 border border-gray-200 rounded-lg"></iframe>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-gray-500">File tidak tersedia</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar - Actions -->
        <div class="space-y-4">
            <!-- Current Status Card -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h3 class="font-bold text-gray-900 mb-4">📊 Status Saat Ini</h3>
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
                <div class="border-2 <?= $statusClass ?> rounded-lg p-4 text-center font-bold">
                    <?= $statusLabel ?>
                </div>

                <?php if ($task['status'] !== 'pending'): ?>
                    <div class="mt-4 text-sm space-y-2 text-gray-600">
                        <div>
                            <strong>Diverifikasi:</strong>
                            <p><?= date('d/m/Y H:i', strtotime($task['verified_at'])) ?></p>
                        </div>
                        <div>
                            <strong>Oleh:</strong>
                            <p><?= htmlspecialchars($task['verified_by_name'] ?? 'Admin') ?></p>
                        </div>
                        <?php if ($task['admin_notes']): ?>
                            <div class="mt-3 p-3 bg-gray-100 rounded border border-gray-300">
                                <strong>Catatan:</strong>
                                <p class="text-gray-700 mt-1"><?= htmlspecialchars($task['admin_notes']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <?php if ($task['status'] === 'pending'): ?>
                <div class="space-y-3">
                    <!-- Verify Button -->
                    <form method="POST" action="?p=admin/task-verify">
                        <input type="hidden" name="id" value="<?= $task['id'] ?>">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition" onclick="return confirm('Verifikasi tugas ini?')">
                            ✓ Verifikasi
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <button type="button" onclick="document.getElementById('rejectForm').style.display = 'block'; document.getElementById('rejectForm').scrollIntoView();" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        ✗ Tolak
                    </button>
                </div>
            <?php endif; ?>

            <!-- Back Button -->
            <a href="?p=admin/tasks" class="block text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Reject Form (Hidden) -->
    <?php if ($task['status'] === 'pending'): ?>
        <div id="rejectForm" style="display: none;" class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
            <h2 class="text-xl font-bold text-red-900 mb-4">✗ Tolak Tugas</h2>

            <form method="POST" action="?p=admin/task-reject">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-red-900 mb-2">
                        Catatan untuk Guru <span class="text-red-500">*</span>
                    </label>
                    <textarea name="admin_notes" required rows="6" class="w-full px-4 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Tulis alasan penolakan atau saran perbaikan..."></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition" onclick="return confirm('Yakin ingin menolak tugas ini?')">
                        ✗ Tolak Tugas
                    </button>
                    <button type="button" onclick="document.getElementById('rejectForm').style.display = 'none';" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>