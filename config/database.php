<?php
/**
 * Database connection class
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $conn;
    private $error;
    private $stmt;
    
    // Get PDO connection
    public function getConnection() {
        return $this->conn;
    }
    
    public function __construct() {
        // Set DSN (Data Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false // Use real prepared statements
        );
        
        // Create PDO instance
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
    
    // Prepare statement with query
    public function query($query) {
        // Reset statement
        $this->stmt = null;
        
        // Check if connection is valid
        if ($this->conn === null) {
            error_log('Database Error: No database connection when preparing query: ' . $query);
            throw new Exception('Database connection not established');
        }
        
        try {
            $this->stmt = $this->conn->prepare($query);
            
            if ($this->stmt === false) {
                $error = $this->conn->errorInfo();
                error_log('Query Preparation Failed - Query: ' . $query . ' - Error: ' . print_r($error, true));
                throw new Exception('Failed to prepare query');
            }
            
            return true;
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('PDO Query Error: ' . $this->error . ' - Query: ' . $query);
            throw $e; // Re-throw to be caught by the caller
        }
    }
    
    // Bind values
    public function bind($param, $value, $type = null) {
        // Debug: Check if statement is set
        if ($this->stmt === null) {
            error_log('Database Error: Statement is null when trying to bind parameter: ' . $param);
            error_log('Backtrace: ' . print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5), true));
            throw new Exception('Database statement is not prepared');
        }
        
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        try {
            $result = $this->stmt->bindValue($param, $value, $type);
            if ($result === false) {
                $error = $this->stmt->errorInfo();
                error_log('Bind Error - Param: ' . $param . ' - Error: ' . print_r($error, true));
                return false;
            }
            return true;
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('PDO Bind Error: ' . $this->error . ' - Param: ' . $param);
            return false;
        }
    }
    
    // Execute the prepared statement
    public function execute() {
        try {
            return $this->stmt->execute();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('Execute Error: ' . $this->error);
            return false;
        }
    }
    
    // Get result set as array of objects
    public function resultSet() {
        try {
            if($this->stmt === null) {
                $this->error = "No statement prepared. Call query() first.";
                error_log('ResultSet Error: ' . $this->error);
                return [];
            }
            
            if(!$this->execute()) {
                return [];
            }
            
            return $this->stmt->fetchAll();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('ResultSet Error: ' . $this->error);
            return [];
        }
    }
    
    // Get single record as object
    public function single() {
        try {
            if($this->stmt === null) {
                $this->error = "No statement prepared. Call query() first.";
                error_log('Single Error: ' . $this->error);
                return false;
            }
            
            if(!$this->execute()) {
                return false;
            }
            
            return $this->stmt->fetch();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('Single Error: ' . $this->error);
            return false;
        }
    }
    
    // Get row count
    public function rowCount() {
        try {
            return $this->stmt->rowCount();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('RowCount Error: ' . $this->error);
            return 0;
        }
    }
    
    // Get the ID of the last inserted row
    public function lastInsertId() {
        try {
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('LastInsertId Error: ' . $this->error);
            return 0;
        }
    }
    
    // Get error information
    public function errorInfo() {
        if ($this->stmt) {
            return $this->stmt->errorInfo();
        } elseif ($this->conn) {
            return $this->conn->errorInfo();
        }
        return ['No database connection'];
    }
    
    // Transactions
    public function beginTransaction() {
        try {
            return $this->conn->beginTransaction();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('BeginTransaction Error: ' . $this->error);
            return false;
        }
    }
    
    public function endTransaction() {
        try {
            return $this->conn->commit();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('EndTransaction Error: ' . $this->error);
            return false;
        }
    }
    
    public function cancelTransaction() {
        try {
            return $this->conn->rollBack();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('CancelTransaction Error: ' . $this->error);
            return false;
        }
    }
    
    // Get error message
    public function getError() {
        return $this->error;
    }
    
    // Check if a table exists
    public function tableExists($table) {
        try {
            $result = $this->conn->query("SHOW TABLES LIKE '{$table}'");
            return $result->rowCount() > 0;
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log('TableExists Error: ' . $this->error);
            return false;
        }
    }

    // Check if a column exists in a table
    public function columnExists($table, $column) {
        try {
            $stmt = $this->conn->prepare("SHOW COLUMNS FROM `{$table}` LIKE :column");
            $stmt->bindValue(':column', $column, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log('ColumnExists Error: ' . $this->error);
            return false;
        }
    }
}
