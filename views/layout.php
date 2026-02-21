<?php
// views/layout.php
// Load settings
require_once __DIR__ . '/../models/Settings.php';
$settingsModel = new Settings();
$schoolName = $settingsModel->get('school_name') ?? 'SMKN 1 Probolinggo';
$logoPath = $settingsModel->get('logo_path');

// Detect if this is a login page
$isLoginPage = isset($requestedView) && strpos($requestedView, 'auth/login.php') !== false;
$currentPath = $_GET['p'] ?? '';
$adminNavClass = static function (array $paths) use ($currentPath): string {
    $active = in_array($currentPath, $paths, true);
    if ($active) {
        return 'block px-3 py-2.5 rounded-lg text-sm font-medium text-blue-900 bg-blue-50 ring-1 ring-blue-100';
    }
    return 'block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition';
};
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>eJurnal - SMKN 1 Probolinggo</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body class="<?= $isLoginPage ? 'bg-gradient-to-br from-blue-50 to-indigo-100' : 'bg-[#fafafa] text-slate-800' ?>">
    <?php if ($isLoginPage): ?>
        <!-- Login Page (Full Screen) -->
        <?php
        // include login view
        if (isset($requestedView) && file_exists($requestedView)) {
            include $requestedView;
        } else {
            echo "<p>View not found.</p>";
        }
        ?>
    <?php else: ?>
        <!-- Check if user is teacher -->
        <?php $isTeacher = (!empty($current_user) && $current_user['role'] === 'teacher'); ?>

        <?php if ($isTeacher): ?>
            <!-- Teacher Layout (No Sidebar) -->
            <div class="flex flex-col h-screen bg-gray-100">
                <!-- Top Header with Logout -->
                <header class="bg-white shadow-sm sticky top-0 z-30">
                    <div class="max-w-6xl mx-auto px-3 sm:px-4 py-2 sm:py-3">
                        <!-- Mobile: Single row layout -->
                        <div class="flex md:hidden items-center justify-between">
                            <!-- Left: Logo and Title -->
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <?php if ($logoPath && file_exists(__DIR__ . '/../' . $logoPath)): ?>
                                    <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo" class="h-10 w-10 rounded-full object-cover border border-gray-300 flex-shrink-0">
                                <?php endif; ?>
                                <div class="min-w-0">
                                    <h1 class="text-lg font-bold text-gray-900 truncate"><a href="?p=teacher/dashboard">eJurnal</a></h1>
                                    <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($schoolName) ?></p>
                                </div>
                            </div>
                            <!-- Right: Profile Dropdown -->
                            <div class="relative ml-2">
                                <button onclick="toggleTeacherProfileDropdownMobile()" class="flex items-center justify-center h-9 w-9 rounded-full bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 flex-shrink-0">
                                    <?= strtoupper(substr($current_user['name'], 0, 1)) ?>
                                </button>
                                <!-- Dropdown Menu -->
                                <div id="teacherProfileDropdownMobile" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-200">
                                    <div class="p-4 border-b border-gray-200">
                                        <p class="text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($current_user['name']) ?></p>
                                        <p class="text-xs text-gray-500 capitalize">👨‍🏫 Guru</p>
                                    </div>
                                    <a href="?p=teacher/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        👤 Profil
                                    </a>
                                    <a href="?p=logout" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition border-t border-gray-200 font-semibold">
                                        🚪 Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Desktop: Full layout -->
                        <div class="hidden md:flex items-center justify-between">
                            <!-- Left Section: Logo and Title -->
                            <div class="flex items-center gap-3 min-w-0">
                                <?php if ($logoPath && file_exists(__DIR__ . '/../' . $logoPath)): ?>
                                    <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo" class="h-12 w-12 rounded-full object-cover border-2 border-gray-200 flex-shrink-0">
                                <?php endif; ?>
                                <div class="min-w-0">
                                    <h1 class="text-2xl font-bold text-gray-900 truncate"><a href="?p=teacher/dashboard">eJurnal</a></h1>
                                    <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($schoolName) ?></p>
                                </div>
                            </div>
                            <!-- Right Section: User Profile Dropdown -->
                            <div class="relative">
                                <button onclick="toggleTeacherProfileDropdownDesktop()" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-semibold text-sm flex-shrink-0">
                                        <?= strtoupper(substr($current_user['name'], 0, 1)) ?>
                                    </div>
                                    <span class="text-sm text-gray-600 truncate"><?= htmlspecialchars($current_user['name']) ?></span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </button>
                                <!-- Dropdown Menu -->
                                <div id="teacherProfileDropdownDesktop" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-200">
                                    <div class="p-4 border-b border-gray-200">
                                        <p class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($current_user['name']) ?></p>
                                        <p class="text-xs text-gray-500 capitalize">👨‍🏫 Guru</p>
                                    </div>
                                    <a href="?p=teacher/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        👤 Profil
                                    </a>
                                    <a href="?p=logout" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition border-t border-gray-200 font-semibold">
                                        🚪 Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6">
                    <div class="max-w-6xl mx-auto">
                        <?php
                        // include requested view
                        if (isset($requestedView) && file_exists($requestedView)) {
                            include $requestedView;
                        } else {
                            echo "<p>View not found.</p>";
                        }
                        ?>
                    </div>
                </main>

                <!-- Footer -->
                <footer class="bg-white border-t border-gray-200 p-4 text-center">
                    <p class="text-xs text-gray-500">&copy; 2026 ICT - SMKN 1 PROBOLINGGO</p>
                </footer>
            </div>
        <?php else: ?>
            <!-- Admin Layout (With Sidebar) -->
            <div class="flex h-screen bg-[#fafafa]">
                <!-- Sidebar -->
                <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white text-slate-800 transform -translate-x-full md:translate-x-0 md:static md:inset-auto transition-transform duration-300 ease-in-out border-r border-slate-200 shadow-sm">
                    <!-- Sidebar Header -->
                    <div class="p-5 border-b border-slate-100">
                        <div class="flex items-center gap-3 mb-2">
                            <?php if ($logoPath && file_exists(__DIR__ . '/../' . $logoPath)): ?>
                                <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo" class="h-8 w-8 rounded-md object-cover border border-slate-200">
                            <?php endif; ?>
                            <h2 class="text-lg font-semibold text-slate-900"><a href="?p=admin/dashboard">eJurnal</a></h2>
                        </div>
                        <p class="text-xs text-slate-500"><?= htmlspecialchars($schoolName) ?></p>
                    </div>

                    <!-- User Info -->
                    <?php if (!empty($current_user)): ?>
                        <div class="px-5 py-4 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($current_user['name']) ?></p>
                            <p class="text-xs text-slate-500 capitalize"><?= htmlspecialchars($current_user['role']) ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Navigation Menu -->
                    <nav class="flex-1 px-4 py-4 space-y-3">
                        <?php if (!empty($current_user)): ?>
                            <?php if ($current_user['role'] === 'teacher'): ?>
                                <!-- Teacher Menu -->
                                <a href="?p=teacher/dashboard" class="block px-3 py-2 rounded-md hover:bg-gray-100 transition text-sm text-gray-700">
                                    📊 Dashboard
                                </a>
                                <a href="?p=teacher/add" class="block px-3 py-2 rounded-md hover:bg-gray-100 transition text-sm text-gray-700">
                                    ➕ Tambah Jurnal
                                </a>
                                <a href="?p=teacher/list" class="block px-3 py-2 rounded-md hover:bg-gray-100 transition text-sm text-gray-700">
                                    📋 Lihat Jurnal
                                </a>
                                <a href="?p=teacher/send-task" class="block px-3 py-2 rounded-md hover:bg-gray-100 transition text-sm text-gray-700">
                                    📤 Kirim Tugas Kelas
                                </a>
                            <?php else: ?>
                                <!-- Admin Menu -->
                                <a href="?p=admin/dashboard" class="<?= $adminNavClass(['admin/dashboard']) ?>">
                                    Dashboard
                                </a>
                                <div class="pt-2 border-t border-slate-100">
                                    <p class="px-3 py-1.5 text-[11px] font-semibold tracking-wide text-slate-400 uppercase">Master Data</p>
                                    <a href="?p=admin/users" class="<?= $adminNavClass(['admin/users', 'admin/users-add', 'admin/users-edit']) ?>">
                                        Kelola Pengguna
                                    </a>
                                    <a href="?p=admin/classes" class="<?= $adminNavClass(['admin/classes', 'admin/classes-add', 'admin/classes-edit']) ?>">
                                        Kelola Kelas
                                    </a>
                                    <a href="?p=admin/subjects" class="<?= $adminNavClass(['admin/subjects', 'admin/subjects-add', 'admin/subjects-edit']) ?>">
                                        Kelola Mapel
                                    </a>
                                    <a href="?p=admin/academic_years" class="<?= $adminNavClass(['admin/academic_years', 'admin/academic_years-add', 'admin/academic_years-edit']) ?>">
                                        Tahun Pelajaran
                                    </a>
                                </div>
                                <div class="pt-2 border-t border-slate-100">
                                    <p class="px-3 py-1.5 text-[11px] font-semibold tracking-wide text-slate-400 uppercase">Validasi</p>
                                    <a href="?p=admin/tasks" class="<?= $adminNavClass(['admin/tasks', 'admin/task-view']) ?>">
                                        Tugas Guru
                                    </a>
                                </div>
                                <div class="pt-2 border-t border-slate-100">
                                    <p class="px-3 py-1.5 text-[11px] font-semibold tracking-wide text-slate-400 uppercase">Rekap & Laporan</p>
                                    <a href="?p=admin/rekap" class="<?= $adminNavClass(['admin/rekap', 'admin/rekap-by-class', 'admin/rekap-by-teacher', 'admin/rekap-print-class', 'admin/rekap-print-teacher']) ?>">
                                        Rekap Jurnal
                                    </a>
                                </div>
                                <div class="pt-2 border-t border-slate-100">
                                    <a href="?p=admin/settings" class="<?= $adminNavClass(['admin/settings']) ?>">
                                        Pengaturan Sekolah
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </nav>

                    <!-- Sidebar Footer -->
                    <div class="p-4 border-t border-slate-100 space-y-2">
                        <?php if (!empty($current_user)): ?>
                            <a href="?p=logout" class="block w-full text-center px-4 py-2.5 rounded-lg bg-red-50 hover:bg-red-100 transition text-sm font-semibold text-red-600">
                                🚪 Logout
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Overlay for mobile -->
                <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden" onclick="toggleSidebar()"></div>

                <!-- Main Content -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <!-- Top Header -->
                    <header class="bg-white border-b border-slate-200 md:hidden sticky top-0 z-30 shadow-sm">
                        <div class="flex items-center justify-between p-3">
                            <!-- Left: Menu Button -->
                            <button id="menuToggle" onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-slate-100 transition flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <!-- Center: Title -->
                            <h1 class="text-base font-bold text-slate-900 truncate flex-1 text-center px-2"><a href="?p=admin/dashboard">eJurnal</a></h1>
                            <!-- Right: User Profile Dropdown -->
                            <div class="relative flex-shrink-0">
                                <button onclick="toggleAdminProfileDropdown()" class="flex items-center justify-center h-9 w-9 rounded-full bg-slate-100 text-slate-700 font-semibold text-sm hover:bg-slate-200 transition focus:outline-none focus:ring-2 focus:ring-slate-200">
                                    <?= strtoupper(substr($current_user['name'], 0, 1)) ?>
                                </button>
                                <!-- Dropdown Menu -->
                                <div id="adminProfileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow z-50 border border-slate-200">
                                    <div class="p-4 border-b border-slate-200">
                                        <p class="text-sm font-semibold text-slate-900 truncate"><?= htmlspecialchars($current_user['name']) ?></p>
                                        <p class="text-xs text-slate-500 capitalize">👨‍💼 Admin</p>
                                    </div>
                                    <a href="?p=admin/profile" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                        👤 Profil
                                    </a>
                                    <a href="?p=logout" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition border-t border-slate-200 font-semibold">
                                        🚪 Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="flex-1 overflow-y-auto bg-[#fafafa] p-4 md:p-6">
                        <div class="max-w-6xl mx-auto">
                            <?php
                            // include requested view
                            if (isset($requestedView) && file_exists($requestedView)) {
                                include $requestedView;
                            } else {
                                echo "<p>View not found.</p>";
                            }
                            ?>
                        </div>
                    </main>

                    <!-- Footer -->
                    <footer class="bg-white border-t border-slate-200 p-4 text-center">
                        <p class="text-xs text-slate-500">&copy; 2026 ICT - SMKN 1 PROBOLINGGO</p>
                    </footer>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');

                sidebar.classList.toggle('-translate-x-full');
                sidebar.classList.toggle('translate-x-0');
                overlay.classList.toggle('hidden');
            }

            function toggleTeacherProfileDropdownMobile() {
                const dropdown = document.getElementById('teacherProfileDropdownMobile');
                if (dropdown) dropdown.classList.toggle('hidden');
            }

            function toggleTeacherProfileDropdownDesktop() {
                const dropdown = document.getElementById('teacherProfileDropdownDesktop');
                if (dropdown) dropdown.classList.toggle('hidden');
            }

            function toggleAdminProfileDropdown() {
                const dropdown = document.getElementById('adminProfileDropdown');
                if (dropdown) dropdown.classList.toggle('hidden');
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                const teacherDropdownMobile = document.getElementById('teacherProfileDropdownMobile');
                const teacherDropdownDesktop = document.getElementById('teacherProfileDropdownDesktop');
                const adminDropdown = document.getElementById('adminProfileDropdown');

                if (teacherDropdownMobile && !event.target.closest('div').contains(teacherDropdownMobile)) {
                    teacherDropdownMobile.classList.add('hidden');
                }

                if (teacherDropdownDesktop && !event.target.closest('div').contains(teacherDropdownDesktop)) {
                    teacherDropdownDesktop.classList.add('hidden');
                }

                if (adminDropdown && !event.target.closest('div').contains(adminDropdown)) {
                    adminDropdown.classList.add('hidden');
                }
            });

            // Close sidebar when a link is clicked on mobile
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarLinks = document.querySelectorAll('#sidebar a');
                const isDesktop = window.innerWidth >= 768;

                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (!isDesktop) {
                            toggleSidebar();
                        }
                    });
                });

                // Close sidebar when window is resized to desktop
                window.addEventListener('resize', function() {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('sidebarOverlay');
                    if (window.innerWidth >= 768) {
                        sidebar.classList.remove('-translate-x-full');
                        sidebar.classList.add('translate-x-0');
                        overlay.classList.add('hidden');
                    }
                });
            });
        </script>
</body>

</html>
