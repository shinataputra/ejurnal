<?php
// controllers/TeacherController.php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Journal.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/AcademicYear.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';

class TeacherController extends Controller
{
    protected const BK_SERVICE_OPTIONS = [
        'Konseling individu',
        'Konseling kelompok',
        'Bimbingan kelompok',
        'Bimbingan klasikal',
        'Konsultasi',
        'Kolaborasi',
        'Mediasi',
        'Alih tangan kasus/Referal',
        'Kunjungan rumah/home visit',
        'Layanan advokasi',
        'Konferensi kasus',
    ];

    protected $journalModel;
    protected $classModel;
    protected $subjectModel;
    protected $ayModel;
    protected $taskModel;
    protected $userModel;

    public function __construct()
    {
        $this->journalModel = new Journal();
        $this->classModel = new ClassModel();
        $this->subjectModel = new Subject();
        $this->ayModel = new AcademicYear();
        $this->taskModel = new Task();
        $this->userModel = new User();
    }

    protected function requireTeacher()
    {
        $role = $_SESSION['user']['role'] ?? '';
        if (empty($_SESSION['user']) || !in_array($role, ['teacher', 'guru_bk'], true)) {
            $_SESSION['flash_error'] = 'Silakan login sebagai guru untuk mengakses halaman ini.';
            $this->redirect('index.php?p=login');
        }
    }

    protected function isGuruBk()
    {
        return (($_SESSION['user']['role'] ?? '') === 'guru_bk');
    }

    protected function findBkSubjectId(array $subjects)
    {
        foreach ($subjects as $subject) {
            $name = strtolower(trim((string)($subject['name'] ?? '')));
            $name = preg_replace('/\s+/', ' ', $name);
            if ($name === 'bimbingan konseling' || $name === 'bimbingan dan konseling') {
                return (int)$subject['id'];
            }
        }

        // Fallback: match any subject containing both words.
        foreach ($subjects as $subject) {
            $name = strtolower((string)($subject['name'] ?? ''));
            if (strpos($name, 'bimbingan') !== false && strpos($name, 'konseling') !== false) {
                return (int)$subject['id'];
            }
        }

        return 0;
    }

