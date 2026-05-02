<?php
/**
 * Product Import Controller
 * Handles CSV import operations with chunked processing, validation, and logging
 */

class ProductImportController extends Controller {
    
    /** @var Product */
    private $productModel;
    
    /** @var Category */
    private $categoryModel;
    
    /** Import configuration */
    private const BATCH_SIZE = 100;
    private const MAX_FILE_SIZE = 50 * 1024 * 1024; // 50MB
    private const ALLOWED_MIME_TYPES = ['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'];
    
    /** Required CSV columns */
    private const REQUIRED_COLUMNS = ['name', 'sku', 'price', 'stock_quantity', 'category_id'];
    
    /** All valid CSV columns */
    private const VALID_COLUMNS = [
        'name', 'description', 'sku', 'price', 'sale_price', 'price2', 'price3',
        'stock_quantity', 'category_id', 'brand_id', 'country_id', 'supplier',
        'batch_number', 'status', 'add_date', 'expiry_date', 'tax_id'
    ];
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(); // Initialize parent Controller (sets up $this->db)
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->ensureImportLogsTable();
    }
    
    /**
     * AJAX endpoint: Import CSV file
     * Processes file in chunks with validation
     */
    public function importCsvAjax() {
        // Check admin authorization
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Unauthorized access'], 401);
            return;
        }
        
        // Validate request
        if (!$this->isPost() || !$this->isAjax()) {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
            return;
        }
        
        // CSRF validation
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            $this->json(['success' => false, 'message' => 'Invalid security token'], 403);
            return;
        }
        
        try {
            // Handle file upload
            $file = $this->handleFileUpload();
            if (!$file['success']) {
                $this->json(['success' => false, 'message' => $file['message']], 400);
                return;
            }
            
            // Parse and validate CSV
            $csvData = $this->parseCSV($file['path']);
            if (!$csvData['success']) {
                $this->json(['success' => false, 'message' => $csvData['message']], 400);
                return;
            }
            
            // Process import
            $result = $this->processImport($csvData['data'], $csvData['headers']);
            
            // Log import
            $importId = $this->logImport($result, $file['name']);
            $result['import_id'] = $importId;
            
            // Cleanup
            @unlink($file['path']);
            
            $this->json([
                'success' => true,
                'message' => "Imported {$result['imported']} products",
                'data' => $result
            ]);
            
        } catch (Exception $e) {
            error_log('CSV Import Error: ' . $e->getMessage());
            $this->json([
                'success' => false, 
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle secure file upload
     */
    private function handleFileUpload() {
        if (!isset($_FILES['csv_file'])) {
            return ['success' => false, 'message' => 'No file uploaded'];
        }
        
        $file = $_FILES['csv_file'];
        
        // Check upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds server limit',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds form limit',
                UPLOAD_ERR_PARTIAL => 'File partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file uploaded'
            ];
            return ['success' => false, 'message' => $errors[$file['error']] ?? 'Upload failed'];
        }
        
        // Validate file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'File exceeds 50MB limit'];
        }
        
        // Validate extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            return ['success' => false, 'message' => 'Only CSV files are allowed'];
        }
        
        // Validate MIME type (additional security)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES) && strpos($mimeType, 'text/') !== 0) {
            return ['success' => false, 'message' => 'Invalid file type'];
        }
        
        // Move to secure temporary location
        $uploadDir = UPLOAD_PATH . 'temp/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $safeName = bin2hex(random_bytes(16)) . '.csv';
        $targetPath = $uploadDir . $safeName;
        
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'message' => 'Failed to save uploaded file'];
        }
        
        return [
            'success' => true,
            'path' => $targetPath,
            'name' => $file['name']
        ];
    }
    
    /**
     * Parse CSV file with validation
     */
    private function parseCSV($filePath) {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return ['success' => false, 'message' => 'Cannot read CSV file'];
        }
        
        // Read headers
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return ['success' => false, 'message' => 'Empty CSV file'];
        }
        
        // Normalize headers
        $headers = array_map(function($h) {
            return strtolower(trim($h));
        }, $headers);
        
        // Validate required columns
        $missing = array_diff(self::REQUIRED_COLUMNS, $headers);
        if (!empty($missing)) {
            fclose($handle);
            return [
                'success' => false, 
                'message' => 'Missing required columns: ' . implode(', ', $missing)
            ];
        }
        
        // Read all rows
        $rows = [];
        $rowNum = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $rowNum++;
            if (count($data) >= count($headers)) {
                $row = array_combine($headers, $data);
                $row['__row_num'] = $rowNum;
                $rows[] = $row;
            }
        }
        
        fclose($handle);
        
        return [
            'success' => true,
            'headers' => $headers,
            'data' => $rows
        ];
    }
    
    /**
     * Process import with batching and transactions
     */
    private function processImport($rows, $headers) {
        $totalRows = count($rows);
        $imported = 0;
        $failed = 0;
        $errors = [];
        $importedIds = [];
        $existingSkus = $this->getExistingSkus();
        
        // Process in batches
        $batches = array_chunk($rows, self::BATCH_SIZE);
        
        foreach ($batches as $batchIndex => $batch) {
            $this->db->beginTransaction();
            
            try {
                foreach ($batch as $row) {
                    $validation = $this->validateRow($row, $existingSkus);
                    
                    if (!$validation['valid']) {
                        $failed++;
                        $errors[] = [
                            'row' => $row['__row_num'],
                            'sku' => $row['sku'] ?? 'N/A',
                            'error' => $validation['error']
                        ];
                        continue;
                    }
                    
                    // Prepare product data
                    $productData = $this->prepareProductData($row);
                    
                    // Insert product
                    $productId = $this->productModel->create($productData);
                    
                    if ($productId) {
                        $imported++;
                        $importedIds[] = $productId;
                        $existingSkus[] = $productData['sku'];
                    } else {
                        $failed++;
                        $errors[] = [
                            'row' => $row['__row_num'],
                            'sku' => $row['sku'] ?? 'N/A',
                            'error' => 'Database insert failed'
                        ];
                    }
                }
                
                $this->db->endTransaction();
            
            } catch (Exception $e) {
                $this->db->cancelTransaction();
                error_log("Batch {$batchIndex} failed: " . $e->getMessage());
                
                // Mark all in batch as failed
                foreach ($batch as $row) {
                    $failed++;
                    $errors[] = [
                        'row' => $row['__row_num'],
                        'sku' => $row['sku'] ?? 'N/A',
                        'error' => 'Batch processing failed: ' . $e->getMessage()
                    ];
                }
            }
        }
        
        return [
            'total_rows' => $totalRows,
            'imported' => $imported,
            'failed' => $failed,
            'errors' => array_slice($errors, 0, 100), // Limit errors returned
            'all_errors' => $errors,
            'imported_ids' => $importedIds
        ];
    }
    
    /**
     * Validate a single row
     */
    private function validateRow($row, &$existingSkus) {
        // Required fields
        if (empty(trim($row['name'] ?? ''))) {
            return ['valid' => false, 'error' => 'Product name is required'];
        }
        
        if (empty(trim($row['sku'] ?? ''))) {
            return ['valid' => false, 'error' => 'SKU is required'];
        }
        
        // SKU format validation
        $sku = trim($row['sku']);
        if (strlen($sku) > 50) {
            return ['valid' => false, 'error' => 'SKU exceeds 50 characters'];
        }
        
        // SKU uniqueness check (within file + database)
        if (in_array($sku, $existingSkus)) {
            return ['valid' => false, 'error' => 'Duplicate SKU: ' . $sku];
        }
        
        // Price validation
        $price = $this->parseNumeric($row['price']);
        if ($price === null || $price < 0) {
            return ['valid' => false, 'error' => 'Invalid price value'];
        }
        
        // Stock quantity validation
        $stock = $this->parseNumeric($row['stock_quantity']);
        if ($stock === null || $stock < 0 || !is_int($stock + 0)) {
            return ['valid' => false, 'error' => 'Invalid stock quantity'];
        }
        
        // Category validation (optional - check if exists)
        $categoryId = (int)($row['category_id'] ?? 0);
        if ($categoryId > 0 && !$this->categoryExists($categoryId)) {
            return ['valid' => false, 'error' => 'Category ID ' . $categoryId . ' does not exist'];
        }
        
        // Date validation
        if (!empty($row['expiry_date'])) {
            if (!strtotime($row['expiry_date'])) {
                return ['valid' => false, 'error' => 'Invalid expiry date format'];
            }
        }
        
        if (!empty($row['add_date'])) {
            if (!strtotime($row['add_date'])) {
                return ['valid' => false, 'error' => 'Invalid add date format'];
            }
        }
        
        // Status validation
        $status = strtolower(trim($row['status'] ?? 'active'));
        if (!in_array($status, ['active', 'inactive', 'out_of_stock'])) {
            return ['valid' => false, 'error' => 'Status must be: active, inactive, or out_of_stock'];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Prepare product data for insertion
     */
    private function prepareProductData($row) {
        $data = [
            'name' => sanitize($row['name']),
            'description' => sanitize($row['description'] ?? ''),
            'sku' => strtoupper(trim($row['sku'])),
            'price' => $this->parseNumeric($row['price']) ?? 0,
            'sale_price' => $this->parseNullableNumeric($row['sale_price'] ?? ''),
            'price2' => $this->parseNullableNumeric($row['price2'] ?? ''),
            'price3' => $this->parseNullableNumeric($row['price3'] ?? ''),
            'stock_quantity' => (int)($this->parseNumeric($row['stock_quantity']) ?? 0),
            'category_id' => (int)($row['category_id'] ?? 0) ?: null,
            'brand_id' => (int)($row['brand_id'] ?? 0) ?: null,
            'country_id' => (int)($row['country_id'] ?? 0) ?: null,
            'supplier' => sanitize($row['supplier'] ?? ''),
            'batch_number' => (int)($row['batch_number'] ?? 0) ?: null,
            'status' => in_array(strtolower(trim($row['status'] ?? '')), ['active', 'inactive', 'out_of_stock']) 
                ? strtolower(trim($row['status'])) 
                : 'active',
            'add_date' => !empty($row['add_date']) 
                ? date('Y-m-d', strtotime($row['add_date'])) 
                : date('Y-m-d'),
            'expiry_date' => !empty($row['expiry_date']) 
                ? date('Y-m-d', strtotime($row['expiry_date'])) 
                : null,
            'tax_id' => (int)($row['tax_id'] ?? 0) ?: null
        ];
        
        // Set defaults for missing optional fields
        if (empty($data['price2'])) $data['price2'] = $data['price'];
        if (empty($data['price3'])) $data['price3'] = $data['price'];
        
        return $data;
    }
    
    /**
     * Get all existing SKUs from database
     */
    private function getExistingSkus() {
        $this->db->query("SELECT sku FROM products WHERE sku IS NOT NULL");
        $results = $this->db->resultSet();
        return array_column(array_map(function($r) { return (array)$r; }, $results), 'sku');
    }
    
    /**
     * Check if category exists
     */
    private function categoryExists($categoryId) {
        $this->db->query("SELECT id FROM categories WHERE id = :id LIMIT 1");
        $this->db->bind(':id', $categoryId);
        return (bool)$this->db->single();
    }
    
    /**
     * Parse numeric value
     */
    private function parseNumeric($value) {
        if (is_numeric($value)) {
            return $value + 0; // Convert to int/float
        }
        return null;
    }
    
    /**
     * Parse nullable numeric
     */
    private function parseNullableNumeric($value) {
        $trimmed = trim($value);
        if ($trimmed === '' || $trimmed === null) {
            return null;
        }
        return is_numeric($trimmed) ? $trimmed + 0 : null;
    }
    
    /**
     * Log import to database
     */
    private function logImport($result, $filename) {
        $this->db->query("INSERT INTO product_import_logs 
            (filename, total_rows, imported_count, failed_count, imported_ids, created_by, created_at)
            VALUES (:filename, :total, :imported, :failed, :ids, :by, NOW())");
        
        $this->db->bind(':filename', $filename);
        $this->db->bind(':total', $result['total_rows']);
        $this->db->bind(':imported', $result['imported']);
        $this->db->bind(':failed', $result['failed']);
        $this->db->bind(':ids', json_encode($result['imported_ids']));
        $this->db->bind(':by', $_SESSION['user_id'] ?? 0);
        
        $this->db->execute();
        return $this->db->lastInsertId();
    }
    
    /**
     * Ensure import logs table exists
     */
    private function ensureImportLogsTable() {
        $this->db->query("CREATE TABLE IF NOT EXISTS product_import_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL,
            total_rows INT NOT NULL DEFAULT 0,
            imported_count INT NOT NULL DEFAULT 0,
            failed_count INT NOT NULL DEFAULT 0,
            imported_ids JSON,
            error_log TEXT,
            created_by INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            undone_at DATETIME NULL,
            undone_by INT NULL,
            INDEX idx_created_at (created_at),
            INDEX idx_created_by (created_by)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $this->db->execute();
    }
    
    /**
     * Download sample CSV template
     */
    public function downloadSampleCsv() {
        if (!isAdmin()) {
            redirect('user/login');
        }
        
        $filename = 'products_import_template.csv';
        $headers = self::VALID_COLUMNS;
        
        // Sample data
        $samples = [
            ['Sample Product 1', 'This is a sample product description', 'SKU001', '99.99', '79.99', '89.99', '84.99', '100', '1', '1', '1', 'Supplier Name', 'B001', 'active', date('Y-m-d'), date('Y-m-d', strtotime('+1 year')), '1'],
            ['Sample Product 2', 'Another sample product', 'SKU002', '149.99', '', '', '', '50', '2', '', '', '', '', 'active', date('Y-m-d'), '', ''],
        ];
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($output, $headers);
        
        // Sample rows
        foreach ($samples as $sample) {
            fputcsv($output, $sample);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Undo last import
     */
    public function undoImport() {
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
            return;
        }
        
        $importId = (int)($_GET['id'] ?? 0);
        if (!$importId) {
            $this->json(['success' => false, 'message' => 'Invalid import ID'], 400);
            return;
        }
        
        try {
            // Get import log
            $this->db->query("SELECT * FROM product_import_logs WHERE id = :id AND undone_at IS NULL");
            $this->db->bind(':id', $importId);
            $log = $this->db->single();
            
            if (!$log) {
                $this->json(['success' => false, 'message' => 'Import not found or already undone'], 404);
                return;
            }
            
            $log = (array)$log;
            $importedIds = json_decode($log['imported_ids'], true) ?: [];
            
            if (empty($importedIds)) {
                $this->json(['success' => false, 'message' => 'No products to undo'], 400);
                return;
            }
            
            // Soft delete imported products
            $placeholders = implode(',', array_fill(0, count($importedIds), '?'));
            $this->db->query("UPDATE products SET status = 'inactive' WHERE id IN ($placeholders)");
            
            foreach ($importedIds as $idx => $id) {
                $this->db->bind($idx + 1, $id);
            }
            
            $this->db->execute();
            
            // Mark import as undone
            $this->db->query("UPDATE product_import_logs SET undone_at = NOW(), undone_by = :by WHERE id = :id");
            $this->db->bind(':by', $_SESSION['user_id'] ?? 0);
            $this->db->bind(':id', $importId);
            $this->db->execute();
            
            $this->json([
                'success' => true,
                'message' => count($importedIds) . ' products have been removed'
            ]);
            
        } catch (Exception $e) {
            error_log('Undo Import Error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Undo failed: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get import history (for admin view)
     */
    public function importHistory() {
        if (!isAdmin()) {
            redirect('user/login');
        }
        
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $this->db->query("SELECT COUNT(*) as total FROM product_import_logs");
        $total = (int)(($this->db->single()->total ?? 0));
        
        $this->db->query("SELECT 
            l.*,
            CONCAT(u.first_name, ' ', u.last_name) as created_by_name,
            CONCAT(u2.first_name, ' ', u2.last_name) as undone_by_name
            FROM product_import_logs l
            LEFT JOIN users u ON l.created_by = u.id
            LEFT JOIN users u2 ON l.undone_by = u2.id
            ORDER BY l.created_at DESC
            LIMIT :limit OFFSET :offset");
        $this->db->bind(':limit', $perPage);
        $this->db->bind(':offset', $offset);
        
        $logs = $this->db->resultSet();
        
        $this->view('admin/products/import_history', [
            'logs' => $logs,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }
}
