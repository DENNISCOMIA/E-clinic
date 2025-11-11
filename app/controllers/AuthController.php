<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    protected $UserModel;

    public function __construct()
    {
        parent::__construct();
        $this->UserModel = new UserModel();
    }

    public function login()
    {
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = $this->db->table('users')->where('email', $email)->get();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    redirect('admin/dashboard');
                } else {
                    redirect('user/home');
                }
            } else {
                $message = "Invalid credentials";
            }
        }

        $this->call->view('auth/login', ['message' => $message]);
    }

    public function register()
    {
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = password_hash(trim($_POST['password'] ?? ''), PASSWORD_DEFAULT);

            $this->db->table('users')->insert([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => 'user'
            ]);

            $message = "Registration successful! Please login.";
        }

        $this->call->view('auth/register', ['message' => $message]);
    }

    public function logout()
    {
        session_destroy();
        redirect('auth/login');
    }
}
?>