    public function dashboard()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];
        $today_filled = $this->journalModel->countByUserAndDate($current_user['id'], date('Y-m-d')) > 0;
        $this->render('teacher/dashboard.php', [
            'current_user' => $current_user,
            'today_filled' => $today_filled
        ]);
    }

    public function add()
    {
        $this->requireTeacher();
        $isGuruBk = $this->isGuruBk();
        $subjects = $this->subjectModel->all();
        $bkSubjectId = $this->findBkSubjectId($subjects);
        if ($isGuruBk && $bkSubjectId <= 0) {
            // Auto-create default BK subject when missing.
            $this->subjectModel->create('Bimbingan Konseling');
            $subjects = $this->subjectModel->all();
            $bkSubjectId = $this->findBkSubjectId($subjects);
            if ($bkSubjectId <= 0) {
                $_SESSION['flash_error'] = 'Mapel "Bimbingan Konseling" belum tersedia. Hubungi admin.';
                $this->redirect('index.php?p=teacher/dashboard');
                return;
            }
        }
        if ($isGuruBk && !$this->journalModel->supportsBkFields()) {
            $_SESSION['flash_error'] = 'Database belum mendukung jurnal Guru BK. Jalankan migrasi database terlebih dahulu.';
            $this->redirect('index.php?p=teacher/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_SESSION['user'];
            $subjectId = (int)($_POST['subject_id'] ?? 0);
            if ($isGuruBk && $bkSubjectId > 0) {
                $subjectId = $bkSubjectId;
            }

            $targetKegiatan = trim((string)($_POST['target_kegiatan'] ?? ''));
            $kegiatanLayanan = trim((string)($_POST['kegiatan_layanan'] ?? ''));
            $hasilDicapai = trim((string)($_POST['hasil_dicapai'] ?? ''));
            $notes = trim((string)($_POST['notes'] ?? ''));

            if ($isGuruBk) {
                if ($targetKegiatan === '' || $kegiatanLayanan === '' || $hasilDicapai === '') {
                    $_SESSION['flash_error'] = 'Form Guru BK belum lengkap.';
                    $this->redirect('index.php?p=teacher/add');
                    return;
                }

                if (!in_array($kegiatanLayanan, self::BK_SERVICE_OPTIONS, true)) {
                    $_SESSION['flash_error'] = 'Kegiatan layanan tidak valid.';
                    $this->redirect('index.php?p=teacher/add');
                    return;
                }
            }

            try {
                $this->journalModel->create([
                    'user_id' => $current['id'],
                    'date' => $_POST['date'] ?? date('Y-m-d'),
                    'class_id' => $_POST['class_id'] ?? 0,
                    'subject_id' => $subjectId,
                    'jam_ke' => $_POST['jam_ke'] ?? '',
                    'materi' => $isGuruBk ? $hasilDicapai : ($_POST['materi'] ?? ''),
                    'notes' => $notes,
                    'target_kegiatan' => $isGuruBk ? $targetKegiatan : '',
                    'kegiatan_layanan' => $isGuruBk ? $kegiatanLayanan : '',
                    'hasil_dicapai' => $isGuruBk ? $hasilDicapai : ''
                ]);
                $_SESSION['flash_success'] = 'Jurnal disimpan.';
                $this->redirect('index.php?p=teacher/dashboard');
            } catch (\PDOException $e) {
                $_SESSION['flash_error'] = 'Gagal menyimpan jurnal: ' . $e->getMessage();
                $this->redirect('index.php?p=teacher/add');
            }
        }
        $classes = $this->classModel->all();

        // build jenjang list and rombel mapping from class names
        $jenjangs = [];
        $rombelsByJenjang = []; // jenjang => array of ['id'=>..., 'rombel'=>...]
        foreach ($classes as $c) {
            $name = trim($c['name']);
            // normalize: allow formats like "X-IPA" or "X IPA" or "X RPL1"
            $parts = preg_split('/[-\s]+/', $name, 2);
            $jenjang = $parts[0] ?? '';
            $rombel = $parts[1] ?? '';
            if ($jenjang === '') continue;
            if (!in_array($jenjang, $jenjangs)) $jenjangs[] = $jenjang;
            $rombelsByJenjang[$jenjang][] = ['id' => $c['id'], 'rombel' => $rombel];
        }

        $this->render('teacher/add_journal.php', [
            'classes' => $classes,
            'subjects' => $subjects,
            'is_guru_bk' => $isGuruBk,
            'bk_subject_id' => $bkSubjectId,
            'bk_service_options' => self::BK_SERVICE_OPTIONS,
            'jenjangs' => $jenjangs,
            'rombelsByJenjang' => $rombelsByJenjang,
            'current_user' => $_SESSION['user'] ?? null
        ]);
    }

    public function list()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];
        $journals = [];
        $filter_date = $_GET['date'] ?? date('Y-m-d');  // Default to today
        $activeAY = $this->ayModel->active();

        if ($activeAY) {
            $journals = $this->journalModel->listByDateAndAcademicYear($filter_date, $activeAY['start_date'], $activeAY['end_date'], $current_user['id']);
        }

        $this->render('teacher/list_journal.php', [
            'current_user' => $current_user,
            'journals' => $journals,
            'activeAY' => $activeAY,
            'filter_date' => $filter_date
        ]);
    }

    public function rekap()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];

        // Get jenjangs for filter
        $classes = $this->classModel->all();
        $jenjangs = [];
        $rombelsByJenjang = [];
        foreach ($classes as $c) {
            $name = trim($c['name']);
            $parts = preg_split('/[-\s]+/', $name, 2);
            $jenjang = $parts[0] ?? '';
            $rombel = $parts[1] ?? '';
            if ($jenjang === '') continue;
            if (!in_array($jenjang, $jenjangs)) $jenjangs[] = $jenjang;
            $rombelsByJenjang[$jenjang][] = ['id' => $c['id'], 'rombel' => $rombel];
        }

        // Get date range filters
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');

        $recap = $this->journalModel->dailyRecap($current_user['id'], $start_date, $end_date);

        $this->render('teacher/rekap_daily.php', [
            'current_user' => $current_user,
            'recap' => $recap,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'jenjangs' => $jenjangs,
            'rombelsByJenjang' => $rombelsByJenjang
        ]);
    }

    public function rekapMonthly()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];

        // Get year options (from active academic year or current year)
        $activeAY = $this->ayModel->active();
        $year = $_GET['year'] ?? ($activeAY ? date('Y', strtotime($activeAY['start_date'])) : date('Y'));

        $yearOptions = [];
        for ($i = 2025; $i <= date('Y'); $i++) {
            $yearOptions[] = $i;
        }

        $monthlyData = $this->journalModel->monthlyRecap($current_user['id'], $year);

        // Month names in Indonesian
        $monthNames = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $this->render('teacher/rekap_monthly.php', [
            'current_user' => $current_user,
            'monthlyData' => $monthlyData,
            'year' => $year,
            'yearOptions' => $yearOptions,
            'monthNames' => $monthNames
        ]);
    }

    public function rekapPrint()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];

        $month_year = $_GET['month_year'] ?? date('m-Y');
        $journals = $this->journalModel->getJournalsByMonthAndUser($current_user['id'], $month_year);

        // Format month_year for display
        $parts = explode('-', $month_year);
        $month = (int)$parts[0];
        $year = (int)$parts[1];

        $monthNames = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $month_display = $monthNames[$month - 1] . ' ' . $year;

        // Generate and download PDF directly
        $vendor = __DIR__ . '/../vendor/autoload.php';
        if (file_exists($vendor)) {
            require_once $vendor;
            try {
                // Render minimal HTML for PDF using a dedicated view
                $month_display_local = $month_display; // local alias for view
                $journals_local = $journals;
                // Ensure we pass fresh user row (includes NIP) to the PDF view
                $userRow = $this->userModel->findById($current_user['id']);
                $current_user_local = $userRow ? $userRow : $current_user;

                ob_start();
                include __DIR__ . '/../views/teacher/rekap_pdf.php';
                $html = ob_get_clean();

                $options = new \Dompdf\Options();
                $options->set('isRemoteEnabled', false);
                $dompdf = new \Dompdf\Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                $filename = 'Rekap_Jurnal_' . $month_year . '.pdf';
                // Set Attachment to 1 (true) for download instead of view
                $dompdf->stream($filename, ['Attachment' => 1]);
                exit;
            } catch (\Exception $e) {
                $_SESSION['flash_error'] = 'Gagal membuat PDF: ' . $e->getMessage();
                $this->redirect('index.php?p=teacher/rekap-monthly');
                return;
            }
        }

        $_SESSION['flash_error'] = 'DomPDF tidak tersedia.';
        $this->redirect('index.php?p=teacher/rekap-monthly');
    }

    public function view()
    {
        $this->requireTeacher();
        $id = $_GET['id'] ?? 0;
        $current_user = $_SESSION['user'];

        $journal = $this->journalModel->findById($id, $current_user['id']);
        if (!$journal) {
            $_SESSION['flash_error'] = 'Jurnal tidak ditemukan.';
            $this->redirect('index.php?p=teacher/list');
        }

        $this->render('teacher/journal_detail.php', [
            'journal' => $journal,
            'current_user' => $current_user
        ]);
    }

    public function edit()
    {
        $this->requireTeacher();
        $id = $_GET['id'] ?? 0;
        $current_user = $_SESSION['user'];
        $isGuruBk = $this->isGuruBk();
        $subjects = $this->subjectModel->all();
        $bkSubjectId = $this->findBkSubjectId($subjects);
        if ($isGuruBk && $bkSubjectId <= 0) {
            $this->subjectModel->create('Bimbingan Konseling');
            $subjects = $this->subjectModel->all();
            $bkSubjectId = $this->findBkSubjectId($subjects);
            if ($bkSubjectId <= 0) {
                $_SESSION['flash_error'] = 'Mapel "Bimbingan Konseling" belum tersedia. Hubungi admin.';
                $this->redirect('index.php?p=teacher/list');
                return;
            }
        }
        if ($isGuruBk && !$this->journalModel->supportsBkFields()) {
            $_SESSION['flash_error'] = 'Database belum mendukung jurnal Guru BK. Jalankan migrasi database terlebih dahulu.';
            $this->redirect('index.php?p=teacher/list');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Preferred source from form
            $class_id = (int)($_POST['class_id'] ?? 0);

            // Backward-compatible fallback if older form posts jenjang/rombel
            if ($class_id <= 0) {
                $jenjang = $_POST['jenjang'] ?? '';
                $rombel = $_POST['rombel'] ?? '';
                $classes = $this->classModel->all();
                foreach ($classes as $c) {
                    $name = trim($c['name']);
                    $parts = preg_split('/[-\s]+/', $name, 2);
                    $c_jenjang = $parts[0] ?? '';
                    $c_rombel = $parts[1] ?? '';
                    if ($c_jenjang === $jenjang && $c_rombel === $rombel) {
                        $class_id = (int)$c['id'];
                        break;
                    }
                }
            }

            if ($class_id <= 0) {
                $_SESSION['flash_error'] = 'Kelas tidak valid.';
                $this->redirect('index.php?p=teacher/edit&id=' . (int)$id);
                return;
            }

            $subjectId = (int)($_POST['subject_id'] ?? 0);
            if ($isGuruBk && $bkSubjectId > 0) {
                $subjectId = $bkSubjectId;
            }

            $targetKegiatan = trim((string)($_POST['target_kegiatan'] ?? ''));
            $kegiatanLayanan = trim((string)($_POST['kegiatan_layanan'] ?? ''));
            $hasilDicapai = trim((string)($_POST['hasil_dicapai'] ?? ''));
            $notes = trim((string)($_POST['notes'] ?? ''));

            if ($isGuruBk) {
                if ($targetKegiatan === '' || $kegiatanLayanan === '' || $hasilDicapai === '') {
                    $_SESSION['flash_error'] = 'Form Guru BK belum lengkap.';
                    $this->redirect('index.php?p=teacher/edit&id=' . (int)$id);
                    return;
                }

                if (!in_array($kegiatanLayanan, self::BK_SERVICE_OPTIONS, true)) {
                    $_SESSION['flash_error'] = 'Kegiatan layanan tidak valid.';
                    $this->redirect('index.php?p=teacher/edit&id=' . (int)$id);
                    return;
                }
            }

            $ok = $this->journalModel->update($id, $current_user['id'], [
                'date' => $_POST['date'] ?? date('Y-m-d'),
                'class_id' => $class_id,
                'subject_id' => $subjectId,
                'jam_ke' => $_POST['jam_ke'] ?? '',
                'materi' => $isGuruBk ? $hasilDicapai : ($_POST['materi'] ?? ''),
                'notes' => $notes,
                'target_kegiatan' => $isGuruBk ? $targetKegiatan : '',
                'kegiatan_layanan' => $isGuruBk ? $kegiatanLayanan : '',
                'hasil_dicapai' => $isGuruBk ? $hasilDicapai : ''
            ]);

            if ($ok) {
                $_SESSION['flash_success'] = 'Jurnal diperbarui.';
            } else {
                $_SESSION['flash_error'] = 'Gagal memperbarui jurnal.';
            }
            $this->redirect('index.php?p=teacher/list');
            return;
        }

        // Handle GET request
        $journal = $this->journalModel->findById($id, $current_user['id']);
        if (!$journal) {
            $_SESSION['flash_error'] = 'Jurnal tidak ditemukan.';
            $this->redirect('index.php?p=teacher/list');
        }

        $classes = $this->classModel->all();
        if ($isGuruBk && empty($journal['target_kegiatan']) && !empty($journal['materi'])) {
            $journal['hasil_dicapai'] = $journal['materi'];
        }

        // build jenjang list and rombel mapping from class names
        $jenjangs = [];
        $rombelsByJenjang = [];
        foreach ($classes as $c) {
            $name = trim($c['name']);
            $parts = preg_split('/[-\s]+/', $name, 2);
            $jenjang = $parts[0] ?? '';
            $rombel = $parts[1] ?? '';
            if ($jenjang === '') continue;
            if (!in_array($jenjang, $jenjangs)) $jenjangs[] = $jenjang;
            $rombelsByJenjang[$jenjang][] = ['id' => $c['id'], 'rombel' => $rombel];
        }

        $this->render('teacher/edit_journal.php', [
            'journal' => $journal,
            'classes' => $classes,
            'subjects' => $subjects,
            'is_guru_bk' => $isGuruBk,
            'bk_subject_id' => $bkSubjectId,
            'bk_service_options' => self::BK_SERVICE_OPTIONS,
            'jenjangs' => $jenjangs,
            'rombelsByJenjang' => $rombelsByJenjang,
            'current_user' => $current_user
        ]);
    }

    public function delete()
    {
        $this->requireTeacher();
        $id = $_GET['id'] ?? 0;
        $current_user = $_SESSION['user'];

        $this->journalModel->delete($id, $current_user['id']);
        $_SESSION['flash_success'] = 'Jurnal dihapus.';
        $this->redirect('index.php?p=teacher/list');
    }

    public function sendTask()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];
        $isGuruBk = $this->isGuruBk();
        $subjects = $this->subjectModel->all();
        $bkSubjectId = $this->findBkSubjectId($subjects);
        if ($isGuruBk && $bkSubjectId <= 0) {
            $this->subjectModel->create('Bimbingan Konseling');
            $subjects = $this->subjectModel->all();
            $bkSubjectId = $this->findBkSubjectId($subjects);
            if ($bkSubjectId <= 0) {
                $_SESSION['flash_error'] = 'Mapel "Bimbingan Konseling" belum tersedia. Hubungi admin.';
                $this->redirect('index.php?p=teacher/dashboard');
                return;
            }
        }
        if ($isGuruBk && !$this->journalModel->supportsBkFields()) {
            $_SESSION['flash_error'] = 'Database belum mendukung jurnal Guru BK. Jalankan migrasi database terlebih dahulu.';
            $this->redirect('index.php?p=teacher/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'] ?? date('Y-m-d');
            $class_id = (int)($_POST['class_id'] ?? 0);
            $jam_ke = trim((string)($_POST['jam_ke'] ?? ''));
            $subjectId = (int)($_POST['subject_id'] ?? 0);
            if ($isGuruBk && $bkSubjectId > 0) {
                $subjectId = $bkSubjectId;
            }

            $materi = trim((string)($_POST['materi'] ?? ''));
            $notes = trim((string)($_POST['notes'] ?? ''));
            $targetKegiatan = trim((string)($_POST['target_kegiatan'] ?? ''));
            $kegiatanLayanan = trim((string)($_POST['kegiatan_layanan'] ?? ''));
            $hasilDicapai = trim((string)($_POST['hasil_dicapai'] ?? ''));

            if ($class_id <= 0 || $jam_ke === '' || !$date) {
                $_SESSION['flash_error'] = 'Form kirim tugas belum lengkap.';
                $this->redirect('index.php?p=teacher/send-task');
                return;
            }

            if ($subjectId <= 0) {
                $_SESSION['flash_error'] = 'Mata pelajaran wajib dipilih.';
                $this->redirect('index.php?p=teacher/send-task');
                return;
            }

            if ($isGuruBk) {
                if ($targetKegiatan === '' || $kegiatanLayanan === '' || $hasilDicapai === '') {
                    $_SESSION['flash_error'] = 'Form Guru BK belum lengkap.';
                    $this->redirect('index.php?p=teacher/send-task');
                    return;
                }

                if (!in_array($kegiatanLayanan, self::BK_SERVICE_OPTIONS, true)) {
                    $_SESSION['flash_error'] = 'Kegiatan layanan tidak valid.';
                    $this->redirect('index.php?p=teacher/send-task');
                    return;
                }
            } elseif ($materi === '') {
                $_SESSION['flash_error'] = 'Materi wajib diisi.';
                $this->redirect('index.php?p=teacher/send-task');
                return;
            }

            // Validate class_id
            $classes = $this->classModel->all();
            $className = '';
            foreach ($classes as $c) {
                if ((int)$c['id'] === $class_id) {
                    $className = trim((string)$c['name']);
                    break;
                }
            }

            if ($className === '') {
                $_SESSION['flash_error'] = 'Kelas tidak ditemukan.';
                $this->redirect('index.php?p=teacher/send-task');
                return;
            }
            $parts = preg_split('/[-\s]+/', $className, 2);
            $jenjang = $parts[0] ?? '';

            // Handle file upload
            $file_path = null;
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['pdf_file'];

                // Check MIME type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if ($mime_type !== 'application/pdf') {
                    $_SESSION['flash_error'] = 'File harus berupa PDF.';
                    $this->redirect('index.php?p=teacher/send-task');
                    return;
                }

                // Create upload directory if not exists
                $upload_dir = __DIR__ . '/../assets/uploads/tasks/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Generate unique filename with hash
                $hash = md5_file($file['tmp_name']) . '_' . time();
                $filename = $hash . '.pdf';
                $file_path = 'assets/uploads/tasks/' . $filename;

                if (!move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
                    $_SESSION['flash_error'] = 'Gagal mengunggah file.';
                    $this->redirect('index.php?p=teacher/send-task');
                    return;
                }
            } else {
                $_SESSION['flash_error'] = 'File PDF harus diunggah.';
                $this->redirect('index.php?p=teacher/send-task');
                return;
            }

            try {
                // Save journal first so it appears in recap immediately.
                $this->journalModel->create([
                    'user_id' => $current_user['id'],
                    'date' => $date,
                    'class_id' => $class_id,
                    'subject_id' => $subjectId,
                    'jam_ke' => $jam_ke,
                    'materi' => $isGuruBk ? $hasilDicapai : $materi,
                    'notes' => $notes,
                    'target_kegiatan' => $isGuruBk ? $targetKegiatan : '',
                    'kegiatan_layanan' => $isGuruBk ? $kegiatanLayanan : '',
                    'hasil_dicapai' => $isGuruBk ? $hasilDicapai : ''
                ]);

                $this->taskModel->create([
                    ':user_id' => $current_user['id'],
                    ':jenjang' => $jenjang,
                    ':class_id' => $class_id,
                    ':date' => $date,
                    ':jam_ke' => $jam_ke,
                    ':file_path' => $file_path,
                    ':status' => 'pending'
                ]);
            } catch (\Throwable $e) {
                if ($file_path && file_exists(__DIR__ . '/../' . $file_path)) {
                    @unlink(__DIR__ . '/../' . $file_path);
                }
                $_SESSION['flash_error'] = 'Gagal mengirim tugas: ' . $e->getMessage();
                $this->redirect('index.php?p=teacher/send-task');
                return;
            }

            $_SESSION['flash_success'] = 'Tugas berhasil dikirim untuk verifikasi admin.';
            $this->redirect('index.php?p=teacher/send-task');
        }

        // GET request: Check if teacher wants form or has tasks
        $force_form = isset($_GET['form']) && $_GET['form'] === '1';
        $tasks = $this->taskModel->findByTeacher($current_user['id']);

        if (!empty($tasks) && !$force_form) {
            // If have tasks and not forced to form, show list instead
            $pending_count = $this->taskModel->countByTeacherAndStatus($current_user['id'], 'pending');
            $verified_count = $this->taskModel->countByTeacherAndStatus($current_user['id'], 'verified');
            $rejected_count = $this->taskModel->countByTeacherAndStatus($current_user['id'], 'rejected');

            $this->render('teacher/list_tasks.php', [
                'current_user' => $current_user,
                'tasks' => $tasks,
                'pending_count' => $pending_count,
                'verified_count' => $verified_count,
                'rejected_count' => $rejected_count
            ]);
        } else {
            // If no tasks, show form
            $classes = $this->classModel->all();
            $jenjangs = [];
            $rombelsByJenjang = [];
            foreach ($classes as $c) {
                $name = trim($c['name']);
                $parts = preg_split('/[-\s]+/', $name, 2);
                $jenjang = $parts[0] ?? '';
                $rombel = $parts[1] ?? '';
                if ($jenjang === '') continue;
                if (!in_array($jenjang, $jenjangs)) $jenjangs[] = $jenjang;
                $rombelsByJenjang[$jenjang][] = ['id' => $c['id'], 'rombel' => $rombel];
            }

            $this->render('teacher/send_task.php', [
                'current_user' => $current_user,
                'subjects' => $subjects,
                'is_guru_bk' => $isGuruBk,
                'bk_subject_id' => $bkSubjectId,
                'bk_service_options' => self::BK_SERVICE_OPTIONS,
                'jenjangs' => $jenjangs,
                'rombelsByJenjang' => $rombelsByJenjang
            ]);
        }
    }

    public function listTasks()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];

        $tasks = $this->taskModel->findByTeacher($current_user['id']);

        // Count by status
        $pending_count = $this->taskModel->countByTeacherAndStatus($current_user['id'], 'pending');
        $verified_count = $this->taskModel->countByTeacherAndStatus($current_user['id'], 'verified');
        $rejected_count = $this->taskModel->countByTeacherAndStatus($current_user['id'], 'rejected');

        $this->render('teacher/list_tasks.php', [
            'current_user' => $current_user,
            'tasks' => $tasks,
            'pending_count' => $pending_count,
            'verified_count' => $verified_count,
            'rejected_count' => $rejected_count
        ]);
    }

    public function viewTask()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];
        $id = $_GET['id'] ?? 0;

        $task = $this->taskModel->findById($id);
        if (!$task || $task['user_id'] != $current_user['id']) {
            $_SESSION['flash_error'] = 'Tugas tidak ditemukan.';
            $this->redirect('index.php?p=teacher/list-tasks');
            return;
        }

        $this->render('teacher/view_task.php', [
            'current_user' => $current_user,
            'task' => $task
        ]);
    }

    public function deleteTask()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];
        $id = $_GET['id'] ?? 0;

        $this->taskModel->delete($id, $current_user['id']);
        $_SESSION['flash_success'] = 'Tugas dihapus.';
        $this->redirect('index.php?p=teacher/list-tasks');
    }

    public function changePassword()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($new !== $confirm) {
                $_SESSION['flash_error'] = 'Password baru dan konfirmasi tidak cocok.';
                $this->redirect('index.php?p=teacher/change-password');
                return;
            }

            if (strlen($new) < 6) {
                $_SESSION['flash_error'] = 'Password minimal 6 karakter.';
                $this->redirect('index.php?p=teacher/change-password');
                return;
            }

            $user = $this->userModel->findById($current_user['id']);
            if (!$user || !password_verify($current, $user['password'])) {
                $_SESSION['flash_error'] = 'Password saat ini salah.';
                $this->redirect('index.php?p=teacher/change-password');
                return;
            }

            $hash = password_hash($new, PASSWORD_DEFAULT);
            $this->userModel->resetPassword($current_user['id'], $hash);
            $_SESSION['flash_success'] = 'Password berhasil diperbarui.';
            $this->redirect('index.php?p=teacher/dashboard');
        }

        $this->render('teacher/change_password.php', [
            'current_user' => $current_user
        ]);
    }

    public function profile()
    {
        $this->requireTeacher();
        $current_user = $_SESSION['user'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // If profile update (name/username/nip)
            if (isset($_POST['update_profile'])) {
                $name = trim($_POST['name'] ?? $current_user['name']);
                $nip = trim($_POST['nip'] ?? '');

                // Preserve username for teacher: they are not allowed to change it here
                $userRow = $this->userModel->findById($current_user['id']);
                $username = $userRow['username'] ?? $current_user['username'];

                $this->userModel->update($current_user['id'], [
                    'name' => $name,
                    'nip' => $nip,
                    'username' => $username,
                    'role' => $current_user['role']
                ]);
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['nip'] = $nip;
                $_SESSION['flash_success'] = 'Profil diperbarui.';
                $this->redirect('index.php?p=teacher/profile');
                return;
            }

            // If password change from profile
            if (isset($_POST['change_password'])) {
                $current = $_POST['current_password'] ?? '';
                $new = $_POST['new_password'] ?? '';
                $confirm = $_POST['confirm_password'] ?? '';

                if ($new !== $confirm) {
                    $_SESSION['flash_error'] = 'Password baru dan konfirmasi tidak cocok.';
                    $this->redirect('index.php?p=teacher/profile');
                    return;
                }

                if (strlen($new) < 6) {
                    $_SESSION['flash_error'] = 'Password minimal 6 karakter.';
                    $this->redirect('index.php?p=teacher/profile');
                    return;
                }

                $user = $this->userModel->findById($current_user['id']);
                if (!$user || !password_verify($current, $user['password'])) {
                    $_SESSION['flash_error'] = 'Password saat ini salah.';
                    $this->redirect('index.php?p=teacher/profile');
                    return;
                }

                $hash = password_hash($new, PASSWORD_DEFAULT);
                $this->userModel->resetPassword($current_user['id'], $hash);
                $_SESSION['flash_success'] = 'Password berhasil diperbarui.';
                $this->redirect('index.php?p=teacher/profile');
                return;
            }
        }

        $user = $this->userModel->findById($current_user['id']);
        $this->render('teacher/profile.php', [
            'current_user' => $current_user,
            'user' => $user
        ]);
    }
}
