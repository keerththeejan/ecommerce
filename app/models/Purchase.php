<?php
/**
 * Purchase Model
 * Restores required APIs for ListPurchaseController and basic purchase operations
 */
class Purchase {
    private $db;
    private $table = 'purchases';
    private $itemsTable = 'purchase_items';
    private $paymentTable = 'purchase_payments';

    public function __construct($db = null) {
        if ($db instanceof Database) {
            $this->db = $db;
        } else {
            $this->db = new Database();
        }
    }

    private function getTableColumns($tableName) {
        try {
            $this->db->query("SHOW COLUMNS FROM `{$tableName}`");
            $rows = $this->db->resultSet();
            $cols = [];
            foreach ((array)$rows as $r) {
                if (is_array($r)) { $cols[] = $r['Field'] ?? null; }
                else if (is_object($r)) { $cols[] = $r->Field ?? null; }
            }
            return array_filter($cols);
        } catch (Exception $e) {
            return [];
        }
    }

    // ---------- Listing ----------
    public function getAllPurchases($page = 1, $perPage = 20) {
        $offset = max(0, ($page - 1) * $perPage);
        $select = "SELECT p.*, s.name AS supplier_name ";
        $from = "FROM {$this->table} p LEFT JOIN suppliers s ON p.supplier_id = s.id ";
        $order = "ORDER BY p.purchase_date DESC, p.id DESC ";
        $limit = "LIMIT :limit OFFSET :offset";

        $this->db->query($select . $from . $order . $limit);
        $this->db->bind(':limit', (int)$perPage);
        $this->db->bind(':offset', (int)$offset);
        return $this->db->resultSet();
    }

    public function countAllPurchases() {
        $this->db->query("SELECT COUNT(*) AS cnt FROM {$this->table}");
        $row = $this->db->single();
        if (is_array($row)) return (int)($row['cnt'] ?? 0);
        if (is_object($row)) return (int)($row->cnt ?? 0);
        return 0;
    }

