<?php
/**
 * Unit Model
 */
class Unit extends Model {
    protected $table = 'units';

    /**
     * Ensure units table exists.
     */
    public function ensureSchema() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS `units` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(120) NOT NULL,
                `short_name` VARCHAR(30) NOT NULL,
                `allow_decimal` TINYINT(1) NOT NULL DEFAULT 0,
                `is_multiple` TINYINT(1) NOT NULL DEFAULT 0,
                `multiplier` DECIMAL(15,4) NULL,
                `base_unit_id` INT UNSIGNED NULL,
                `status` TINYINT(1) NOT NULL DEFAULT 1,
                `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uniq_units_name` (`name`),
                UNIQUE KEY `uniq_units_short_name` (`short_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            if (!$this->db->query($sql)) {
                $this->lastError = $this->db->getError();
                return false;
            }
            return $this->db->execute();
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function getActiveUnits() {
        if (!$this->db->tableExists($this->table)) {
            return [];
        }

        $sql = "SELECT * FROM {$this->table} WHERE status = 1 ORDER BY name ASC";
        if (!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        return $this->db->resultSet();
    }
}
