<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AppointmentModel extends Model
{
    protected $table = 'appointments';

    // ================================
    // 🩺 CREATE OR UPDATE APPOINTMENT
    // ================================
    public function createOrUpdateAppointment(array $data)
    {
        // ✅ Check if appointment already exists for this user on this date
        $existing = $this->db->table($this->table)
                             ->where('user_id', $data['user_id'])
                             ->where('appointment_date', $data['appointment_date'])
                             ->get();

        if ($existing) {
            // ✅ Update existing appointment instead of creating new
            $this->db->table($this->table)
                     ->where('id', $existing['id'])
                     ->update($data);

            return $existing['id']; // return the existing appointment ID
        } else {
            // ✅ Insert new appointment if none exists
            return $this->db->table($this->table)->insert($data);
        }
    }

    // ================================
    // 📋 GET ALL APPOINTMENTS
    // ================================
    public function getAllAppointments()
    {
        return $this->db->table($this->table)->get_all();
    }

    // ================================
    // 👤 GET APPOINTMENTS BY USER
    // ================================
    public function getAppointmentsByUser(int $user_id)
    {
        return $this->db->table($this->table)
                        ->where('user_id', $user_id)
                        ->order_by('appointment_date', 'DESC')
                        ->get_all();
    }

    // ================================
    // 🔍 GET APPOINTMENT BY ID
    // ================================
    public function getAppointmentById(int $id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    // ================================
    // 📅 GET APPOINTMENTS BY STATUS
    // ================================
    public function getAppointmentsByStatus(string $status = null)
    {
        $query = $this->db->table($this->table);
        if (!empty($status)) {
            $query->where('status', $status);
        }
        return $query->order_by('appointment_date', 'DESC')->get_all();
    }

    // ================================
    // ✏️ UPDATE APPOINTMENT
    // ================================
    public function updateAppointment(int $id, array $data)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->update($data);
    }

    // ================================
    // ✅ GET ACCEPTED APPOINTMENTS + USER
    // ================================
    public function getAcceptedAppointmentsWithUser()
    {
        return $this->db->table('appointments a')
                        ->select('a.id, a.appointment_date, u.full_name')
                        ->join('users u', 'u.id = a.user_id')
                        ->where('a.status', 'Accepted')
                        ->get_all();
    }

    // ================================
    // 🔁 UPDATE STATUS (SYNC TO OTHER TABLES)
    // ================================
    public function updateStatusAndSync(int $appointment_id, string $status)
    {
        // ✅ Step 1: Update appointment status
        $this->db->table($this->table)
                 ->where('id', $appointment_id)
                 ->update(['status' => $status]);

        // ✅ Step 2: Fetch the appointment record
        $appointment = $this->db->table($this->table)
                                ->where('id', $appointment_id)
                                ->get();

        if ($appointment) {
            $user_id = $appointment['user_id'];
            $appointment_date = $appointment['appointment_date'];

            // ✅ Step 3: patient_records
            $this->db->table('patient_records')
                     ->where('user_id', $user_id)
                     ->where('appointment_date', $appointment_date)
                     ->update(['status' => $status]);

            // ✅ Step 4: Sync with user_history
            $this->db->table('user_history')
                     ->where('user_id', $user_id)
                     ->where('appointment_date', $appointment_date)
                     ->update(['status' => $status]);
        }

        return true;
    }
}
?>
