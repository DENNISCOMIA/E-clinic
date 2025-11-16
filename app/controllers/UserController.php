<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

require_once __DIR__ . '/../libraries/Mailer.php';

class UserController extends Controller
{
    protected $UserModel, $AppointmentModel, $RecordModel;

    public function __construct()
    {
        parent::__construct();

        // ✅ Initialize LavaLust Database
        $this->call->library('database');
        $this->db = new Database();

        // ✅ Load models
        $this->UserModel = new UserModel();
        $this->AppointmentModel = new AppointmentModel();
        $this->RecordModel = new RecordModel();

        // ✅ Allow only logged-in users
        if (empty($_SESSION['logged_in']) || $_SESSION['role'] !== 'user') {
            redirect('auth/login');
            exit;
        }
    }

    // ============================
    // 🏠 USER HOME PAGE
    // ============================
    public function home()
    {
        $this->call->view('user/home');
    }

    // ============================
    // 👤 USER PROFILE
    // ============================
    public function profile()
    {
        $user_id = $_SESSION['user_id'];
        $profile = $this->UserModel->getProfile($user_id);
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id'       => $user_id,
                'full_name'     => trim($_POST['fullName'] ?? ''),
                'age'           => intval($_POST['age'] ?? 0),
                'gender'        => $_POST['gender'] ?? '',
                'date_of_birth' => $_POST['dob'] ?? '',
                'address'       => trim($_POST['address'] ?? ''),
                'phone'         => trim($_POST['phone'] ?? '')
            ];

            if ($profile) {
                $this->UserModel->updateProfile($user_id, $data);
                $message = "Profile updated successfully.";
            } else {
                $this->UserModel->saveProfile($data);
                $message = "Profile saved successfully.";
            }

            $profile = $this->UserModel->getProfile($user_id);
        }

        $this->call->view('user/profile', [
            'profile' => $profile,
            'message' => $message
        ]);
    }

    // ============================
    // 📅 USER APPOINTMENT (CREATE + VIEW)
    // ============================
    public function appointment()
    {
        $user_id = $_SESSION['user_id'];
        $appointments = $this->AppointmentModel->getAppointmentsByUser($user_id);
        $profile = $this->UserModel->getProfile($user_id);
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname         = trim($_POST['fullname']);
            $age              = intval($_POST['age']);
            $contact          = trim($_POST['contact']);
            $appointment_date = $_POST['appointment_date'];
            $purpose          = trim($_POST['purpose']);
            $status           = 'Pending';

             $pending = $this->db->table('appointments')
                    ->where('appointment_date', $appointment_date)
                    ->where('status', 'Pending')
                    ->get_all();

    $pendingCount = $pending ? count($pending) : 0;

    if ($pendingCount >= 5) {
    $message = "❌ The selected date already has 5 pending appointments. Please pick another date.";

    $this->call->view('user/appointment', [
        'appointments' => $appointments,
        'profile'      => $profile,
        'message'      => $message
    ]);
    return;
}

            $appointmentData = [
                'user_id'          => $user_id,
                'fullname'         => $fullname,
                'age'              => $age,
                'contact'          => $contact,
                'appointment_date' => $appointment_date,
                'status'           => $status,
                'purpose'          => $purpose
            ];

            $appointment_id = $this->AppointmentModel->createOrUpdateAppointment($appointmentData);

            if ($appointment_id) {
                $historyExists = $this->db->table('user_history')
                                          ->where('user_id', $user_id)
                                          ->where('appointment_date', $appointment_date)
                                          ->get();

                $historyData = [
                    'user_id'          => $user_id,
                    'appointment_id'   => $appointment_id,
                    'appointment_date' => $appointment_date,
                    'purpose'          => $purpose,
                    'status'           => $status,
                    'Findings'         => '',
                    'Prescription'     => '',
                    'fullname'         => $fullname
                ];

                if ($historyExists) {
                    $this->db->table('user_history')
                             ->where('user_id', $user_id)
                             ->where('appointment_date', $appointment_date)
                             ->update($historyData);
                    $message = "Appointment updated successfully for the same date!";
                } else {
                    $this->db->table('user_history')->insert($historyData);
                    $message = "Appointment requested successfully!";
                }

                // ✅ Patient Records
                $patientExists = $this->db->table('patient_records')
                                          ->where('user_id', $user_id)
                                          ->where('appointment_date', $appointment_date)
                                          ->get();

                $patientData = [
                    'user_id'          => $user_id,
                    'fullname'         => $fullname,
                    'age'              => $age,
                    'contact'          => $contact,
                    'appointment_date' => $appointment_date,
                    'purpose'          => $purpose,
                    'status'           => $status,
                    'findings'         => '',
                    'prescription'     => ''
                ];

                if ($patientExists) {
                    $this->db->table('patient_records')
                             ->where('user_id', $user_id)
                             ->where('appointment_date', $appointment_date)
                             ->update($patientData);
                } else {
                    $this->db->table('patient_records')->insert($patientData);
                }
            } else {
                $message = "Error saving appointment.";
            }

            $appointments = $this->AppointmentModel->getAppointmentsByUser($user_id);
        }

        $this->call->view('user/appointment', [
            'appointments' => $appointments,
            'profile'      => $profile,
            'message'      => $message
        ]);
    }

    // ============================
    // 📜 USER HISTORY
    // ============================
    public function history()
    {
        $user_id = $_SESSION['user_id'];

        $history = $this->db->table('user_history')
                            ->where('user_id', $user_id)
                            ->order_by('appointment_date', 'DESC')
                            ->get_all();

        $this->call->view('user/history', ['history' => $history]);
    }

    // ============================
    // 🗑️ BULK DELETE HISTORY
    // ============================
