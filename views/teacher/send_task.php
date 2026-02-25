<?php
// views/teacher/send_task.php
?>
<?php
$isGuruBk = !empty($is_guru_bk);
?>
<div class="bg-white rounded-lg shadow-md p-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">📤 Kirim Tugas Kelas</h1>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
            <?php unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal <span class="text-red-500">*</span>
            </label>
            <input type="date" name="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="<?= date('Y-m-d') ?>">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kelas <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <select name="jenjang" id="jenjang" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Jenjang --</option>
                        <?php foreach ($jenjangs as $j): ?>
                            <option value="<?= htmlspecialchars($j) ?>"><?= htmlspecialchars($j) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="class_id" id="rombel" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Rombel --</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <?php if ($isGuruBk): ?>
                    <input type="hidden" name="subject_id" value="<?= (int)($bk_subject_id ?? 0) ?>">
                    <input type="text" value="Bimbingan Konseling" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                <?php else: ?>
                    <select name="subject_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Mapel --</option>
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Jam ke <span class="text-red-500">*</span>
            </label>
            <input type="text" name="jam_ke" placeholder="Contoh: 2-4 atau 3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <?php if ($isGuruBk): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Sasaran Kegiatan <span class="text-red-500">*</span>
                </label>
                <textarea name="target_kegiatan" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kegiatan Layanan <span class="text-red-500">*</span>
                </label>
                <select name="kegiatan_layanan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Pilih Kegiatan Layanan --</option>
                    <?php foreach (($bk_service_options ?? []) as $option): ?>
                        <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Hasil yang Dicapai <span class="text-red-500">*</span>
                </label>
                <textarea name="hasil_dicapai" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
            </div>
        <?php else: ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Materi <span class="text-red-500">*</span>
                </label>
                <textarea name="materi" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
            </div>
        <?php endif; ?>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <?= $isGuruBk ? 'Catatan Guru BK' : 'Catatan Guru' ?>
            </label>
            <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
        </div>

        <!-- File Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                File Tugas (PDF) <span class="text-red-500">*</span>
            </label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition" id="dropZone">
                <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf" required class="hidden">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <p class="text-gray-600 font-medium">Drag and drop file PDF di sini atau klik untuk memilih</p>
                <p class="text-gray-500 text-sm mt-1">Hanya file PDF yang dapat diunggah</p>
                <p id="fileName" class="text-blue-600 text-sm mt-2 font-medium"></p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                📤 Kirim Tugas
            </button>
            <a href="?p=teacher/dashboard" class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition">
                ← Kembali
            </a>
        </div>
    </form>
</div>

<script>
    // Populate rombel dropdown based on jenjang
    const jenjangsData = <?= json_encode($rombelsByJenjang) ?>;

    document.getElementById('jenjang').addEventListener('change', function() {
        const jenjang = this.value;
        const rombelSelect = document.getElementById('rombel');
        rombelSelect.innerHTML = '<option value="">-- Pilih Rombel --</option>';

        if (jenjang && jenjangsData[jenjang]) {
            jenjangsData[jenjang].forEach(r => {
                const option = document.createElement('option');
                option.value = r.id;
                option.textContent = r.rombel;
                rombelSelect.appendChild(option);
            });
        }
    });

    // Drag and drop file upload
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('pdf_file');
    const fileName = document.getElementById('fileName');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');

        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type === 'application/pdf') {
            fileInput.files = files;
            fileName.textContent = '✓ ' + files[0].name;
        } else {
            alert('Hanya file PDF yang dapat diunggah');
        }
    });

    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            fileName.textContent = '✓ ' + e.target.files[0].name;
        }
    });
</script>
