<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// Load Composer Autoload FIRST
require_once __DIR__ . '/../../vendor/autload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function send_message()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $recipient = trim($_POST['recipient']);
            $sender    = trim($_POST['sender']);
            $subject   = trim($_POST['subject']);
            $message   = trim($_POST['message']);

            $mail = new PHPMailer(true);

            try {
                // SMTP SETTINGS
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'denniscomia445@gmail.com';
                $mail->Password   = 'pbzlupqfkrkaeqog'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // HEADERS
                $mail->setFrom('denniscomia445@gmail.com', 'eClinic Mailer');
                $mail->addAddress($recipient);
                $mail->addReplyTo($sender);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = nl2br($message);

                $mail->send();

                $_SESSION['flash_success'] = "✅ Message successfully sent to $recipient";

            } catch (Exception $e) {
                $_SESSION['flash_error'] = "❌ Failed to send message: " . $mail->ErrorInfo;
            }

            redirect('user/home');
        }
    }
}