public function delete_history()
{
    // Only handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['delete_ids'])) {
        $ids = $_POST['delete_ids'];

        // Convert all IDs to integers for safety
        $ids = array_map('intval', $ids);

        // ✅ Delete using LavaLust's query builder
        foreach ($ids as $id) {
            $this->db->table('user_history')->where('id', '=', $id)->delete();
        }

        // Redirect back with success message
        redirect('user/history');
    } else {
        // No IDs selected or invalid request
        redirect('user/history');
    }
}
    // ============================
    // 📩 CONTACT FORM (Mailer)
    // ============================
    public function send_message()
    {
        $name = trim($this->io->post('name'));
        $email = trim($this->io->post('email'));
        $message = trim($this->io->post('message'));

        if (empty($name) || empty($email) || empty($message)) {
            $_SESSION['flash_error'] = 'All fields are required.';
            redirect('user/home');
            return;
        }

        $adminEmail = 'denniscomia445@gmail.com'; // Admin Gmail

        // 1️⃣ Send the message to Admin
        $mail = new Mailer();
        $mail->addAddress($adminEmail, 'eClinic Admin');
        $mail->addReplyTo($email, $name);
        $mail->Subject = "📩 New Contact Message from $name";
        $mail->Body = "You have received a new message from the eClinic Contact Form:\n\n"
                    . "--------------------------------------\n"
                    . "Name: $name\n"
                    . "Email: $email\n"
                    . "--------------------------------------\n\n"
                    . "Message:\n$message\n\n"
                    . "--------------------------------------\n"
                    . "Please reply to this email to respond to the sender.";

        $adminSent = $mail->send();

        // 2️⃣ Send Auto-Reply to User
        if ($adminSent) {
            $reply = new Mailer();
            $reply->addAddress($email, $name);
            $reply->Subject = "✅ Thank you for contacting eClinic!";
            $reply->Body = "Hi $name,\n\n"
                        . "We’ve received your message:\n\n"
                        . "\"$message\"\n\n"
                        . "Our team will review your inquiry and respond soon.\n\n"
                        . "Best regards,\nThe eClinic Team\n";

            $reply->send();

            $_SESSION['flash_success'] = '✅ Message sent successfully! We’ll reply soon.';
        } else {
            $_SESSION['flash_error'] = '❌ Failed to send message. Please try again later.';
        }

        redirect('user/home');
    }
}
?>
