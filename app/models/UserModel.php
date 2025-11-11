<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UserModel extends Model
{
    protected $table = 'users';

    // =========================
    // 👤 USER PROFILE FUNCTIONS
    // =========================
    public function getUserById($id)
    {
        return $this->db->table($this->table)->where('user_id', $id)->get();
    }

    public function getAllUsers()
    {
        return $this->db->table($this->table)->get_all();
    }

    public function getProfile($user_id)
    {
        return $this->db->table('personal_information')->where('user_id', $user_id)->get();
    }

    public function updateProfile($user_id, $data)
    {
        return $this->db->table('personal_information')->where('user_id', $user_id)->update($data);
    }

    public function saveProfile($data)
    {
        return $this->db->table('personal_information')->insert($data);
    }

    // =========================
    // 📜 USER HISTORY FUNCTIONS
    // =========================
public function countUserHistory($user_id) {
    return $this->db->table('user_history')
                    ->where('user_id', $user_id)
                    ->count();
}

public function getUserHistory($user_id, $limit, $offset) {
    return $this->db->table('user_history')
                    ->where('user_id', $user_id)
                    ->orderBy('appointment_date', 'DESC')
                    ->limit($limit, $offset) // Lavalust handles LIMIT internally, no ? placeholders
                    ->getAll();
}


}
?>
