<?php
// views/admin/dashboard.php
?>
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Admin</h1>
        <p class="text-gray-600">Kelola data sekolah dan monitor aktivitas jurnal</p>
    </div>

    <!-- Masonry-style container for compact admin cards -->
    <style>
        .dashboard-masonry {
            column-count: 3;
            column-gap: 1rem;
        }

        @media (max-width: 1024px) {
            .dashboard-masonry {
                column-count: 2;
            }
        }

        @media (max-width: 640px) {
            .dashboard-masonry {
                column-count: 1;
            }
        }

        .dashboard-card {
            display: inline-block;
            width: 100%;
            margin: 0 0 1rem;
            break-inside: avoid;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
        }

        .dashboard-card .card-header {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .metrics-row {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .metrics-item {
            display: flex;
            flex-direction: column;
        }

        .metric-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }

        .metric-note {
            font-size: 0.75rem;
            color: #6b7280;
        }
    </style>

    <div class="dashboard-masonry mt-4">
        <!-- Total Teachers Card -->
        <div class="dashboard-card">
            <div class="card-header">Total Guru</div>
            <div class="metrics-row">
                <div class="metrics-item">
                    <div class="metric-value"><?php echo intval($teacher_count ?? 0); ?></div>
                    <div class="metric-note">Guru aktif</div>
                </div>
            </div>
        </div>

        <!-- Total Classes Card -->
        <div class="dashboard-card">
            <div class="card-header">Total Kelas</div>
            <div class="metrics-row">
                <div class="metrics-item">
                    <div class="metric-value"><?php echo intval($class_count ?? 0); ?></div>
                    <div class="metric-note">Kelas terdaftar</div>
                </div>
            </div>
        </div>

        <!-- Active Academic Year Card -->
        <div class="dashboard-card">
            <div class="card-header">Tahun Aktif</div>
            <div class="metrics-row">
                <div class="metrics-item">
                    <div class="metric-value"><?php echo htmlspecialchars($active_year['name'] ?? '-'); ?></div>
                    <div class="metric-note"><?php echo isset($active_year['start_date']) ? htmlspecialchars($active_year['start_date']) . ' → ' . htmlspecialchars($active_year['end_date']) : 'Tahun pelajaran'; ?></div>
                </div>
            </div>
        </div>

        <!-- Tasks summary -->
        <div class="dashboard-card">
            <div class="card-header">Ringkasan Tugas</div>
            <div class="mt-1 metrics-row">
                <div class="metrics-item">
                    <div class="metric-value"><?php echo intval($pending_count ?? 0); ?></div>
                    <div class="metric-note">Menunggu</div>
                </div>
                <div class="metrics-item">
                    <div class="metric-value" style="color:#15803d"><?php echo intval($verified_count ?? 0); ?></div>
                    <div class="metric-note">Terverifikasi</div>
                </div>
                <div class="metrics-item">
                    <div class="metric-value" style="color:#b91c1c"><?php echo intval($rejected_count ?? 0); ?></div>
                    <div class="metric-note">Ditolak</div>
                </div>
            </div>
            <div class="mt-2" style="font-size:0.85rem;color:#6b7280;">Ringkasan status tugas pada periode berjalan.</div>
        </div>

        <!-- Journals summary -->
        <div class="dashboard-card">
            <div class="card-header">Ringkasan Jurnal</div>
            <div class="mt-1 metrics-row">
                <div class="metrics-item">
                    <div class="metric-value"><?php echo intval($total_journals ?? 0); ?></div>
                    <div class="metric-note">Total entri</div>
                </div>
                <div class="metrics-item">
                    <div class="metric-value"><?php echo intval($journals_this_month ?? 0); ?></div>
                    <div class="metric-note">Bulan ini</div>
                </div>
            </div>
            <div class="mt-2" style="font-size:0.85rem;color:#6b7280;">Top guru bulan ini:</div>
            <ol class="mt-2" style="font-size:0.9rem;color:#111827; margin-left:1rem;">
                <?php if (!empty($top_teachers)): foreach ($top_teachers as $t): ?>
                        <li style="display:flex;justify-content:space-between;"><span><?php echo htmlspecialchars($t['name']); ?></span><span style="color:#6b7280;font-size:0.8rem"><?php echo intval($t['total_entries']); ?> entri</span></li>
                    <?php endforeach;
                else: ?>
                    <li style="color:#6b7280;font-size:0.9rem">Belum ada data.</li>
                <?php endif; ?>
            </ol>
        </div>

        <!-- Recent activity -->
        <div class="dashboard-card">
            <div class="card-header">Aktivitas Terbaru</div>
            <div class="mt-1" style="font-size:0.9rem;color:#111827;">
                <div style="font-weight:600;margin-bottom:0.25rem;">Jurnal Terbaru</div>
                <ul style="margin:0;padding-left:1rem;font-size:0.85rem;color:#374151;">
                    <?php if (!empty($recent_journals)): foreach ($recent_journals as $rj): ?>
                            <li style="display:flex;justify-content:space-between;margin-bottom:0.25rem;"><span><?php echo htmlspecialchars($rj['teacher_name']); ?> — <?php echo htmlspecialchars($rj['class_name']); ?></span><span style="color:#6b7280;font-size:0.8rem"><?php echo htmlspecialchars($rj['date']); ?></span></li>
                        <?php endforeach;
                    else: ?>
                        <li style="color:#6b7280">Tidak ada jurnal.</li>
                    <?php endif; ?>
                </ul>

                <div style="font-weight:600;margin-top:0.5rem;margin-bottom:0.25rem;">Tugas Terbaru</div>
                <ul style="margin:0;padding-left:1rem;font-size:0.85rem;color:#374151;">
                    <?php if (!empty($recent_tasks)): foreach ($recent_tasks as $rt): ?>
                            <li style="display:flex;justify-content:space-between;margin-bottom:0.25rem;"><span><?php echo htmlspecialchars($rt['teacher_name']); ?> — <?php echo htmlspecialchars($rt['class_name']); ?></span><span style="color:#6b7280;font-size:0.8rem"><?php echo htmlspecialchars($rt['status']); ?></span></li>
                        <?php endforeach;
                    else: ?>
                        <li style="color:#6b7280">Tidak ada tugas.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Per-class recap removed as requested; dashboard cards now adapt to content width -->
</div>