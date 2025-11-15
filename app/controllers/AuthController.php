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

    /* -------------------- LOGIN -------------------- */
    public function login()
    {
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $user = $this->db->table('users')->where('email', $email)->get();

            if ($user && password_verify($password, $user['password'])) {

                // Prevent login if not verified
                if ($user['role'] !== 'admin' && $user['is_verified'] == 0) {
                    $message = "⚠️ Please verify your account first using your OTP.";
                } else {

                    // Create session
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['role'] = $user['role'];

                    redirect($user['role'] === 'admin' ? 'admin/dashboard' : 'user/home');
                }

            } else {
                $message = "❌ Invalid email or password.";
            }
        }

        $this->call->view('auth/login', ['message' => $message]);
    }

    /* -------------------- REGISTER (SEND OTP) -------------------- */
    public function register()
    {
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Check if email already exists
            $exists = $this->db->table('users')->where('email', $email)->get();
            if ($exists) {
                return $this->call->view('auth/register', [
                    'message' => "⚠️ Email already exists!"
                ]);
            }

            // Generate OTP
            $otp = rand(100000, 999999);

            // Insert user
            $this->db->table('users')->insert([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => 'user',
                'otp_code' => $otp,
                'is_verified' => 0
            ]);

            // Send OTP to email
            require_once APP_DIR . 'libraries/mailer.php';
            $mail = new Mailer(true);

            try {
                $mail->addAddress($email, $username);
                $mail->Subject = 'Your eClinic OTP Code';
                $mail->isHTML(true);
                $mail->Body = "
                    <h2>Hello, $username!</h2>
                    <p>Your OTP verification code is:</p>
                    <h1 style='font-size:32px; letter-spacing:5px;'>$otp</h1>
                    <p>Enter this code to verify your account.</p>
                ";
                $mail->send();

                // Redirect to OTP page
                $_SESSION['pending_email'] = $email;

                redirect('auth/otp');

            } catch (Exception $e) {
                $message = "❌ Email failed: " . $mail->ErrorInfo;
            }
        }

        $this->call->view('auth/register', ['message' => $message]);
    }

    /* -------------------- SHOW OTP PAGE -------------------- */
    public function otp()
    {
        if (!isset($_SESSION['pending_email'])) {
            redirect('auth/register');
        }

        $this->call->view('auth/otp', [
            'email' => $_SESSION['pending_email']
        ]);
    }

    /* -------------------- VERIFY OTP -------------------- */
public function verify_otp()
{
    $email = $_POST['email'] ?? null;
    $otp = $_POST['otp'] ?? null;

    if (!$email || !$otp) {
        $this->call->view('auth/login', ['message' => "❌ Invalid request"]);
        return;
    }

    $user = $this->db->table('users')->where('email', $email)->get();

    if (!$user) {
        $this->call->view('auth/login', ['message' => "❌ Email not found"]);
        return;
    }

    if ($user['otp_code'] !== $otp) {
        $this->call->view('auth/otp', [
            'email' => $email,
            'message' => "❌ Incorrect OTP"
        ]);
        return;
    }

    // Mark as verified
    $this->db->table('users')->where('email', $email)->update([
        'is_verified' => 1,
        'otp_code' => null
    ]);

    $this->call->view('auth/login', [
        'message' => "✅ Email verified! You may now log in."
    ]);
}


    /* -------------------- LOGOUT -------------------- */
    public function logout()
    {
        session_destroy();
        redirect('auth/login');
    }
}
?>
