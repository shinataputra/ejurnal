<?php
// views/admin/dashboard.php
?>
<style>
    .admin-shell {
        --ink: #0f172a;
        --muted: #64748b;
        --line: #e2e8f0;
        --card: #ffffff;
    }

    .hero {
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #eff6ff 0%, #ecfdf5 100%);
    }

    .dash-card {
        background: var(--card);
        border: 1px solid var(--line);
    }

    .kpi-value {
        color: var(--ink);
        letter-spacing: -0.02em;
    }

    .activity-list li:last-child {
        border-bottom: 0;
    }
</style>

<div class="admin-shell space-y-6">
    <section class="hero rounded-2xl p-5 md:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Dashboard Admin</h1>
                <p class="mt-1 text-sm md:text-base text-slate-600">Pantau aktivitas jurnal, tugas guru, dan status data sekolah secara ringkas.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <a href="index.php?p=admin/rekap" class="rounded-lg bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-blue-700 transition">Rekap Bulanan</a>
                <a href="index.php?p=admin/tasks" class="rounded-lg bg-emerald-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-emerald-700 transition">Verifikasi Tugas</a>
                <a href="index.php?p=admin/users" class="rounded-lg bg-slate-700 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-slate-800 transition">Kelola Guru</a>
                <a href="index.php?p=admin/classes" class="rounded-lg bg-slate-700 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-slate-800 transition">Kelola Kelas</a>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <article class="dash-card rounded-xl p-4">
            <p class="text-sm text-slate-500">Total Guru</p>
            <p class="kpi-value mt-2 text-3xl font-bold"><?php echo intval($teacher_count ?? 0); ?></p>
            <p class="mt-1 text-xs text-slate-500">Guru aktif</p>
        </article>
        <article class="dash-card rounded-xl p-4">
            <p class="text-sm text-slate-500">Total Kelas</p>
            <p class="kpi-value mt-2 text-3xl font-bold"><?php echo intval($class_count ?? 0); ?></p>
            <p class="mt-1 text-xs text-slate-500">Kelas terdaftar</p>
        </article>
        <article class="dash-card rounded-xl p-4">
            <p class="text-sm text-slate-500">Tahun Pelajaran Aktif</p>
            <p class="kpi-value mt-2 text-lg md:text-xl font-bold"><?php echo htmlspecialchars($active_year['name'] ?? '-'); ?></p>
            <p class="mt-1 text-xs text-slate-500"><?php echo isset($active_year['start_date']) ? htmlspecialchars($active_year['start_date']) . ' - ' . htmlspecialchars($active_year['end_date']) : 'Belum ditentukan'; ?></p>
        </article>
        <article class="dash-card rounded-xl p-4">
            <p class="text-sm text-slate-500">Jurnal Bulan Ini</p>
            <p class="kpi-value mt-2 text-3xl font-bold"><?php echo intval($journals_this_month ?? 0); ?></p>
            <p class="mt-1 text-xs text-slate-500">Dari total <?php echo intval($total_journals ?? 0); ?> entri</p>
        </article>
        <article class="dash-card rounded-xl p-4">
            <p class="text-sm text-slate-500">Tugas Menunggu</p>
            <p class="mt-2 text-3xl font-bold text-amber-600"><?php echo intval($pending_count ?? 0); ?></p>
            <p class="mt-1 text-xs text-slate-500">Perlu verifikasi admin</p>
        </article>
        <article class="dash-card rounded-xl p-4">
            <p class="text-sm text-slate-500">Status Tugas</p>
            <div class="mt-2 flex items-end gap-4">
                <div>
                    <p class="text-2xl font-bold text-emerald-600"><?php echo intval($verified_count ?? 0); ?></p>
                    <p class="text-xs text-slate-500">Terverifikasi</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-rose-600"><?php echo intval($rejected_count ?? 0); ?></p>
                    <p class="text-xs text-slate-500">Ditolak</p>
                </div>
            </div>
        </article>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <article class="dash-card rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Top Guru Bulan Ini</h2>
                <a href="index.php?p=admin/rekap-by-teacher" class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat Rekap</a>
            </div>
            <ol class="mt-3 space-y-2">
                <?php if (!empty($top_teachers)): ?>
                    <?php foreach ($top_teachers as $i => $t): ?>
                        <li class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                            <span class="text-sm text-slate-800"><?php echo ($i + 1) . '. ' . htmlspecialchars($t['name']); ?></span>
                            <span class="text-xs font-semibold text-slate-600"><?php echo intval($t['total_entries']); ?> entri</span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="text-sm text-slate-500">Belum ada data guru untuk bulan ini.</li>
                <?php endif; ?>
            </ol>
        </article>

        <article class="dash-card rounded-xl p-4">
            <h2 class="text-lg font-semibold text-slate-900">Aktivitas Terbaru</h2>

            <div class="mt-3">
                <p class="text-sm font-semibold text-slate-700">Jurnal Terbaru</p>
                <ul class="activity-list mt-2 divide-y divide-slate-200 rounded-lg border border-slate-200">
                    <?php if (!empty($recent_journals)): ?>
                        <?php foreach ($recent_journals as $rj): ?>
                            <li class="flex items-center justify-between gap-3 px-3 py-2 text-sm">
                                <span class="text-slate-700"><?php echo htmlspecialchars($rj['teacher_name']); ?> - <?php echo htmlspecialchars($rj['class_name']); ?></span>
                                <span class="whitespace-nowrap text-xs text-slate-500"><?php echo htmlspecialchars($rj['date']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="px-3 py-2 text-sm text-slate-500">Tidak ada jurnal terbaru.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-700">Tugas Terbaru</p>
                <ul class="activity-list mt-2 divide-y divide-slate-200 rounded-lg border border-slate-200">
                    <?php if (!empty($recent_tasks)): ?>
                        <?php foreach ($recent_tasks as $rt): ?>
                            <li class="flex items-center justify-between gap-3 px-3 py-2 text-sm">
                                <span class="text-slate-700"><?php echo htmlspecialchars($rt['teacher_name']); ?> - <?php echo htmlspecialchars($rt['class_name']); ?></span>
                                <span class="whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-semibold <?php echo ($rt['status'] ?? '') === 'verified' ? 'bg-emerald-100 text-emerald-700' : ((($rt['status'] ?? '') === 'rejected') ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700'); ?>">
                                    <?php echo htmlspecialchars($rt['status']); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="px-3 py-2 text-sm text-slate-500">Tidak ada tugas terbaru.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </article>
    </section>
</div>
