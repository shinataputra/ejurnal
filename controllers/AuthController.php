<?php
// controllers/AuthController.php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $u = $this->userModel->findByUsername($username);
            if ($u && password_verify($password, $u['password'])) {
                $_SESSION['user'] = [
                    'id' => $u['id'],
                    'name' => $u['name'],
                    'role' => $u['role'],
                    'username' => $u['username']
                ];
                if ($u['role'] === 'admin') {
                    $this->redirect('index.php?p=admin/dashboard');
                } else {
                    $this->redirect('index.php?p=teacher/dashboard');
                }
            } else {
                $_SESSION['flash_error'] = 'Login gagal';
                $this->redirect('index.php?p=login');
            }
        }
        $this->render('auth/login.php');
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('index.php?p=login');
    }
}
