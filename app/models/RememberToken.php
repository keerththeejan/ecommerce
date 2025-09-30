<?php
/**
 * RememberToken Model
 * Handles remember me tokens for user authentication
 */
class RememberToken extends Model {
    protected $table = 'remember_tokens';
    
    /**
     * Find token
     * 
     * @param string $token Token
     * @return array|bool
     */
    public function findToken($token) {
        return $this->getSingleBy('token', $token);
    }
    
    /**
     * Find valid token
     * 
     * @param string $token Token
     * @return array|bool
     */
    public function findValidToken($token) {
        $sql = "SELECT * FROM {$this->table} WHERE token = :token AND expires_at > :now";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':token', $token);
        $this->db->bind(':now', date('Y-m-d H:i:s'));
        
        return $this->db->single();
    }
    
    /**
     * Delete token
     * 
     * @param string $token Token
     * @return bool
     */
    public function deleteToken($token) {
        $sql = "DELETE FROM {$this->table} WHERE token = :token";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':token', $token);
        
        return $this->db->execute();
    }
    
    /**
     * Delete expired tokens
     * 
     * @return bool
     */
    public function deleteExpiredTokens() {
        $sql = "DELETE FROM {$this->table} WHERE expires_at < :now";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':now', date('Y-m-d H:i:s'));
        
        return $this->db->execute();
    }
    
    /**
     * Delete user tokens
     * 
     * @param int $userId User ID
     * @return bool
     */
    public function deleteUserTokens($userId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = :user_id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
}
