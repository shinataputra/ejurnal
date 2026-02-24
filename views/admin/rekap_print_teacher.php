<?php
$page_title = 'Cetak Rekap - ' . htmlspecialchars($user['name']);
$isGuruBk = (($user['role'] ?? '') === 'guru_bk');
?>

<style>
    @media print {
        .no-print {
            display: none;
        }

        body {
            margin: 0;
            padding: 10px;
        }

        table {
            page-break-inside: avoid;
        }
    }
</style>

<div class="container mx-auto px-4 py-6">
    <!-- Header (No Print) -->
    <div class="no-print flex flex-col md:flex-row items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Cetak Rekap Jurnal</h1>
            <p class="text-gray-600"><?= htmlspecialchars($user['name']) ?> (@<?= htmlspecialchars($user['username']) ?>) - <?= htmlspecialchars($month_display) ?></p>
        </div>
        <div class="flex gap-2">
            <a href="index.php?p=admin/rekap-by-teacher&month=<?= date('m', strtotime($month_display)) ?>&year=<?= date('Y', strtotime($month_display)) ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                ← Kembali
            </a>
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                🖨️ Cetak
            </button>
            <a href="index.php?p=admin/rekap-print-teacher&teacher_id=<?= $user['id'] ?>&month_year=<?= $month_year ?>&pdf=1" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition" target="_blank">
                📄 PDF
            </a>
        </div>
    </div>

    <!-- Print Header -->
    <div class="print-only mb-6 text-center border-b-2 border-gray-800 pb-4">
        <h2 class="text-2xl font-bold">REKAP JURNAL <?= $isGuruBk ? 'LAYANAN BK' : 'MENGAJAR' ?></h2>
        <p class="text-lg font-semibold"><?= htmlspecialchars($user['name']) ?></p>
        <p class="text-base"><?= htmlspecialchars($user['username']) ?></p>
        <p class="text-lg"><?= htmlspecialchars($month_display) ?></p>
    </div>

    <!-- Journal Entries -->
    <?php if (!empty($journals)): ?>
        <!-- Desktop View (Hidden on Mobile) -->
        <div class="hidden md:block bg-white rounded-lg shadow-md p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-200 border-b-2 border-gray-600">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold">Tanggal</th>
                        <th class="px-3 py-2 text-left font-semibold">Kelas</th>
                        <th class="px-3 py-2 text-left font-semibold">Mata Pelajaran</th>
                        <th class="px-3 py-2 text-left font-semibold">Jam Ke</th>
                        <?php if ($isGuruBk): ?>
                            <th class="px-3 py-2 text-left font-semibold">Sasaran</th>
                            <th class="px-3 py-2 text-left font-semibold">Layanan</th>
                            <th class="px-3 py-2 text-left font-semibold">Hasil</th>
                        <?php else: ?>
                            <th class="px-3 py-2 text-left font-semibold">Materi</th>
                        <?php endif; ?>
                        <th class="px-3 py-2 text-left font-semibold">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($journals as $j): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($j['date'])) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($j['class_name']) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($j['subject_name']) ?></td>
                            <td class="px-3 py-2 text-center"><?= $j['jam_ke'] ?></td>
                            <?php if ($isGuruBk): ?>
                                <td class="px-3 py-2"><?= htmlspecialchars(substr((string)($j['target_kegiatan'] ?? ''), 0, 40)) ?></td>
                                <td class="px-3 py-2"><?= htmlspecialchars((string)($j['kegiatan_layanan'] ?? '-')) ?></td>
                                <td class="px-3 py-2"><?= htmlspecialchars(substr((string)($j['hasil_dicapai'] ?? $j['materi'] ?? ''), 0, 40)) ?></td>
                            <?php else: ?>
                                <td class="px-3 py-2"><?= htmlspecialchars(substr($j['materi'], 0, 50)) . (strlen($j['materi']) > 50 ? '...' : '') ?></td>
                            <?php endif; ?>
                            <td class="px-3 py-2 text-xs"><?= htmlspecialchars(substr($j['notes'] ?? '', 0, 30)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile View (Hidden on Desktop) -->
        <div class="md:hidden grid grid-cols-1 gap-4">
            <?php foreach ($journals as $j): ?>
                <div class="bg-white rounded-lg shadow border-l-4 border-green-500 p-4">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-sm font-semibold text-green-600"><?= date('d/m/Y', strtotime($j['date'])) ?></span>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Jam <?= $j['jam_ke'] ?></span>
                    </div>
                    <p class="font-semibold text-gray-800 mb-1"><?= htmlspecialchars($j['subject_name']) ?></p>
                    <p class="text-xs text-gray-600 mb-2"><?= htmlspecialchars($j['class_name']) ?></p>
                    <?php if ($isGuruBk): ?>
                        <div class="bg-gray-50 rounded p-2 mb-2 text-sm">
                            <p class="font-medium text-gray-700">Sasaran:</p>
                            <p class="text-gray-600"><?= htmlspecialchars($j['target_kegiatan'] ?? '-') ?></p>
                        </div>
                        <div class="bg-gray-50 rounded p-2 mb-2 text-sm">
                            <p class="font-medium text-gray-700">Layanan:</p>
                            <p class="text-gray-600"><?= htmlspecialchars($j['kegiatan_layanan'] ?? '-') ?></p>
                        </div>
                        <div class="bg-gray-50 rounded p-2 mb-2 text-sm">
                            <p class="font-medium text-gray-700">Hasil:</p>
                            <p class="text-gray-600"><?= htmlspecialchars($j['hasil_dicapai'] ?? $j['materi']) ?></p>
                        </div>
                    <?php else: ?>
                        <div class="bg-gray-50 rounded p-2 mb-2 text-sm">
                            <p class="font-medium text-gray-700">Materi:</p>
                            <p class="text-gray-600"><?= htmlspecialchars($j['materi']) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($j['notes'])): ?>
                        <div class="bg-yellow-50 rounded p-2 text-sm border-l-2 border-yellow-400">
                            <p class="font-medium text-gray-700">Catatan:</p>
                            <p class="text-gray-600"><?= htmlspecialchars($j['notes']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-gray-700">Tidak ada data jurnal untuk periode ini.</p>
        </div>
    <?php endif; ?>

    <!-- Summary (Mobile Friendly) -->
    <?php if (!empty($journals)): ?>
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-sm text-gray-600">Total Entri</p>
                <p class="text-2xl font-bold text-blue-600"><?= count($journals) ?></p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <p class="text-sm text-gray-600">Hari Terisi</p>
                <p class="text-2xl font-bold text-green-600"><?= count(array_unique(array_map(fn($j) => $j['date'], $journals))) ?></p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <p class="text-sm text-gray-600">Kelas</p>
                <p class="text-2xl font-bold text-purple-600"><?= count(array_unique(array_map(fn($j) => $j['class_id'], $journals))) ?></p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 text-center">
                <p class="text-sm text-gray-600">Mata Pelajaran</p>
                <p class="text-2xl font-bold text-orange-600"><?= count(array_unique(array_map(fn($j) => $j['subject_id'], $journals))) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Footer (Print Only) -->
    <div class="print-only mt-8 pt-4 border-t-2 border-gray-800 text-center text-sm text-gray-600">
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
    </div>
</div>
