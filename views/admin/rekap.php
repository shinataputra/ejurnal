<?php
$page_title = 'Rekap Jurnal';
?>

<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Rekap Jurnal Bulanan</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Recap by Class -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center mb-4">
                <div class="text-4xl text-blue-500 mr-4">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800">Rekap Per Kelas</h2>
            </div>
            <p class="text-gray-600 mb-6">Lihat rekap jurnal mengajar berdasarkan kelas untuk periode bulanan yang dipilih.</p>
            <a href="index.php?p=admin/rekap-by-class" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                Buka Rekap Per Kelas
            </a>
        </div>

        <!-- Recap by Teacher -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center mb-4">
                <div class="text-4xl text-green-500 mr-4">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800">Rekap Per Guru</h2>
            </div>
            <p class="text-gray-600 mb-6">Lihat rekap jurnal mengajar berdasarkan guru (pendidik) untuk periode bulanan yang dipilih.</p>
            <a href="index.php?p=admin/rekap-by-teacher" class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                Buka Rekap Per Guru
            </a>
        </div>
    </div>
</div>