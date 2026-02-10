<?php
// views/admin/settings.php
?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">⚙️ Pengaturan Sekolah</h1>
        <p class="text-gray-600">Kelola informasi dan logo sekolah</p>
    </div>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex justify-between items-center">
            <span><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
            <button onclick="this.parentElement.style.display='none'" class="text-green-700 font-bold text-xl">&times;</button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex justify-between items-center">
            <span><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
            <button onclick="this.parentElement.style.display='none'" class="text-red-700 font-bold text-xl">&times;</button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <!-- Form -->
    <form method="post" action="?p=admin/settings" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <div class="space-y-6">
            <!-- School Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Sekolah <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    name="school_name"
                    value="<?= htmlspecialchars($schoolName) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                    required
                    placeholder="Masukkan nama sekolah">
                <p class="text-xs text-gray-500 mt-1">Nama sekolah yang akan ditampilkan di header</p>
            </div>

            <!-- Current Logo Preview -->
            <?php if ($logoPath && file_exists(__DIR__ . '/../../' . $logoPath)): ?>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Logo Saat Ini</label>
                    <div class="flex items-center gap-4">
                        <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo Sekolah" class="h-20 w-20 rounded-full border-2 border-gray-300 object-cover">
                        <p class="text-sm text-gray-600">Logo akan ditampilkan di header aplikasi</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Logo Upload -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Unggah Logo Sekolah</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer" id="uploadArea">
                    <input
                        type="file"
                        name="logo"
                        id="logoInput"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        class="hidden">
                    <div id="uploadPlaceholder">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">
                            Klik atau drag file logo di sini
                        </p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF, atau WebP (Max 5MB)</p>
                    </div>
                    <div id="uploadPreview" class="hidden">
                        <img id="previewImage" src="" alt="Preview" class="mx-auto h-20 w-20 rounded-full object-cover mb-2">
                        <p id="fileName" class="text-sm text-gray-700"></p>
                        <button type="button" class="text-sm text-blue-600 hover:text-blue-800 mt-2 underline">Pilih file lain</button>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Opsional. Jika dikosongkan, logo lama akan tetap digunakan atau tidak ada logo.</p>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex gap-3">
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                💾 Simpan Pengaturan
            </button>
            <a href="?p=admin/dashboard" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-semibold transition">
                ← Kembali
            </a>
        </div>
    </form>
</div>

<script>
    const uploadArea = document.getElementById('uploadArea');
    const logoInput = document.getElementById('logoInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const uploadPreview = document.getElementById('uploadPreview');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');

    // Click to upload
    uploadArea.addEventListener('click', () => logoInput.click());

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('bg-blue-50', 'border-blue-500');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('bg-blue-50', 'border-blue-500');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('bg-blue-50', 'border-blue-500');
        if (e.dataTransfer.files.length > 0) {
            logoInput.files = e.dataTransfer.files;
            handleFileSelect();
        }
    });

    // File input change
    logoInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        if (logoInput.files.length > 0) {
            const file = logoInput.files[0];
            const reader = new FileReader();

            reader.onload = (e) => {
                previewImage.src = e.target.result;
                fileName.textContent = file.name;
                uploadPlaceholder.classList.add('hidden');
                uploadPreview.classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        }
    }

    // Click to change file
    document.querySelector('#uploadPreview button').addEventListener('click', () => {
        logoInput.click();
    });
</script>