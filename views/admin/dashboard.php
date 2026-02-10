<?php
// views/admin/dashboard.php
?>
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Admin</h1>
        <p class="text-gray-600">Kelola data sekolah dan monitor aktivitas jurnal</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Teachers Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6 shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-semibold mb-1">👥 TOTAL GURU</p>
                    <p class="text-3xl font-bold text-blue-900">—</p>
                    <p class="text-xs text-blue-600 mt-2">Guru aktif</p>
                </div>
            </div>
        </div>

        <!-- Total Classes Card -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-6 shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-green-600 font-semibold mb-1">🎓 TOTAL KELAS</p>
                    <p class="text-3xl font-bold text-green-900">—</p>
                    <p class="text-xs text-green-600 mt-2">Kelas terdaftar</p>
                </div>
            </div>
        </div>

        <!-- Active Academic Year Card -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6 shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-purple-600 font-semibold mb-1">📅 TAHUN AKTIF</p>
                    <p class="text-2xl font-bold text-purple-900">—</p>
                    <p class="text-xs text-purple-600 mt-2">Tahun pelajaran</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4">📋 Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Add Teacher -->
            <a href="?p=admin/usersAdd" class="bg-white border-2 border-blue-200 hover:border-blue-400 rounded-lg p-4 text-center transition">
                <div class="text-2xl mb-2">➕</div>
                <p class="font-semibold text-sm text-gray-900">Tambah Guru</p>
                <p class="text-xs text-gray-500 mt-1">Daftarkan guru baru</p>
            </a>

            <!-- Add Class -->
            <a href="?p=admin/classesAdd" class="bg-white border-2 border-green-200 hover:border-green-400 rounded-lg p-4 text-center transition">
                <div class="text-2xl mb-2">🎓</div>
                <p class="font-semibold text-sm text-gray-900">Tambah Kelas</p>
                <p class="text-xs text-gray-500 mt-1">Buat kelas baru</p>
            </a>

            <!-- Add Subject -->
            <a href="?p=admin/subjectsAdd" class="bg-white border-2 border-yellow-200 hover:border-yellow-400 rounded-lg p-4 text-center transition">
                <div class="text-2xl mb-2">📚</div>
                <p class="font-semibold text-sm text-gray-900">Tambah Mapel</p>
                <p class="text-xs text-gray-500 mt-1">Daftarkan mata pelajaran</p>
            </a>

            <!-- Add Academic Year -->
            <a href="?p=admin/academicYearsAdd" class="bg-white border-2 border-purple-200 hover:border-purple-400 rounded-lg p-4 text-center transition">
                <div class="text-2xl mb-2">📅</div>
                <p class="font-semibold text-sm text-gray-900">Tahun Pelajaran</p>
                <p class="text-xs text-gray-500 mt-1">Kelola tahun akademik</p>
            </a>
        </div>
    </div>

    <!-- Management Section -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4">⚙️ Manajemen</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <!-- View Teachers -->
            <a href="?p=admin/users" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-4 flex items-center gap-3 transition">
                <div class="text-2xl">👥</div>
                <div>
                    <p class="font-semibold">Kelola Guru</p>
                    <p class="text-xs text-blue-200">Lihat dan kelola data guru</p>
                </div>
            </a>

            <!-- View Classes -->
            <a href="?p=admin/classes" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-4 flex items-center gap-3 transition">
                <div class="text-2xl">🎓</div>
                <div>
                    <p class="font-semibold">Kelola Kelas</p>
                    <p class="text-xs text-green-200">Lihat dan kelola kelas</p>
                </div>
            </a>

            <!-- View Subjects -->
            <a href="?p=admin/subjects" class="bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg p-4 flex items-center gap-3 transition">
                <div class="text-2xl">📚</div>
                <div>
                    <p class="font-semibold">Kelola Mapel</p>
                    <p class="text-xs text-yellow-200">Lihat dan kelola mata pelajaran</p>
                </div>
            </a>

            <!-- View Academic Years -->
            <a href="?p=admin/academicYears" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-4 flex items-center gap-3 transition">
                <div class="text-2xl">📅</div>
                <div>
                    <p class="font-semibold">Tahun Pelajaran</p>
                    <p class="text-xs text-purple-200">Kelola tahun akademik aktif</p>
                </div>
            </a>
        </div>
    </div>
</div>