    // ---------- Single purchase ----------
    public function getPurchaseById($id) {
        $this->db->query("SELECT p.*, s.name AS supplier_name, s.email AS supplier_email, s.phone AS supplier_phone, s.address AS supplier_address
                          FROM {$this->table} p
                          LEFT JOIN suppliers s ON p.supplier_id = s.id
                          WHERE p.id = :id");
        $this->db->bind(':id', (int)$id);
        $row = $this->db->single();
        return $row ?: null;
    }

    public function getPurchaseItems($purchaseId) {
        $this->db->query("SELECT pi.*, pr.name AS product_name, pr.sku AS product_sku
                          FROM {$this->itemsTable} pi
                          LEFT JOIN products pr ON pi.product_id = pr.id
                          WHERE pi.purchase_id = :pid");
        $this->db->bind(':pid', (int)$purchaseId);
        return $this->db->resultSet();
    }

    public function getPurchasePayments($purchaseId) {
        if (!$this->db->tableExists($this->paymentTable)) return [];
        $this->db->query("SELECT * FROM {$this->paymentTable} WHERE purchase_id = :pid ORDER BY payment_date DESC, id DESC");
        $this->db->bind(':pid', (int)$purchaseId);
        return $this->db->resultSet();
    }

    // ---------- Filters ----------
    public function getFilteredPurchases($filters, $page = 1, $perPage = 20) {
        $where = [];
        $params = [];

        if (!empty($filters['start_date'])) { $where[] = 'p.purchase_date >= :start_date'; $params[':start_date'] = $filters['start_date']; }
        if (!empty($filters['end_date'])) { $where[] = 'p.purchase_date <= :end_date'; $params[':end_date'] = $filters['end_date']; }
        if (!empty($filters['supplier_id'])) { $where[] = 'p.supplier_id = :supplier_id'; $params[':supplier_id'] = $filters['supplier_id']; }
        if (!empty($filters['status'])) { $where[] = 'p.status = :status'; $params[':status'] = $filters['status']; }
        if (!empty($filters['payment_status'])) { $where[] = 'p.payment_status = :payment_status'; $params[':payment_status'] = $filters['payment_status']; }
        if (!empty($filters['search'])) {
            $where[] = '(s.name LIKE :search OR p.purchase_no LIKE :search)';
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql = "SELECT p.*, s.name AS supplier_name FROM {$this->table} p LEFT JOIN suppliers s ON p.supplier_id = s.id";
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY p.purchase_date DESC, p.id DESC LIMIT :limit OFFSET :offset';

        $this->db->query($sql);
        foreach ($params as $k => $v) { $this->db->bind($k, $v); }
        $this->db->bind(':limit', (int)$perPage);
        $this->db->bind(':offset', (int)max(0, ($page - 1) * $perPage));
        return $this->db->resultSet();
    }

    public function countFilteredPurchases($filters) {
        $where = [];
        $params = [];
        if (!empty($filters['start_date'])) { $where[] = 'purchase_date >= :start_date'; $params[':start_date'] = $filters['start_date']; }
        if (!empty($filters['end_date'])) { $where[] = 'purchase_date <= :end_date'; $params[':end_date'] = $filters['end_date']; }
        if (!empty($filters['supplier_id'])) { $where[] = 'supplier_id = :supplier_id'; $params[':supplier_id'] = $filters['supplier_id']; }
        if (!empty($filters['status'])) { $where[] = 'status = :status'; $params[':status'] = $filters['status']; }
        if (!empty($filters['payment_status'])) { $where[] = 'payment_status = :payment_status'; $params[':payment_status'] = $filters['payment_status']; }
        if (!empty($filters['search'])) {
            $where[] = '(purchase_no LIKE :search)';
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql = "SELECT COUNT(*) AS cnt FROM {$this->table}";
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $this->db->query($sql);
        foreach ($params as $k => $v) { $this->db->bind($k, $v); }
        $row = $this->db->single();
        if (is_array($row)) return (int)($row['cnt'] ?? 0);
        if (is_object($row)) return (int)($row->cnt ?? 0);
        return 0;
    }

    // ---------- Mutations ----------
    /**
     * Create a purchase with items. Returns inserted purchase ID on success, false on failure.
     * Expected $data keys: supplier_id, purchase_no, location_id, purchase_date, status, notes, document_path, items[]
     */
    public function create($data) {
        $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
        // Compute totals
        $totalAmount = 0.0;
        foreach ($items as $it) {
            $qty = (float)($it['quantity'] ?? 0);
            $price = (float)($it['unit_price'] ?? 0);
            $disc = isset($it['discount_percent']) ? (float)$it['discount_percent'] : 0.0;
            $unitAfterDiscount = max($price - ($price * ($disc/100.0)), 0);
            $lineTotal = $unitAfterDiscount * max($qty, 0);
            $totalAmount += $lineTotal;
        }

        // Prepare dynamic insert for purchases table
        $cols = $this->getTableColumns($this->table);
        $allowed = [
            'supplier_id' => $data['supplier_id'] ?? null,
            'purchase_no' => $data['purchase_no'] ?? null,
            'location_id' => $data['location_id'] ?? null,
            'purchase_date' => $data['purchase_date'] ?? date('Y-m-d'),
            'status' => $data['status'] ?? 'pending',
            'notes' => $data['notes'] ?? null,
            'document_path' => $data['document_path'] ?? null,
            'total_amount' => $totalAmount,
        ];
        $insertCols = [];
        $placeholders = [];
        $bindings = [];
        foreach ($allowed as $k => $v) {
            if (in_array($k, $cols, true)) {
                $insertCols[] = $k;
                $placeholders[] = ':' . $k;
                $bindings[':' . $k] = $v;
            }
        }
        // created_at if exists
        if (in_array('created_at', $cols, true)) {
            $insertCols[] = 'created_at';
            $placeholders[] = 'NOW()';
        }
        if (empty($insertCols)) {
            return false;
        }

        $sql = 'INSERT INTO ' . $this->table . ' (' . implode(',', $insertCols) . ') VALUES (' . implode(',', $placeholders) . ')';
        $this->db->query($sql);
        foreach ($bindings as $k => $v) { $this->db->bind($k, $v); }
        if (!$this->db->execute()) {
            return false;
        }

        // Get inserted purchase ID (portable approach)
        $purchaseId = null;
        if (method_exists($this->db, 'lastInsertId')) {
            $purchaseId = (int)$this->db->lastInsertId();
        }
        if (!$purchaseId) {
            $this->db->query('SELECT LAST_INSERT_ID() AS id');
            $row = $this->db->single();
            $purchaseId = is_array($row) ? (int)($row['id'] ?? 0) : (int)($row->id ?? 0);
        }
        if (!$purchaseId) {
            return false;
        }

        // Insert items if table exists
        if ($this->db->tableExists($this->itemsTable) && !empty($items)) {
            $itemCols = $this->getTableColumns($this->itemsTable);
            foreach ($items as $it) {
                $pid = (int)($it['product_id'] ?? 0);
                $qty = (float)($it['quantity'] ?? 0);
                $price = (float)($it['unit_price'] ?? 0);
                $disc = isset($it['discount_percent']) ? (float)$it['discount_percent'] : 0.0;
                $margin = isset($it['profit_margin']) ? (float)$it['profit_margin'] : 0.0;
                $unitAfterDiscount = max($price - ($price * ($disc/100.0)), 0);
                $lineTotal = $unitAfterDiscount * max($qty, 0);

                $insCols = ['purchase_id','product_id','quantity','unit_price'];
                $vals = [':purchase_id'=> $purchaseId, ':product_id'=> $pid, ':quantity'=> $qty, ':unit_price'=> $price];
                $phs = [':purchase_id', ':product_id', ':quantity', ':unit_price'];
                if (in_array('discount_percent', $itemCols, true)) { $insCols[] = 'discount_percent'; $phs[]=':discount_percent'; $vals[':discount_percent']=$disc; }
                if (in_array('profit_margin', $itemCols, true)) { $insCols[] = 'profit_margin'; $phs[]=':profit_margin'; $vals[':profit_margin']=$margin; }
                if (in_array('line_total', $itemCols, true)) { $insCols[] = 'line_total'; $phs[]=':line_total'; $vals[':line_total']=$lineTotal; }

                $sqlIt = 'INSERT INTO ' . $this->itemsTable . ' (' . implode(',', $insCols) . ') VALUES (' . implode(',', $phs) . ')';
                $this->db->query($sqlIt);
                foreach ($vals as $k => $v) { $this->db->bind($k, $v); }
                $this->db->execute();

                // Update stock if products table has stock_quantity
                $this->db->query("SHOW COLUMNS FROM products LIKE 'stock_quantity'");
                $hasStock = $this->db->resultSet();
                if (!empty($hasStock) && $qty > 0 && ($data['status'] ?? 'pending') === 'received') {
                    $this->db->query('UPDATE products SET stock_quantity = COALESCE(stock_quantity,0) + :qty WHERE id = :pid');
                    $this->db->bind(':qty', $qty);
                    $this->db->bind(':pid', $pid);
                    $this->db->execute();
                }
            }
        }

        return $purchaseId;
    }

    public function deletePurchase($id) {
        // Rely on FK cascade if present; otherwise delete items first
        if ($this->db->tableExists($this->itemsTable)) {
            $this->db->query("DELETE FROM {$this->itemsTable} WHERE purchase_id = :id");
            $this->db->bind(':id', (int)$id);
            $this->db->execute();
        }
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    // Backward-compatible alias used by controller
    public function delete($id) {
        return $this->deletePurchase($id);
    }

    public function addPayment($data) {
        if (!$this->db->tableExists($this->paymentTable)) return false;

        // Build dynamic insert based on available columns to avoid unknown column errors
        $cols = $this->getTableColumns($this->paymentTable);
        $insertCols = [];
        $placeholders = [];
        $binds = [];

        // Required
        if (in_array('purchase_id', $cols, true)) { $insertCols[] = 'purchase_id'; $placeholders[]=':purchase_id'; $binds[':purchase_id']=(int)$data['purchase_id']; }
        if (in_array('amount', $cols, true)) { $insertCols[] = 'amount'; $placeholders[]=':amount'; $binds[':amount']=(float)$data['amount']; }
        if (in_array('payment_date', $cols, true)) { $insertCols[] = 'payment_date'; $placeholders[]='NOW()'; }
        if (in_array('payment_method', $cols, true)) { $insertCols[] = 'payment_method'; $placeholders[]=':payment_method'; $binds[':payment_method']=$data['payment_method'] ?? 'cash'; }

        // Optional
        if (in_array('transaction_id', $cols, true)) { $insertCols[] = 'transaction_id'; $placeholders[]=':transaction_id'; $binds[':transaction_id']=$data['transaction_id'] ?? null; }
        if (in_array('notes', $cols, true)) { $insertCols[] = 'notes'; $placeholders[]=':notes'; $binds[':notes']=$data['notes'] ?? null; }
        if (in_array('status', $cols, true)) { $insertCols[] = 'status'; $placeholders[]="'completed'"; }
        if (in_array('created_at', $cols, true)) { $insertCols[] = 'created_at'; $placeholders[]='NOW()'; }

        if (empty($insertCols)) return false;

        $sql = 'INSERT INTO ' . $this->paymentTable . ' (' . implode(',', $insertCols) . ') VALUES (' . implode(',', $placeholders) . ')';
        $this->db->query($sql);
        foreach ($binds as $k=>$v) { $this->db->bind($k, $v); }
        if (!$this->db->execute()) return false;

        // Update paid_amount and payment_status on purchases
        $this->db->query("UPDATE {$this->table} SET paid_amount = COALESCE(paid_amount,0) + :amt WHERE id = :id");
        $this->db->bind(':amt', (float)$data['amount']);
        $this->db->bind(':id', (int)$data['purchase_id']);
        $this->db->execute();

        // Recompute payment_status
        $this->db->query("SELECT total_amount, COALESCE(paid_amount,0) AS paid FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', (int)$data['purchase_id']);
        $row = $this->db->single();
        if ($row) {
            $paid = is_array($row) ? (float)$row['paid'] : (float)$row->paid;
            $total = is_array($row) ? (float)$row['total_amount'] : (float)$row->total_amount;
            $status = ($paid >= $total && $total > 0) ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid');
            // Use fallback to 'pending' if unpaid not allowed
            if (!in_array($status, ['paid','partial'])) { $status = 'pending'; }
            $this->db->query("UPDATE {$this->table} SET payment_status = :ps WHERE id = :id");
            $this->db->bind(':ps', $status);
            $this->db->bind(':id', (int)$data['purchase_id']);
            $this->db->execute();
        }
        return true;
    }

    public function processReturn($data) {
        // Stubbed success to satisfy controller; extend with actual return logic as needed
        return true;
    }

    // ---------- Dashboard stats ----------
    public function getTotalPurchases() {
        return $this->countAllPurchases();
    }

    public function countPurchasesByStatus($status) {
        $this->db->query("SELECT COUNT(*) AS cnt FROM {$this->table} WHERE status = :status");
        $this->db->bind(':status', $status);
        $row = $this->db->single();
        if (is_array($row)) return (int)($row['cnt'] ?? 0);
        if (is_object($row)) return (int)($row->cnt ?? 0);
        return 0;
    }

    public function getMonthlySpending() {
        $this->db->query("SELECT DATE_FORMAT(purchase_date, '%Y-%m') AS month, SUM(total_amount) AS total
                          FROM {$this->table}
                          GROUP BY month
                          ORDER BY month DESC
                          LIMIT 12");
        return $this->db->resultSet();
    }

    public function getPendingPaymentsTotal() {
        $this->db->query("SELECT SUM(GREATEST(total_amount - COALESCE(paid_amount,0), 0)) AS pending_total FROM {$this->table} WHERE payment_status IS NULL OR payment_status NOT IN ('paid')");
        $row = $this->db->single();
        if (is_array($row)) return (float)($row['pending_total'] ?? 0);
        if (is_object($row)) return (float)($row->pending_total ?? 0);
        return 0.0;
    }

    /**
     * Get recent purchases for dashboard
     * Returns: id, purchase_date, supplier_name, total_items, total_amount, status
     */
    public function getRecentPurchases($limit = 5) {
        $limit = max(1, (int)$limit);

        $hasItems = method_exists($this->db, 'tableExists') ? $this->db->tableExists($this->itemsTable) : true;

        $select = "SELECT p.id, p.purchase_date, s.name AS supplier_name, p.total_amount, p.status";
        if ($hasItems) {
            $select .= ", COALESCE(SUM(pi.quantity), 0) AS total_items";
        } else {
            $select .= ", 0 AS total_items";
        }

        $sql = $select . " FROM {$this->table} p LEFT JOIN suppliers s ON p.supplier_id = s.id";
        if ($hasItems) {
            $sql .= " LEFT JOIN {$this->itemsTable} pi ON pi.purchase_id = p.id";
        }

        if ($hasItems) {
            $sql .= " GROUP BY p.id, p.purchase_date, s.name, p.total_amount, p.status";
        }

        $sql .= " ORDER BY p.purchase_date DESC, p.id DESC LIMIT :limit";

        $this->db->query($sql);
        $this->db->bind(':limit', (int)$limit);
        return $this->db->resultSet();
    }
}
