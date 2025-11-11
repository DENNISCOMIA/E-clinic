<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class RecordModel extends Model
{
    /**
     * ✅ Update Findings & Prescription for a specific patient record
     * Only updates 'findings' and 'prescription' in both patient_records and user_history
     */
public function updateFindingsPrescription($user_id, $appointment_date, $findings, $prescription)
{
    // ✅ Check if patient record exists
    $checkSql = "SELECT COUNT(*) as count FROM patient_records WHERE user_id = :user_id AND DATE(appointment_date) = DATE(:appointment_date)";
    $check = $this->db->raw($checkSql, [
        'user_id' => $user_id,
        'appointment_date' => $appointment_date
    ])->fetch();

    // ✅ If not exist → insert new record
    if ($check && $check['count'] == 0) {
        $insertSql = "
            INSERT INTO patient_records (user_id, appointment_date, findings, prescription, status)
            VALUES (:user_id, :appointment_date, :findings, :prescription, 'Completed')
        ";
        $this->db->raw($insertSql, [
            'user_id' => $user_id,
            'appointment_date' => $appointment_date,
            'findings' => $findings,
            'prescription' => $prescription
        ]);
    } else {
        // ✅ Otherwise, update existing record
        $updateSql = "
            UPDATE patient_records
            SET findings = :findings, prescription = :prescription, status = 'Completed'
            WHERE user_id = :user_id AND DATE(appointment_date) = DATE(:appointment_date)
        ";
        $this->db->raw($updateSql, [
            'findings' => $findings,
            'prescription' => $prescription,
            'user_id' => $user_id,
            'appointment_date' => $appointment_date
        ]);
    }

    // ✅ ALSO update user_history to reflect findings/prescription
    $this->db->table('user_history')
             ->where('user_id', $user_id)
             ->where('appointment_date', $appointment_date)
             ->update([
                 'Findings' => $findings,
                 'Prescription' => $prescription,
                 'status' => 'Completed'
             ]);

    return true;
}


    /**
     * ✅ Add Findings & Prescription manually (Admin)
     * Inserts into both patient_records and user_history
     */
    public function addFindingsPrescriptionManual($data)
    {
        // Insert into patient_records
        $insertPR = $this->db->table('patient_records')->insert([
            'user_id'          => $data['user_id'],
            'fullname'         => $data['fullname'],
            'age'              => $data['age'],
            'contact'          => $data['contact'],
            'appointment_date' => $data['appointment_date'],
            'purpose'          => $data['purpose'],
            'status'           => 'Completed',
            'findings'         => $data['findings'],
            'prescription'     => $data['prescription']
        ]);

        // Get inserted patient_records ID
        $appointment_id = $this->db->lastInsertId();

        // Insert into user_history
        $this->db->table('user_history')->insert([
            'user_id'          => $data['user_id'],
            'appointment_id'   => $appointment_id,
            'appointment_date' => $data['appointment_date'],
            'purpose'          => $data['purpose'],
            'status'           => 'Completed',
            'Findings'         => $data['findings'],
            'Prescription'     => $data['prescription'],
            'fullname'         => $data['fullname']
        ]);

        return $insertPR;
    }

    /**
     * ✅ Get all patient medical records
     */
    public function getAllPatientRecords()
    {
        $table = $this->db->table('patient_records');
        $table->select('*');
        $table->order_by('appointment_date', 'DESC');
        return $table->get_all();
    }

    /**
     * ✅ Search patient records by keyword
     */
    public function searchPatientRecords($keyword)
    {
        $table = $this->db->table('patient_records');
        $table->select('*');
        $table->like('fullname', "%{$keyword}%")
              ->or_like('user_id', "%{$keyword}%")
              ->or_like('purpose', "%{$keyword}%")
              ->or_like('status', "%{$keyword}%")
              ->or_like('findings', "%{$keyword}%")
              ->or_like('prescription', "%{$keyword}%");
        $table->order_by('appointment_date', 'DESC');
        return $table->get_all();
    }

    /**
     * ✅ Get user history
     */
    public function getUserHistory($user_id)
    {
        $table = $this->db->table('user_history');
        $table->where('user_id', $user_id);
        $table->order_by('appointment_date', 'DESC');
        return $table->get_all();
    }

    /**
     * ✅ Add or update a user history record
     * Only updates findings & prescription if record already exists
     */
    public function addToUserHistory($data)
    {
        $table = $this->db->table('user_history');
        $table->where('appointment_id', $data['appointment_id']);
        $existing = $table->get();

        if (!$existing) {
            // Insert new history
            $this->db->table('user_history')->insert([
                'user_id'          => $data['user_id'],
                'appointment_id'   => $data['appointment_id'],
                'appointment_date' => $data['appointment_date'],
                'purpose'          => $data['purpose'],
                'status'           => $data['status'],
                'Findings'         => $data['findings'],
                'Prescription'     => $data['prescription'],
                'fullname'         => $data['fullname']
            ]);
        } else {
            // Update existing history (only findings & prescription)
            $table->where('appointment_id', $data['appointment_id']);
            $table->update([
                'Findings'     => $data['findings'],
                'Prescription' => $data['prescription']
            ]);
        }
    }

     public function getFilteredRecords($search = '', $month = '', $status = '')
    {
        $sql = "
            SELECT 
                a.id AS appointment_id,
                a.user_id,
                pi.full_name AS fullname,
                pi.age,
                pi.phone AS contact,
                a.appointment_date,
                a.purpose,
                a.status,
                COALESCE(pr.findings, '') AS findings,
                COALESCE(pr.prescription, '') AS prescription
            FROM appointments a
            JOIN personal_information pi 
                ON a.user_id = pi.user_id
            LEFT JOIN (
                SELECT 
                    user_id,
                    DATE(appointment_date) AS appt_date,
                    MAX(findings) AS findings,
                    MAX(prescription) AS prescription
                FROM patient_records
                GROUP BY user_id, DATE(appointment_date)
            ) pr 
                ON pr.user_id = a.user_id 
               AND DATE(a.appointment_date) = pr.appt_date
            WHERE 1=1
        ";

        $params = [];

        if ($search !== '') {
            $sql .= " AND (pi.full_name LIKE :search OR a.user_id LIKE :search_id)";
            $params[':search'] = "%{$search}%";
            $params[':search_id'] = "%{$search}%";
        }

        if ($month !== '') {
            $m = (int)$month;
            if ($m >= 1 && $m <= 12) {
                $sql .= " AND MONTH(a.appointment_date) = :month";
                $params[':month'] = $m;
            }
        }

        if ($status !== '') {
            $sql .= " AND a.status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY a.appointment_date DESC";

        try {
            $stmt = $this->db->raw($sql, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>
