<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Use __DIR__ instead of APPPATH (this fixes your error)
require_once __DIR__ . '/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer-master/src/SMTP.php';

class Mailer extends PHPMailer
{
    public function __construct($exceptions = null)
    {
        parent::__construct($exceptions);

        // ✅ Gmail SMTP Configuration
        $this->isSMTP();
        $this->Host = 'smtp.gmail.com';
        $this->SMTPAuth = true;
        $this->Username = 'denniscomia445@gmail.com'; // your Gmail
        $this->Password = 'pbzlupqfkrkaeqog';   // your Gmail App Password
        $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->Port = 587;

        // Default sender info
        $this->setFrom('denniscomia445@gmail.com', 'eClinic Team');
        $this->isHTML(false); // Set to true if you want HTML email
    }
}
