<?php
/**
 * Base Model class
 */
require_once ROOT_PATH . 'config/database.php';

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $lastError = '';
    
    public function __construct() {
        try {
            $this->db = new Database();
        } catch (Exception $e) {
            error_log('Database connection error in Model: ' . $e->getMessage());
            throw new Exception('Failed to connect to database. Please check your configuration.');
        }
    }
    
    /**
     * Get all records from the table
     * 
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @return array
     */
    public function getAll($orderBy = null, $order = 'ASC') {
        $sql = "SELECT * FROM {$this->table}";
        
        if($orderBy) {
            $sql .= " ORDER BY {$orderBy} {$order}";
        }
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get a single record by ID
     * 
     * @param int $id The ID of the record
     * @return object|bool The record or false if not found
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        
        if(!$result) {
            $this->lastError = "Record with ID {$id} not found";
        }
        
        return $result;
    }
    
    /**
     * Get records by a specific column value
     * 
     * @param string $column The column name
     * @param mixed $value The value to search for
     * @return array
     */
    public function getBy($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':value', $value);
        return $this->db->resultSet();
    }
    
    /**
     * Get a single record by a specific column value
     * 
     * @param string $column The column name
     * @param mixed $value The value to search for
     * @return object|bool The record or false if not found
     */
    public function getSingleBy($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':value', $value);
        return $this->db->single();
    }
    
    /**
     * Create a new record
     * 
     * @param array $data The data to insert
     * @return int|bool The ID of the new record or false on failure
     */
    public function create($data) {
        // Validate data
        if(empty($data) || !is_array($data)) {
            $this->lastError = "Invalid data provided for create operation";
            return false;
        }
        
        // Prepare column names and placeholders
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        // Bind values
        foreach($data as $key => $value) {
            if(!$this->db->bind(':' . $key, $value)) {
                $this->lastError = $this->db->getError();
                return false;
            }
        }
        
        // Execute
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            $this->lastError = $this->db->getError();
            return false;
        }
    }
    
    /**
     * Update an existing record
     * 
     * @param int $id The ID of the record to update
     * @param array $data The data to update
     * @return bool
     */
    public function update($id, $data) {
        // Validate data
        if(empty($data) || !is_array($data)) {
            $this->lastError = "Invalid data provided for update operation";
            return false;
        }
        
        // Check if record exists
        if(!$this->getById($id)) {
            $this->lastError = "Record with ID {$id} not found";
            return false;
        }
        
        // Prepare SET clause
        $setClause = '';
        foreach(array_keys($data) as $key) {
            $setClause .= "{$key} = :{$key}, ";
        }
        $setClause = rtrim($setClause, ', ');
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        // Bind values
        if(!$this->db->bind(':id', $id)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        foreach($data as $key => $value) {
            if(!$this->db->bind(':' . $key, $value)) {
                $this->lastError = $this->db->getError();
                return false;
            }
        }
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            $this->lastError = $this->db->getError();
            return false;
        }
    }
    
    /**
     * Delete a record
     * 
     * @param int $id The ID of the record to delete
     * @return bool
     */
    public function delete($id) {
        // Check if record exists
        if(!$this->getById($id)) {
            $this->lastError = "Record with ID {$id} not found";
            return false;
        }
        
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':id', $id);
        
        if($this->db->execute()) {
            return true;
        } else {
            $this->lastError = $this->db->getError();
            return false;
        }
    }
    
    /**
     * Count all records
     * 
     * @return int
     */
    public function count() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return 0;
        }
        
        $result = $this->db->single();
        return $result ? $result['count'] : 0;
    }
    
    /**
     * Get paginated results
     * 
     * @param int $page The current page
     * @param int $perPage Number of items per page
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @return array
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = null, $order = 'ASC') {
        // Validate parameters
        $page = max(1, intval($page));
        $perPage = max(1, intval($perPage));
        
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Build query
        $sql = "SELECT * FROM {$this->table}";
        
        if($orderBy) {
            $sql .= " ORDER BY {$orderBy} {$order}";
        }
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [
                'data' => [],
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => 0,
                'total_pages' => 0
            ];
        }
        
        $results = $this->db->resultSet();
        
        // Get total count
        $totalCount = $this->count();
        $totalPages = ceil($totalCount / $perPage);
        
        return [
            'data' => $results,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $totalCount,
            'total_pages' => $totalPages
        ];
    }
    
    /**
     * Search records by a specific column
     * 
     * @param string $column The column to search in
     * @param string $keyword The keyword to search for
     * @return array
     */
    public function search($column, $keyword) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} LIKE :keyword";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':keyword', '%' . $keyword . '%');
        return $this->db->resultSet();
    }
    
    /**
     * Execute a custom query
     * 
     * @param string $sql The SQL query
     * @param array $params The parameters to bind
     * @param bool $single Whether to return a single record or all records
     * @return array|object|bool
     */
    public function query($sql, $params = [], $single = false) {
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return $single ? false : [];
        }
        
        foreach($params as $key => $value) {
            if(!$this->db->bind(':' . $key, $value)) {
                $this->lastError = $this->db->getError();
                return $single ? false : [];
            }
        }
        
        if($single) {
            return $this->db->single();
        } else {
            return $this->db->resultSet();
        }
    }
    
    /**
     * Get the last error message
     * 
     * @return string
     */
    public function getLastError() {
        return $this->lastError;
    }
    
    /**
     * Check if a record exists
     * 
     * @param int $id The ID of the record
     * @return bool
     */
    public function exists($id) {
        $sql = "SELECT 1 FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        
        return !empty($result);
    }
    
    /**
     * Begin a database transaction
     * 
     * @return bool
     */
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }
    
    /**
     * Commit a database transaction
     * 
     * @return bool
     */
    public function commitTransaction() {
        return $this->db->endTransaction();
    }
    
    /**
     * Rollback a database transaction
     * 
     * @return bool
     */
    public function rollbackTransaction() {
        return $this->db->cancelTransaction();
    }
}
