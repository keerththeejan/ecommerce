<?php
/**
 * Wishlist Model
 * Handles database operations for wishlist functionality
 */
class Wishlist extends Model {

    protected $table = 'wishlist';

    public function __construct() {
        parent::__construct();
        $this->createWishlistTableIfNotExists();
        $this->ensureUpdatedAtColumn();
        $this->ensureUniqueUserProductIndex();
    }

    /**
     * Create wishlist table if it doesn't exist
     */
    private function createWishlistTableIfNotExists() {
        try {
            $this->db->query("SHOW TABLES LIKE 'wishlist'");
            $tableExists = $this->db->single();

            if (!$tableExists) {
                $sql = "
                CREATE TABLE `wishlist` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `product_id` int(11) NOT NULL,
                    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `user_product` (`user_id`,`product_id`),
                    KEY `product_id` (`product_id`),
                    KEY `user_id` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

                $this->db->query($sql);
                $this->db->execute();
            }
        } catch (Exception $e) {
            error_log('Error creating wishlist table: ' . $e->getMessage());
        }
    }

    /**
     * Ensure updated_at exists on older installs
     */
    private function ensureUpdatedAtColumn() {
        try {
            $this->db->query("SHOW COLUMNS FROM wishlist LIKE 'updated_at'");
            $col = $this->db->single();
            if (!$col) {
                $this->db->query("
                    ALTER TABLE `wishlist`
                    ADD COLUMN `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    AFTER `created_at`
                ");
                $this->db->execute();
            }
        } catch (Exception $e) {
            // Non-fatal
        }
    }

    /**
     * Ensure unique (user_id, product_id) to prevent duplicates
     */
    private function ensureUniqueUserProductIndex() {
        try {
            $this->db->query("SHOW INDEX FROM wishlist WHERE Key_name = 'user_product'");
            if (!$this->db->single()) {
                // Remove duplicates first (keep lowest id)
                $this->db->query("
                    DELETE w1 FROM wishlist w1
                    INNER JOIN wishlist w2
                      ON w1.user_id = w2.user_id
                     AND w1.product_id = w2.product_id
                     AND w1.id > w2.id
                ");
                $this->db->execute();

                $this->db->query("ALTER TABLE `wishlist` ADD UNIQUE KEY `user_product` (`user_id`,`product_id`)");
                $this->db->execute();
            }
        } catch (Exception $e) {
            // Non-fatal
        }
    }

    /**
     * Remove wishlist rows pointing at deleted / missing products
     */
    public function purgeOrphans($userId = null) {
        try {
            if ($userId !== null) {
                $this->db->query("
                    DELETE w FROM wishlist w
                    LEFT JOIN products p ON p.id = w.product_id
                    WHERE w.user_id = :user_id AND p.id IS NULL
                ");
                $this->db->bind(':user_id', (int)$userId);
            } else {
                $this->db->query("
                    DELETE w FROM wishlist w
                    LEFT JOIN products p ON p.id = w.product_id
                    WHERE p.id IS NULL
                ");
            }
            return (bool)$this->db->execute();
        } catch (Exception $e) {
            error_log('Wishlist purgeOrphans: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Add product to user's wishlist (idempotent)
     */
    public function addToWishlist($userId, $productId) {
        $userId = (int)$userId;
        $productId = (int)$productId;
        if ($userId <= 0 || $productId <= 0) {
            return false;
        }

        if ($this->isInWishlist($userId, $productId)) {
            return true;
        }

        try {
            $this->db->query('INSERT INTO wishlist (user_id, product_id, created_at, updated_at) VALUES (:user_id, :product_id, NOW(), NOW())');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':product_id', $productId);
            return (bool)$this->db->execute();
        } catch (Exception $e) {
            // Duplicate key race — treat as success
            if ($this->isInWishlist($userId, $productId)) {
                return true;
            }
            error_log('Wishlist addToWishlist: ' . $e->getMessage());
            return false;
        }
    }

    /** Alias */
    public function add($userId, $productId) {
        return $this->addToWishlist($userId, $productId);
    }

    /**
     * Remove product from user's wishlist
     */
    public function removeFromWishlist($userId, $productId) {
        $this->db->query('DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', (int)$userId);
        $this->db->bind(':product_id', (int)$productId);
        return (bool)$this->db->execute();
    }

    /** Alias */
    public function remove($userId, $productId) {
        return $this->removeFromWishlist($userId, $productId);
    }

    /**
     * Clear entire wishlist for a user
     */
    public function clear($userId) {
        $this->db->query('DELETE FROM wishlist WHERE user_id = :user_id');
        $this->db->bind(':user_id', (int)$userId);
        return (bool)$this->db->execute();
    }

    /**
     * Check if product is in user's wishlist
     */
    public function isInWishlist($userId, $productId) {
        $this->db->query('SELECT w.id
                          FROM wishlist w
                          INNER JOIN products p ON p.id = w.product_id
                          WHERE w.user_id = :user_id AND w.product_id = :product_id
                          LIMIT 1');
        $this->db->bind(':user_id', (int)$userId);
        $this->db->bind(':product_id', (int)$productId);
        $row = $this->db->single();
        return $row ? true : false;
    }

    /** Alias — compatible with Model::exists($id) */
    public function exists($id, $productId = null) {
        if ($productId !== null) {
            return $this->isInWishlist($id, $productId);
        }
        return parent::exists($id);
    }

    /**
     * Toggle wishlist membership
     *
     * @return array{success:bool,action:string,in_wishlist:bool}
     */
    public function toggle($userId, $productId) {
        if ($this->isInWishlist($userId, $productId)) {
            $ok = $this->removeFromWishlist($userId, $productId);
            return [
                'success' => (bool)$ok,
                'action' => 'removed',
                'in_wishlist' => false
            ];
        }

        $ok = $this->addToWishlist($userId, $productId);
        return [
            'success' => (bool)$ok,
            'action' => 'added',
            'in_wishlist' => true
        ];
    }

    /**
     * Get user's wishlist with product details (only real products)
     */
    public function getUserWishlist($userId) {
        $this->purgeOrphans($userId);

        $this->db->query('SELECT
                             p.id,
                             p.id AS product_id,
                             p.name,
                             p.description,
                             p.price,
                             p.sale_price,
                             p.price2,
                             p.image,
                             p.sku,
                             p.stock_quantity,
                             p.status,
                             w.id AS wishlist_id,
                             w.created_at AS added_date,
                             w.created_at AS created_at
                          FROM wishlist w
                          INNER JOIN products p ON p.id = w.product_id
                          WHERE w.user_id = :user_id
                          ORDER BY w.created_at DESC');
        $this->db->bind(':user_id', (int)$userId);

        $rows = $this->db->resultSet();
        if (!is_array($rows)) {
            return [];
        }

        $out = [];
        foreach ($rows as $row) {
            $r = is_object($row) ? (array)$row : (array)$row;
            $r['id'] = (int)($r['product_id'] ?? $r['id'] ?? 0);
            $r['product_id'] = (int)($r['product_id'] ?? $r['id'] ?? 0);
            $out[] = $r;
        }
        return $out;
    }

    /** Alias */
    public function all($userId) {
        return $this->getUserWishlist($userId);
    }

    /** Alias expected by API naming */
    public function getWishlist($userId) {
        return $this->getUserWishlist($userId);
    }

    /**
     * Get wishlist count for a user (existing products only)
     */
    public function getWishlistCount($userId) {
        $this->purgeOrphans($userId);

        $this->db->query('SELECT COUNT(*) AS count
                          FROM wishlist w
                          INNER JOIN products p ON p.id = w.product_id
                          WHERE w.user_id = :user_id');
        $this->db->bind(':user_id', (int)$userId);

        $row = $this->db->single();
        if (!$row) {
            return 0;
        }
        if (is_object($row)) {
            return (int)$row->count;
        }
        return (int)($row['count'] ?? 0);
    }

    /** Alias — compatible with Model::count() */
    public function count($userId = null) {
        if ($userId !== null) {
            return $this->getWishlistCount($userId);
        }
        return parent::count();
    }

    /**
     * Product IDs currently in the user's wishlist (valid products only)
     *
     * @return int[]
     */
    public function getProductIds($userId) {
        $this->purgeOrphans($userId);

        $this->db->query('SELECT w.product_id
                          FROM wishlist w
                          INNER JOIN products p ON p.id = w.product_id
                          WHERE w.user_id = :user_id');
        $this->db->bind(':user_id', (int)$userId);
        $rows = $this->db->resultSet();
        $ids = [];
        if (is_array($rows)) {
            foreach ($rows as $row) {
                $r = is_object($row) ? (array)$row : (array)$row;
                if (isset($r['product_id'])) {
                    $ids[] = (int)$r['product_id'];
                }
            }
        }
        return array_values(array_unique($ids));
    }
}
