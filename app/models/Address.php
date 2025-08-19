<?php
/**
 * Address Model
 */
class Address extends Model {
    protected $table = 'addresses';

    public function getByUser($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY is_default DESC, id DESC";
        if(!$this->db->query($sql)) { return []; }
        $this->db->bind(':user_id', (int)$userId);
        return $this->db->resultSet();
    }

    public function getByIdForUser($id, $userId) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND user_id = :user_id LIMIT 1";
        if(!$this->db->query($sql)) { return false; }
        $this->db->bind(':id', (int)$id);
        $this->db->bind(':user_id', (int)$userId);
        return $this->db->single();
    }

    public function unsetDefaultForUser($userId, $type) {
        $sql = "UPDATE {$this->table} SET is_default = 0 WHERE user_id = :user_id AND type = :type";
        if(!$this->db->query($sql)) { return false; }
        $this->db->bind(':user_id', (int)$userId);
        $this->db->bind(':type', $type);
        return $this->db->execute();
    }

    public function createAddress($data) {
        return $this->create($data);
    }

    public function updateAddress($id, $data) {
        return $this->update($id, $data);
    }

    public function deleteAddress($id) {
        return $this->delete($id);
    }
}
