<?php
// Load Composer Autoload FIRST (before using PHPMailer classes)
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer extends PHPMailer
{
    public function __construct($exceptions = null)
    {
        parent::__construct($exceptions);

        // Gmail SMTP SETTINGS
        $this->isSMTP();
        $this->Host = 'smtp.gmail.com';
        $this->SMTPAuth = true;
        $this->Username = 'denniscomia445@gmail.com';
        $this->Password = 'pbzlupqfkrkaeqog'; // Gmail App Password
        $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->Port = 587;

        // Default FROM
        $this->setFrom('denniscomia445@gmail.com', 'eClinic Team');
        $this->isHTML(true);
    }
}
