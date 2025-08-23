<?php
/**
 * Setting Model
 * Handles application settings
 */
class Setting extends Model {
    protected $table = 'settings';
    
    /**
     * Get all settings
     * 
     * @return array
     */
    public function getAllSettings() {
        $sql = "SELECT * FROM {$this->table}";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $settings = $this->db->resultSet();
        $result = [];
        
        // Convert to key-value pairs
        foreach($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        
        return $result;
    }
    
    /**
     * Get setting by key
     * 
     * @param string $key Setting key
     * @param mixed $default Default value if setting doesn't exist
     * @return mixed
     */
    public function getSetting($key, $default = null) {
        $sql = "SELECT value FROM {$this->table} WHERE `key` = :key";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return $default;
        }
        
        $this->db->bind(':key', $key);
        $result = $this->db->single();
        
        return $result ? $result['value'] : $default;
    }
    
    /**
     * Update or create setting
     * 
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @return bool
     */
    public function updateSetting($key, $value) {
        try {
            // Sanitize key and prepare
            $key = trim($key);
            if (empty($key)) {
                throw new Exception('Setting key cannot be empty');
            }

            // Determine available columns to avoid schema mismatch errors
            $hasGroup = $this->db->columnExists($this->table, 'group');
            $hasCreatedAt = $this->db->columnExists($this->table, 'created_at');
            $hasUpdatedAt = $this->db->columnExists($this->table, 'updated_at');

            // Check if setting exists
            $sql = "SELECT id FROM {$this->table} WHERE `key` = :key";

            if(!$this->db->query($sql)) {
                throw new Exception('Failed to prepare query: ' . $this->db->getError());
            }

            $this->db->bind(':key', $key);
            $result = $this->db->single();

            if($result) {
                // Update existing setting; include updated_at only if present
                $sql = "UPDATE {$this->table} SET value = :value";
                if ($hasUpdatedAt) {
                    $sql .= ", updated_at = NOW()";
                }
                $sql .= " WHERE `key` = :key";

                if(!$this->db->query($sql)) {
                    throw new Exception('Failed to prepare update query: ' . $this->db->getError());
                }

                $this->db->bind(':value', $value);
                $this->db->bind(':key', $key);

                if(!$this->db->execute()) {
                    throw new Exception('Failed to execute update: ' . $this->db->getError());
                }

                return true;
            } else {
                // Create new setting; build columns dynamically
                $columns = ['`key`', 'value'];
                $placeholders = [':key', ':value'];
                if ($hasGroup) {
                    $columns[] = '`group`';
                    $placeholders[] = "'general'"; // literal
                }
                if ($hasCreatedAt) {
                    $columns[] = 'created_at';
                    $placeholders[] = 'NOW()';
                }
                if ($hasUpdatedAt) {
                    $columns[] = 'updated_at';
                    $placeholders[] = 'NOW()';
                }

                $columnsSql = implode(', ', $columns);
                $valuesSql = implode(', ', $placeholders);
                $sql = "INSERT INTO {$this->table} ({$columnsSql}) VALUES ({$valuesSql})";

                if(!$this->db->query($sql)) {
                    throw new Exception('Failed to prepare insert query: ' . $this->db->getError());
                }

                $this->db->bind(':key', $key);
                $this->db->bind(':value', $value);

                if(!$this->db->execute()) {
                    throw new Exception('Failed to execute insert: ' . $this->db->getError());
                }

                return true;
            }
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Setting update error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete setting
     * 
     * @param string $key Setting key
     * @return bool
     */
    public function deleteSetting($key) {
        $sql = "DELETE FROM {$this->table} WHERE `key` = :key";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':key', $key);
        return $this->db->execute();
    }
    
    /**
     * Get the database instance
     * 
     * @return Database
     */
    public function getDb() {
        return $this->db;
    }
    
    /**
     * Get settings by group
     * 
     * @param string $group
     * @return array
     */
    public function getSettingsByGroup($group) {
        $sql = "SELECT * FROM {$this->table} WHERE `group` = :group";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':group', $group);
        $settings = $this->db->resultSet();
        $result = [];
        
        // Convert to key-value pairs
        foreach($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        
        return $result;
    }
}
