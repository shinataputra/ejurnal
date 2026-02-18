<?php
// controllers/AdminController.php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/AcademicYear.php';
require_once __DIR__ . '/../models/Settings.php';
require_once __DIR__ . '/../models/Task.php';

class AdminController extends Controller
{
    protected $userModel;
    protected $classModel;
    protected $subjectModel;
    protected $ayModel;
    protected $settingsModel;
    protected $taskModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->classModel = new ClassModel();
        $this->subjectModel = new Subject();
        $this->ayModel = new AcademicYear();
        $this->settingsModel = new Settings();
        $this->taskModel = new Task();
    }

    protected function requireAdmin()
    {
        if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
            $_SESSION['flash_error'] = 'Akses ditolak. Silakan masuk sebagai admin.';
            $this->redirect('index.php?p=login');
        }
    }

    public function dashboard()
    {
        $this->requireAdmin();
        // basic stats
        $this->render('admin/dashboard.php', ['current_user' => $_SESSION['user']]);
    }

    public function users()
    {
        $this->requireAdmin();
        $teachers = $this->userModel->allTeachers();
        $this->render('admin/users_list.php', ['teachers' => $teachers, 'current_user' => $_SESSION['user']]);
    }

    public function usersAdd()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $nip = $_POST['nip'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'teacher';
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->create([
                ':name' => $name,
                ':nip' => $nip,
                ':username' => $username,
                ':password' => $hash,
                ':role' => $role
            ]);
            $_SESSION['flash_success'] = 'Guru ditambahkan.';
            $this->redirect('index.php?p=admin/users');
        }
        $this->render('admin/users_add.php', ['current_user' => $_SESSION['user']]);
    }

    public function usersReset()
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $default = password_hash('guru123', PASSWORD_DEFAULT);
            $this->userModel->resetPassword($id, $default);
            $_SESSION['flash_success'] = 'Password di-reset ke default.';
        }
        $this->redirect('index.php?p=admin/users');
    }

    public function usersEdit()
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            $_SESSION['flash_error'] = 'User tidak ditemukan.';
            $this->redirect('index.php?p=admin/users');
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            $_SESSION['flash_error'] = 'User tidak ditemukan.';
            $this->redirect('index.php?p=admin/users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $nip = $_POST['nip'] ?? '';
            $username = $_POST['username'] ?? '';
            $role = $_POST['role'] ?? 'teacher';

            $this->userModel->update($id, [
                'name' => $name,
                'nip' => $nip,
                'username' => $username,
                'role' => $role
            ]);
            $_SESSION['flash_success'] = 'Data guru diperbarui.';
            $this->redirect('index.php?p=admin/users');
        }

        $this->render('admin/users_edit.php', [
            'user' => $user,
            'current_user' => $_SESSION['user']
        ]);
    }

    public function usersDelete()
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $user = $this->userModel->findById($id);
            if ($user && $user['role'] === 'teacher') {
                $this->userModel->delete($id);
                $_SESSION['flash_success'] = 'Guru dihapus.';
            } else {
                $_SESSION['flash_error'] = 'Tidak dapat menghapus user.';
            }
        }
        $this->redirect('index.php?p=admin/users');
    }

    public function classes()
    {
        $this->requireAdmin();
        $classes = $this->classModel->all();
        $this->render('admin/classes_list.php', ['classes' => $classes, 'current_user' => $_SESSION['user']]);
    }

    public function classesAdd()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jenjang = trim($_POST['jenjang'] ?? '');
            $rombel = trim($_POST['rombel'] ?? '');
            $name = trim($jenjang . ' ' . $rombel);
            if ($name === '') {
                $_SESSION['flash_error'] = 'Nama kelas wajib diisi.';
                $this->redirect('index.php?p=admin/classesAdd');
            }
            $this->classModel->create($name);
            $_SESSION['flash_success'] = 'Kelas ditambahkan.';
            $this->redirect('index.php?p=admin/classes');
        }
        $this->render('admin/classes_add.php', ['current_user' => $_SESSION['user']]);
    }

    public function subjects()
    {
        $this->requireAdmin();
        $subjects = $this->subjectModel->all();
        $this->render('admin/subjects_list.php', ['subjects' => $subjects, 'current_user' => $_SESSION['user']]);
    }

    public function subjectsAdd()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $this->subjectModel->create($name);
            $_SESSION['flash_success'] = 'Mata pelajaran ditambahkan.';
            $this->redirect('index.php?p=admin/subjects');
        }
        $this->render('admin/subjects_add.php', ['current_user' => $_SESSION['user']]);
    }

    public function classesEdit()
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            $_SESSION['flash_error'] = 'Kelas tidak ditemukan.';
            $this->redirect('index.php?p=admin/classes');
        }

        $class = $this->classModel->findById($id);
        if (!$class) {
            $_SESSION['flash_error'] = 'Kelas tidak ditemukan.';
            $this->redirect('index.php?p=admin/classes');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jenjang = trim($_POST['jenjang'] ?? '');
            $rombel = trim($_POST['rombel'] ?? '');
            $name = trim($jenjang . ' ' . $rombel);
            if ($name === '') {
                $_SESSION['flash_error'] = 'Nama kelas wajib diisi.';
                $this->redirect('index.php?p=admin/classesEdit&id=' . $id);
            }
            $this->classModel->update($id, $name);
            $_SESSION['flash_success'] = 'Kelas diperbarui.';
            $this->redirect('index.php?p=admin/classes');
        }

        $this->render('admin/classes_edit.php', [
            'class' => $class,
            'current_user' => $_SESSION['user']
        ]);
    }

    public function classesDelete()
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->classModel->delete($id);
            $_SESSION['flash_success'] = 'Kelas dihapus.';
        }
        $this->redirect('index.php?p=admin/classes');
    }

    public function subjectsEdit()
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            $_SESSION['flash_error'] = 'Mata pelajaran tidak ditemukan.';
            $this->redirect('index.php?p=admin/subjects');
        }

        $subject = $this->subjectModel->findById($id);
        if (!$subject) {
            $_SESSION['flash_error'] = 'Mata pelajaran tidak ditemukan.';
            $this->redirect('index.php?p=admin/subjects');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $this->subjectModel->update($id, $name);
            $_SESSION['flash_success'] = 'Mata pelajaran diperbarui.';
            $this->redirect('index.php?p=admin/subjects');
        }

        $this->render('admin/subjects_edit.php', [
            'subject' => $subject,
            'current_user' => $_SESSION['user']
        ]);
    }

    public function subjectsDelete()
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->subjectModel->delete($id);
            $_SESSION['flash_success'] = 'Mata pelajaran dihapus.';
        }
        $this->redirect('index.php?p=admin/subjects');
    }

    public function academicYears()
    {
        $this->requireAdmin();
        $ays = $this->ayModel->all();
        $this->render('admin/academic_years_list.php', ['years' => $ays, 'current_user' => $_SESSION['user']]);
    }

    public function academicYearsAdd()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':name' => $_POST['name'] ?? '',
                ':start_date' => $_POST['start_date'] ?? '',
                ':end_date' => $_POST['end_date'] ?? '',
                ':is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            $this->ayModel->create($data);
            if ($data[':is_active']) {
                $lastId = db()->lastInsertId();
                $this->ayModel->setActive($lastId);
            }
            $_SESSION['flash_success'] = 'Tahun pelajaran ditambahkan.';
            $this->redirect('index.php?p=admin/academic_years');
        }
        $this->render('admin/academic_years_add.php', ['current_user' => $_SESSION['user']]);
    }

    public function academicYearsSetActive()
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->ayModel->setActive($id);
            $_SESSION['flash_success'] = 'Tahun pelajaran di-set aktif.';
        }
        $this->redirect('index.php?p=admin/academic_years');
    }

    public function settings()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $schoolName = trim($_POST['school_name'] ?? '');

            // Validate school name
            if (empty($schoolName)) {
                $_SESSION['flash_error'] = 'Nama sekolah tidak boleh kosong.';
                $this->redirect('index.php?p=admin/settings');
            }

            // Save school name
            $this->settingsModel->set('school_name', $schoolName);

            // Handle logo upload
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../assets/uploads/';

                // Create uploads directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $file = $_FILES['logo'];
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, $allowedMimes)) {
                    $_SESSION['flash_error'] = 'Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP.';
                    $this->redirect('index.php?p=admin/settings');
                }

                $fileExtension = match ($mimeType) {
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp',
                    default => 'jpg'
                };

                $fileName = 'logo_' . hash('md5', microtime()) . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                $relativePath = 'assets/uploads/' . $fileName;

                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    // Delete old logo if exists
                    $oldLogoPath = $this->settingsModel->get('logo_path');
                    if ($oldLogoPath && file_exists(__DIR__ . '/../' . $oldLogoPath)) {
                        unlink(__DIR__ . '/../' . $oldLogoPath);
                    }

                    // Save new logo path
                    $this->settingsModel->set('logo_path', $relativePath);
                } else {
                    $_SESSION['flash_error'] = 'Gagal mengunggah file logo.';
                    $this->redirect('index.php?p=admin/settings');
                }
            }

            $_SESSION['flash_success'] = 'Pengaturan sekolah berhasil disimpan.';
            $this->redirect('index.php?p=admin/settings');
        }

        // Get current settings
        $schoolName = $this->settingsModel->get('school_name') ?? 'SMKN 1 Probolinggo';
        $logoPath = $this->settingsModel->get('logo_path');

        $this->render('admin/settings.php', [
            'current_user' => $_SESSION['user'],
            'schoolName' => $schoolName,
            'logoPath' => $logoPath
        ]);
    }

    public function tasks()
    {
        $this->requireAdmin();

        // Get filters
        $status = $_GET['status'] ?? null;
        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;

        // Get tasks with filters
        $tasks = $this->taskModel->listByStatus($status, $start_date, $end_date);

        // Count by status
        $pending_count = $this->taskModel->countByStatus('pending');
        $verified_count = $this->taskModel->countByStatus('verified');
        $rejected_count = $this->taskModel->countByStatus('rejected');

        $this->render('admin/tasks_list.php', [
            'current_user' => $_SESSION['user'],
            'tasks' => $tasks,
            'pending_count' => $pending_count,
            'verified_count' => $verified_count,
            'rejected_count' => $rejected_count,
            'filter_status' => $status,
            'filter_start_date' => $start_date,
            'filter_end_date' => $end_date
        ]);
    }

    public function taskView()
    {
        $this->requireAdmin();
        $id = $_GET['id'] ?? 0;

        $task = $this->taskModel->findById($id);
        if (!$task) {
            $_SESSION['flash_error'] = 'Tugas tidak ditemukan.';
            $this->redirect('index.php?p=admin/tasks');
            return;
        }

        $this->render('admin/task_view.php', [
            'current_user' => $_SESSION['user'],
            'task' => $task
        ]);
    }

    public function taskVerify()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
        } else {
            $id = $_GET['id'] ?? 0;
        }

        $task = $this->taskModel->findById($id);
        if (!$task) {
            $_SESSION['flash_error'] = 'Tugas tidak ditemukan.';
            $this->redirect('index.php?p=admin/tasks');
            return;
        }

        $this->taskModel->verify($id, $_SESSION['user']['id']);
        $_SESSION['flash_success'] = 'Tugas telah diverifikasi.';
        $this->redirect('index.php?p=admin/tasks');
    }

    public function taskReject()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $admin_notes = $_POST['admin_notes'] ?? '';

            $task = $this->taskModel->findById($id);
            if (!$task) {
                $_SESSION['flash_error'] = 'Tugas tidak ditemukan.';
                $this->redirect('index.php?p=admin/tasks');
                return;
            }

            $this->taskModel->reject($id, $admin_notes, $_SESSION['user']['id']);
            $_SESSION['flash_success'] = 'Tugas telah ditolak.';
            $this->redirect('index.php?p=admin/tasks');
        } else {
            $_SESSION['flash_error'] = 'Metode request tidak valid.';
            $this->redirect('index.php?p=admin/tasks');
        }
    }

    public function profile()
    {
        $this->requireAdmin();
        $current_user = $_SESSION['user'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_profile'])) {
                $name = trim($_POST['name'] ?? $current_user['name']);
                $nip = trim($_POST['nip'] ?? '');
                $username = trim($_POST['username'] ?? $current_user['username']);

                $this->userModel->update($current_user['id'], [
                    'name' => $name,
                    'nip' => $nip,
                    'username' => $username,
                    'role' => 'admin'
                ]);
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['username'] = $username;
                $_SESSION['flash_success'] = 'Profil diperbarui.';
                $this->redirect('index.php?p=admin/profile');
                return;
            }

            if (isset($_POST['change_password'])) {
                $current = $_POST['current_password'] ?? '';
                $new = $_POST['new_password'] ?? '';
                $confirm = $_POST['confirm_password'] ?? '';

                if ($new !== $confirm) {
                    $_SESSION['flash_error'] = 'Password baru dan konfirmasi tidak cocok.';
                    $this->redirect('index.php?p=admin/profile');
                    return;
                }

                if (strlen($new) < 6) {
                    $_SESSION['flash_error'] = 'Password minimal 6 karakter.';
                    $this->redirect('index.php?p=admin/profile');
                    return;
                }

                $user = $this->userModel->findById($current_user['id']);
                if (!$user || !password_verify($current, $user['password'])) {
                    $_SESSION['flash_error'] = 'Password saat ini salah.';
                    $this->redirect('index.php?p=admin/profile');
                    return;
                }

                $hash = password_hash($new, PASSWORD_DEFAULT);
                $this->userModel->resetPassword($current_user['id'], $hash);
                $_SESSION['flash_success'] = 'Password berhasil diperbarui.';
                $this->redirect('index.php?p=admin/profile');
                return;
            }
        }

        $user = $this->userModel->findById($current_user['id']);
        $this->render('admin/profile.php', [
            'current_user' => $current_user,
            'user' => $user
        ]);
    }
}
