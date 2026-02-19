<?php
// views/auth/login.php
?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-sky-50 to-gray-50 px-4 py-10">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <!-- Logo or Default Icon (small, subtle) -->
            <div class="mb-4 inline-block">
                <?php if ($logoPath && file_exists(__DIR__ . '/../../' . $logoPath)): ?>
                    <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo Sekolah" class="h-16 w-16 rounded-full object-cover mx-auto">
                <?php else: ?>
                    <div class="bg-indigo-600 text-white rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a2 2 0 00-2 2v2H6a2 2 0 00-2 2v4h12V8a2 2 0 00-2-2h-2V4a2 2 0 00-2-2z" />
                        </svg>
                    </div>
                <?php endif; ?>
            </div>
            <h1 class="text-2xl font-semibold text-gray-900 mb-1">eJurnal</h1>
            <p class="text-sm text-gray-600"><?= htmlspecialchars($schoolName) ?></p>
            <p class="text-xs text-gray-500 mt-1">Sistem Jurnal Mengajar</p>
        </div>

        <!-- Error Message -->
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-100 text-red-700 rounded-md text-sm">
                <?php echo htmlspecialchars($_SESSION['flash_error']);
                unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <form method="post" action="?p=login" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-5">
            <!-- Username Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input
                    name="username"
                    type="text"
                    placeholder="Masukkan username"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-200 text-sm"
                    required
                    autocomplete="username">
            </div>

            <!-- Password Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input
                    name="password"
                    type="password"
                    placeholder="Masukkan password"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-200 text-sm"
                    required
                    autocomplete="current-password">
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg shadow-sm hover:bg-indigo-600 focus:outline-none">
                Masuk
            </button>
        </form>
    </div>
</div>