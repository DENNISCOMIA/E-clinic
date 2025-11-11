<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load PHPMailer
        require_once APP_DIR . 'libraries/phpmailer/PHPMailer.php';
        require_once APP_DIR . 'libraries/phpmailer/SMTP.php';
        require_once APP_DIR . 'libraries/phpmailer/Exception.php';
    }

    public function send_message()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipient = trim($_POST['recipient']);
            $sender = trim($_POST['sender']);
            $subject = trim($_POST['subject']);
            $message = trim($_POST['message']);

            $mail = new PHPMailer(true);

            try {
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;

                // 🔑 Replace with your Gmail and app password
                $mail->Username = 'denniscomia445@gmail.com';
                $mail->Password = 'pbzlupqfkrkaeqog'; 

                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Email headers
                $mail->setFrom($sender, 'eClinic Mailer');
                $mail->addAddress($recipient);
                $mail->isHTML(true);

                $mail->Subject = $subject;
                $mail->Body    = nl2br($message);

                $mail->send();

                $_SESSION['flash_success'] = "✅ Message successfully sent to $recipient";
            } catch (Exception $e) {
                $_SESSION['flash_error'] = "❌ Message could not be sent. Error: {$mail->ErrorInfo}";
            }

            redirect('user/home');
        }
    }
}
