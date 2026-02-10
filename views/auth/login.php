<?php
// views/auth/login.php
?>
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 px-4 py-6">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <!-- Logo or Default Icon -->
            <div class="mb-4 inline-block">
                <?php if ($logoPath && file_exists(__DIR__ . '/../../' . $logoPath)): ?>
                    <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo Sekolah" class="h-16 w-16 rounded-full border-2 border-blue-600 object-cover mx-auto">
                <?php else: ?>
                    <div class="bg-blue-600 text-white rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">eJurnal</h1>
            <p class="text-sm md:text-base text-gray-600"><?= htmlspecialchars($schoolName) ?></p>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Sistem Jurnal Mengajar</p>
        </div>

        <!-- Error Message -->
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span><?php echo htmlspecialchars($_SESSION['flash_error']);
                            unset($_SESSION['flash_error']); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <form method="post" action="?p=login" class="bg-white rounded-lg shadow-lg p-6 md:p-8 space-y-5">
            <!-- Username Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input
                    name="username"
                    type="text"
                    placeholder="Masukkan username"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                    required
                    autocomplete="current-password">
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 transform hover:scale-105 active:scale-95">
                Masuk
            </button>
        </form>

        <!-- Footer Info -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-center">
            <p class="text-xs md:text-sm text-gray-600 mb-2">
                <strong>Demo Login:</strong>
            </p>
            <div class="space-y-1 text-xs text-gray-700">
                <p><span class="font-semibold">Guru:</span> guru1 / guru123</p>
                <p><span class="font-semibold">Admin:</span> admin / admin123</p>
            </div>
        </div>
    </div>
</div>