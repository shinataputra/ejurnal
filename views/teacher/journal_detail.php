<?php
// views/teacher/journal_detail.php
$className = str_replace('-', ' ', $journal['class_name'] ?? '');
$formattedDate = date('d F Y', strtotime($journal['date']));
?>
<div>
    <!-- Header -->
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1 md:mb-2">Detail Jurnal Mengajar</h1>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow-lg p-4 md:p-6 space-y-4 md:space-y-6">

        <!-- Date Section -->
        <div class="pb-4 md:pb-6 border-b border-gray-200">
            <label class="block text-xs md:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Tanggal</label>
            <p class="text-lg md:text-xl font-semibold text-gray-900"><?= htmlspecialchars($formattedDate) ?></p>
        </div>

        <!-- Grid: Class and Subject -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 pb-4 md:pb-6 border-b border-gray-200">
            <div>
                <label class="block text-xs md:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Kelas</label>
                <p class="text-base md:text-lg font-semibold text-gray-900"><?= htmlspecialchars($className) ?></p>
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Mata Pelajaran</label>
                <p class="text-base md:text-lg font-semibold text-gray-900"><?= htmlspecialchars($journal['subject_name'] ?? '') ?></p>
            </div>
        </div>

        <!-- Jam ke -->
        <div class="pb-4 md:pb-6 border-b border-gray-200">
            <label class="block text-xs md:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Jam ke</label>
            <p class="text-base md:text-lg font-semibold text-gray-900"><?= htmlspecialchars($journal['jam_ke']) ?></p>
        </div>

        <!-- Materi -->
        <div class="pb-4 md:pb-6 border-b border-gray-200">
            <label class="block text-xs md:text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Materi</label>
            <div class="bg-gray-50 rounded-lg p-3 md:p-4 border border-gray-200">
                <p class="text-sm md:text-base text-gray-800 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($journal['materi']) ?></p>
            </div>
        </div>

        <!-- Notes (if present) -->
        <?php if (!empty($journal['notes'])): ?>
            <div class="pb-4 md:pb-6 border-b border-gray-200">
                <label class="block text-xs md:text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Catatan Guru</label>
                <div class="bg-amber-50 rounded-lg p-3 md:p-4 border border-amber-200">
                    <p class="text-sm md:text-base text-amber-900 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($journal['notes']) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Additional Info -->
        <div class="text-xs md:text-sm text-gray-500 space-y-1 pt-2">
            <?php if (!empty($journal['created_at'])): ?>
                <p>Dibuat: <?= date('d F Y H:i', strtotime($journal['created_at'])) ?></p>
            <?php endif; ?>
            <?php if (!empty($journal['updated_at']) && $journal['updated_at'] !== $journal['created_at']): ?>
                <p>Diperbarui: <?= date('d F Y H:i', strtotime($journal['updated_at'])) ?></p>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-2 pt-4 md:pt-6 border-t border-gray-200">
            <a
                href="?p=teacher/edit&id=<?= $journal['id'] ?>"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 md:py-3 text-sm md:text-base rounded-lg transition text-center">
                ✏️ Edit
            </a>
            <button
                type="button"
                onclick="if(confirm('Apakah Anda yakin ingin menghapus jurnal ini?')) { window.location.href = '?p=teacher/delete&id=<?= $journal['id'] ?>'; }"
                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 md:py-3 text-sm md:text-base rounded-lg transition">
                🗑️ Hapus
            </button>
            <a
                href="?p=teacher/list_journal"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 md:py-3 text-sm md:text-base rounded-lg transition text-center">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-4 md:mt-6 bg-green-50 border border-green-200 rounded-lg p-3 md:p-4 text-xs md:text-sm text-green-900">
        <p class="font-semibold mb-2">✅ Informasi</p>
        <ul class="list-disc list-inside space-y-1">
            <li>Gunakan tombol Edit untuk memperbarui data jurnal</li>
            <li>Gunakan tombol Hapus untuk menghapus jurnal ini secara permanen</li>
            <li>Klik Kembali untuk kembali ke daftar jurnal</li>
        </ul>
    </div>
</div>