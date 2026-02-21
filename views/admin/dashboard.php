<?php
// views/admin/dashboard.php
$formatDate = static function ($date): string {
    if (empty($date)) {
        return '-';
    }
    $ts = strtotime((string)$date);
    return $ts ? date('d M Y', $ts) : (string)$date;
};

$formatStatus = static function ($status): string {
    $s = strtolower(trim((string)$status));
    if ($s === 'verified') {
        return 'Terverifikasi';
    }
    if ($s === 'rejected') {
        return 'Ditolak';
    }
    if ($s === 'pending') {
        return 'Menunggu';
    }
    return $status ?: '-';
};
?>
<div class="space-y-5 bg-[#fafafa]">
    <section class="rounded-xl border border-slate-200 bg-white px-5 py-5 md:px-6 md:py-6 shadow-sm">
        <h1 class="text-2xl md:text-3xl font-semibold text-slate-900">Dashboard Admin</h1>
        <p class="mt-1 text-sm md:text-base text-slate-600">Monitoring data inti jurnal dan validasi tugas guru.</p>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-4 md:p-5 shadow-sm">
        <p class="text-xs uppercase tracking-wide text-slate-400">Tahun Pelajaran Aktif</p>
        <p class="mt-1 text-xl md:text-2xl font-semibold text-slate-900"><?php echo htmlspecialchars($active_year['name'] ?? '-'); ?></p>
        <p class="mt-1 text-sm text-slate-500"><?php echo isset($active_year['start_date']) ? $formatDate($active_year['start_date']) . ' - ' . $formatDate($active_year['end_date']) : 'Belum ditentukan'; ?></p>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-4 md:p-5 shadow-sm">
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            <div class="rounded-lg bg-slate-50 px-3 py-3">
                <p class="text-xs uppercase tracking-wide text-slate-400">Total Guru</p>
                <p class="mt-1 text-3xl md:text-4xl font-semibold leading-none text-slate-900"><?php echo intval($teacher_count ?? 0); ?></p>
            </div>
            <div class="rounded-lg bg-slate-50 px-3 py-3">
                <p class="text-xs uppercase tracking-wide text-slate-400">Total Kelas</p>
                <p class="mt-1 text-3xl md:text-4xl font-semibold leading-none text-slate-900"><?php echo intval($class_count ?? 0); ?></p>
            </div>
            <div class="rounded-lg bg-slate-50 px-3 py-3">
                <p class="text-xs uppercase tracking-wide text-slate-400">Jurnal Bulan Ini</p>
                <p class="mt-1 text-3xl md:text-4xl font-semibold leading-none text-blue-900"><?php echo intval($journals_this_month ?? 0); ?></p>
                <p class="mt-1 text-xs text-slate-500">Total: <?php echo intval($total_journals ?? 0); ?></p>
            </div>
            <div class="rounded-lg bg-slate-50 px-3 py-3">
                <p class="text-xs uppercase tracking-wide text-slate-400">Menunggu Verifikasi</p>
                <p class="mt-1 text-3xl md:text-4xl font-semibold leading-none text-amber-600"><?php echo intval($pending_count ?? 0); ?></p>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 xl:grid-cols-12">
        <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Top Guru Bulan Ini</h2>
                <a href="index.php?p=admin/rekap-by-teacher" class="text-sm font-medium text-blue-700 hover:text-blue-800">Lihat Rekap</a>
            </div>
            <ol class="mt-4 space-y-2">
                <?php if (!empty($top_teachers)): ?>
                    <?php foreach ($top_teachers as $i => $t): ?>
                        <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2.5">
                            <span class="text-sm text-slate-800"><?php echo ($i + 1) . '. ' . htmlspecialchars($t['name']); ?></span>
                            <span class="rounded-md bg-slate-100 px-2 py-0.5 text-sm font-semibold text-slate-700"><?php echo intval($t['total_entries']); ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="rounded-lg bg-slate-50 px-3 py-2.5 text-sm text-slate-500">Belum ada data guru.</li>
                <?php endif; ?>
            </ol>
        </article>

        <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-4">
            <h2 class="text-lg font-semibold text-slate-900">Jurnal Terbaru</h2>
            <ul class="mt-4 space-y-2">
                <?php if (!empty($recent_journals)): ?>
                    <?php foreach ($recent_journals as $rj): ?>
                        <li class="rounded-lg bg-slate-50 px-3 py-2.5">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm text-slate-800"><?php echo htmlspecialchars($rj['teacher_name']); ?> - <?php echo htmlspecialchars($rj['class_name']); ?></span>
                                <span class="text-xs text-slate-500"><?php echo htmlspecialchars($formatDate($rj['date'] ?? null)); ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="rounded-lg bg-slate-50 px-3 py-2.5 text-sm text-slate-500">Tidak ada jurnal terbaru.</li>
                <?php endif; ?>
            </ul>
        </article>

        <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-4">
            <h2 class="text-lg font-semibold text-slate-900">Tugas Terbaru</h2>
            <ul class="mt-4 space-y-2">
                <?php if (!empty($recent_tasks)): ?>
                    <?php foreach ($recent_tasks as $rt): ?>
                        <li class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-3 py-2.5">
                            <span class="text-sm text-slate-800"><?php echo htmlspecialchars($rt['teacher_name']); ?> - <?php echo htmlspecialchars($rt['class_name']); ?></span>
                            <span class="rounded-md px-2.5 py-1 text-xs font-semibold <?php echo ($rt['status'] ?? '') === 'verified' ? 'bg-green-100 text-green-700' : ((($rt['status'] ?? '') === 'rejected') ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'); ?>">
                                <?php echo htmlspecialchars($formatStatus($rt['status'] ?? '')); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="rounded-lg bg-slate-50 px-3 py-2.5 text-sm text-slate-500">Tidak ada tugas terbaru.</li>
                <?php endif; ?>
            </ul>

            <div class="mt-5 border-t border-slate-100 pt-4">
                <p class="text-sm font-medium text-slate-500">Status Tugas</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-md bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                        Terverifikasi: <?php echo intval($verified_count ?? 0); ?>
                    </span>
                    <span class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                        Ditolak: <?php echo intval($rejected_count ?? 0); ?>
                    </span>
                </div>
            </div>

        </article>
    </section>
</div>
