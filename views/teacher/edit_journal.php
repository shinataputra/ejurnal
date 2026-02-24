<?php
// views/teacher/edit_journal.php

// Extract jenjang and rombel from class_name
$isGuruBk = !empty($is_guru_bk);
$jenjang_from_class = '';
$rombel_from_class = '';
if (!empty($journal['class_name'])) {
    $parts = preg_split('/[-\s]+/', trim($journal['class_name']), 2);
    $jenjang_from_class = $parts[0] ?? '';
    $rombel_from_class = $parts[1] ?? '';
}
?>
<div>
    <!-- Header -->
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1 md:mb-2">Edit Jurnal Mengajar</h1>
        <p class="text-sm md:text-base text-gray-600">Perbarui data jurnal Anda</p>
    </div>

    <!-- Form Card -->
    <form method="post" action="?p=teacher/edit&id=<?= $journal['id'] ?>" class="bg-white rounded-lg shadow-lg p-4 md:p-6 space-y-4 md:space-y-6">

        <!-- Date -->
        <div>
            <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
            <input
                type="date"
                name="date"
                value="<?= htmlspecialchars($journal['date']) ?>"
                class="w-full px-3 md:px-4 py-2 md:py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                required>
        </div>

        <!-- Grid: Class and Subject -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
            <div>
                <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-2">
                    <select id="jenjangSelect" name="jenjang" required class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        <option value="">-- Pilih Jenjang --</option>
                        <?php if (!empty($jenjangs)): foreach ($jenjangs as $j): ?>
                                <option value="<?= htmlspecialchars($j) ?>" <?= $j === $jenjang_from_class ? 'selected' : '' ?>><?= htmlspecialchars($j) ?></option>
                        <?php endforeach;
                        endif; ?>
                    </select>

                    <select id="rombelSelect" name="class_id" required class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        <option value="">-- Pilih Rombel --</option>
                        <!-- rombel options populated by JS -->
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                <?php if ($isGuruBk): ?>
                    <input type="hidden" name="subject_id" value="<?= (int)($bk_subject_id ?? 0) ?>">
                    <input type="text" value="Bimbingan Konseling" readonly class="w-full px-3 md:px-4 py-2 text-sm md:text-base bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                <?php else: ?>
                    <select
                        name="subject_id"
                        class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $journal['subject_id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
        </div>

        <!-- Jam ke -->
        <div>
            <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Jam ke <span class="text-red-500">*</span></label>
            <input
                type="text"
                name="jam_ke"
                value="<?= htmlspecialchars($journal['jam_ke']) ?>"
                placeholder="Contoh: 2-4 atau 3"
                class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                required>
            <p class="text-xs text-gray-500 mt-1">Masukkan jam pelajaran, misal: 2-4 untuk jam ke-2 sampai 4</p>
        </div>

        <?php if ($isGuruBk): ?>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Sasaran Kegiatan <span class="text-red-500">*</span></label>
                <textarea
                    name="target_kegiatan"
                    rows="3"
                    placeholder="Isi sasaran kegiatan..."
                    class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"
                    required><?= htmlspecialchars($journal['target_kegiatan'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Kegiatan Layanan <span class="text-red-500">*</span></label>
                <select name="kegiatan_layanan" class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" required>
                    <option value="">-- Pilih Kegiatan Layanan --</option>
                    <?php foreach (($bk_service_options ?? []) as $option): ?>
                        <option value="<?= htmlspecialchars($option) ?>" <?= ($journal['kegiatan_layanan'] ?? '') === $option ? 'selected' : '' ?>>
                            <?= htmlspecialchars($option) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Hasil yang Dicapai <span class="text-red-500">*</span></label>
                <textarea
                    name="hasil_dicapai"
                    rows="4"
                    placeholder="Isi hasil yang dicapai..."
                    class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"
                    required><?= htmlspecialchars($journal['hasil_dicapai'] ?? '') ?></textarea>
            </div>
        <?php else: ?>
            <!-- Materi -->
            <div>
                <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2">Materi <span class="text-red-500">*</span></label>
                <textarea
                    name="materi"
                    rows="4"
                    placeholder="Deskripsikan materi yang diajarkan..."
                    class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"
                    required><?= htmlspecialchars($journal['materi']) ?></textarea>
            </div>
        <?php endif; ?>

        <!-- Catatan Guru -->
        <div>
            <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-2"><?= $isGuruBk ? 'Catatan Guru BK' : 'Catatan Guru' ?></label>
            <textarea
                name="notes"
                rows="3"
                placeholder="<?= $isGuruBk ? 'Tambahkan catatan guru BK (opsional)' : 'Tambahkan catatan penting atau pengamatan khusus (opsional)' ?>"
                class="w-full px-3 md:px-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"><?= htmlspecialchars($journal['notes'] ?? '') ?></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex gap-2 pt-4">
            <button
                type="submit"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 md:py-3 text-sm md:text-base rounded-lg transition duration-200 transform active:scale-95">
                💾 Update Jurnal
            </button>
            <a
                href="?p=teacher/list"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 md:py-3 text-sm md:text-base rounded-lg transition text-center">
                Batal
            </a>
        </div>
    </form>

    <script>
        // rombelsByJenjang provided by PHP
        const rombelsByJenjang = <?= json_encode($rombelsByJenjang ?? []) ?>;
        const currentClassId = <?= json_encode((string)$journal['class_id']) ?>;
        const currentJenjang = <?= json_encode($jenjang_from_class) ?>;

        const jenjangSelect = document.getElementById('jenjangSelect');
        const rombelSelect = document.getElementById('rombelSelect');

        function clearRombel() {
            rombelSelect.innerHTML = '<option value="">-- Pilih Rombel --</option>';
        }

        function populateRombel(j) {
            clearRombel();
            const list = rombelsByJenjang[j] || [];
            list.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.rombel || item.id;
                if (item.id == currentClassId) {
                    opt.selected = true;
                }
                rombelSelect.appendChild(opt);
            });
        }

        jenjangSelect && jenjangSelect.addEventListener('change', function() {
            populateRombel(this.value);
        });

        // Initialize: populate rombels with current jenjang
        if (currentJenjang) {
            populateRombel(currentJenjang);
        } else if (jenjangSelect && jenjangSelect.options.length === 2) {
            jenjangSelect.selectedIndex = 1;
            populateRombel(jenjangSelect.value);
        }
    </script>

    <!-- Info Box -->
    <div class="mt-4 md:mt-6 bg-blue-50 border border-blue-200 rounded-lg p-3 md:p-4 text-xs md:text-sm text-blue-900">
        <p class="font-semibold mb-2">📌 Catatan</p>
        <ul class="list-disc list-inside space-y-1">
            <li>Isi semua field yang bertanda <span class="text-red-500">*</span></li>
            <?php if ($isGuruBk): ?>
                <li>Sasaran kegiatan, kegiatan layanan, dan hasil yang dicapai wajib diisi</li>
                <li>Catatan guru BK bersifat opsional</li>
            <?php else: ?>
                <li>Materi adalah wajib untuk mencatat topik pembelajaran</li>
                <li>Catatan guru bersifat opsional untuk informasi tambahan</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
