<?php
/**
 * Banner Model 2026
 * Handles all banner database operations
 */
class Banner2026 {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Get all banners with sorting
     */
    public function getAllBanners() {
        try {
            $this->db->query("
                SELECT b.*, 
                       (SELECT COUNT(*) FROM banner_analytics WHERE banner_id = b.id AND event_type = 'impression') as impressions,
                       (SELECT COUNT(*) FROM banner_analytics WHERE banner_id = b.id AND event_type = 'click') as clicks
                FROM banners b 
                ORDER BY b.sort_order ASC, b.created_at DESC
            ");
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Get all banners error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get banner by ID
     */
    public function getBannerById($id) {
        try {
            $this->db->query("
                SELECT b.*, 
                       (SELECT COUNT(*) FROM banner_analytics WHERE banner_id = b.id AND event_type = 'impression') as impressions,
                       (SELECT COUNT(*) FROM banner_analytics WHERE banner_id = b.id AND event_type = 'click') as clicks
                FROM banners b 
                WHERE b.id = :id
            ");
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Get banner by ID error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get hero banners
     */
    public function getHeroBanners() {
        try {
            $this->db->query("
                SELECT * FROM banners 
                WHERE type = 'hero' AND status = 'active' 
                ORDER BY sort_order ASC
            ");
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Get hero banners error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get mobile banners
     */
    public function getMobileBanners() {
        try {
            $this->db->query("
                SELECT * FROM banners 
                WHERE type = 'mobile' AND status = 'active' 
                ORDER BY sort_order ASC
            ");
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Get mobile banners error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get promotional banners
     */
    public function getPromotionalBanners() {
        try {
            $this->db->query("
                SELECT * FROM banners 
                WHERE type = 'promotional' AND status = 'active' 
                ORDER BY sort_order ASC
            ");
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Get promotional banners error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get campaign banners
     */
    public function getCampaignBanners() {
        try {
            $this->db->query("
                SELECT * FROM banners 
                WHERE type = 'campaign' AND status = 'active' 
                ORDER BY sort_order ASC
            ");
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Get campaign banners error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create new banner
     */
    public function create($data) {
        try {
            $this->db->query("
                INSERT INTO banners (title, description, image, cta_text, cta_link, start_date, end_date, 
                                  status, type, sort_order, created_at, updated_at) 
                VALUES (:title, :description, :image, :cta_text, :cta_link, :start_date, :end_date, 
                        :status, :type, :sort_order, :created_at, :updated_at)
            ");
            
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':image', $data['image']);
            $this->db->bind(':cta_text', $data['cta_text']);
            $this->db->bind(':cta_link', $data['cta_link']);
            $this->db->bind(':start_date', $data['start_date']);
            $this->db->bind(':end_date', $data['end_date']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':type', $data['type']);
            $this->db->bind(':sort_order', $data['sort_order']);
            $this->db->bind(':created_at', $data['created_at']);
            $this->db->bind(':updated_at', $data['updated_at']);
            
            $this->db->execute();
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            error_log('Create banner error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update banner
     */
    public function update($id, $data) {
        try {
            // Build dynamic query
            $fields = [];
            foreach ($data as $key => $value) {
                if ($key !== 'id') {
                    $fields[] = "$key = :$key";
                }
            }
            
            $sql = "UPDATE banners SET " . implode(', ', $fields) . " WHERE id = :id";
            $this->db->query($sql);
            
            // Bind all values
            foreach ($data as $key => $value) {
                $this->db->bind(":$key", $value);
            }
            $this->db->bind(':id', $id);
            
            return $this->db->execute();
            
        } catch (Exception $e) {
            error_log('Update banner error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete banner
     */
    public function delete($id) {
        try {
            $this->db->query("DELETE FROM banners WHERE id = :id");
            $this->db->bind(':id', $id);
            return $this->db->execute();
            
        } catch (Exception $e) {
            error_log('Delete banner error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get banner settings
     */
    public function getSettings() {
        try {
            $this->db->query("SELECT * FROM banner_settings WHERE id = 1");
            $settings = $this->db->single();
            
            if (!$settings) {
                // Create default settings
                $this->createDefaultSettings();
                return $this->getDefaultSettings();
            }
            
            return $settings;
            
        } catch (Exception $e) {
            error_log('Get settings error: ' . $e->getMessage());
            return $this->getDefaultSettings();
        }
    }
    
    /**
     * Update banner settings
     */
    public function updateSettings($data) {
        try {
            // Check if settings exist
            $this->db->query("SELECT COUNT(*) as count FROM banner_settings WHERE id = 1");
            $count = $this->db->single();
            
            if ($count['count'] > 0) {
                // Update existing settings
                $fields = [];
                foreach ($data as $key => $value) {
                    $fields[] = "$key = :$key";
                }
                
                $sql = "UPDATE banner_settings SET " . implode(', ', $fields) . " WHERE id = 1";
                $this->db->query($sql);
                
                foreach ($data as $key => $value) {
                    $this->db->bind(":$key", $value);
                }
                
                return $this->db->execute();
            } else {
                // Insert new settings
                $data['id'] = 1;
                return $this->createSettings($data);
            }
            
        } catch (Exception $e) {
            error_log('Update settings error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create banner settings
     */
    private function createSettings($data) {
        try {
            $fields = array_keys($data);
            $placeholders = array_map(function($field) { return ":$field"; }, $fields);
            
            $sql = "INSERT INTO banner_settings (" . implode(', ', $fields) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";
            
            $this->db->query($sql);
            
            foreach ($data as $key => $value) {
                $this->db->bind(":$key", $value);
            }
            
            return $this->db->execute();
            
        } catch (Exception $e) {
            error_log('Create settings error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create default settings
     */
    private function createDefaultSettings() {
        $defaultSettings = $this->getDefaultSettings();
        $defaultSettings['id'] = 1;
        $defaultSettings['created_at'] = date('Y-m-d H:i:s');
        return $this->createSettings($defaultSettings);
    }
    
    /**
     * Get default settings
     */
    private function getDefaultSettings() {
        return [
            'auto_slide' => 1,
            'slide_interval' => 5000,
            'transition_effect' => 'fade',
            'show_arrows' => 1,
            'show_dots' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Track banner impression
     */
    public function trackImpression($bannerId) {
        try {
            $this->db->query("
                INSERT INTO banner_analytics (banner_id, event_type, ip_address, user_agent, created_at) 
                VALUES (:banner_id, 'impression', :ip_address, :user_agent, :created_at)
            ");
            
            $this->db->bind(':banner_id', $bannerId);
            $this->db->bind(':ip_address', $_SERVER['REMOTE_ADDR'] ?? '');
            $this->db->bind(':user_agent', $_SERVER['HTTP_USER_AGENT'] ?? '');
            $this->db->bind(':created_at', date('Y-m-d H:i:s'));
            
            return $this->db->execute();
            
        } catch (Exception $e) {
            error_log('Track impression error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Track banner click
     */
    public function trackClick($bannerId) {
        try {
            $this->db->query("
                INSERT INTO banner_analytics (banner_id, event_type, ip_address, user_agent, created_at) 
                VALUES (:banner_id, 'click', :ip_address, :user_agent, :created_at)
            ");
            
            $this->db->bind(':banner_id', $bannerId);
            $this->db->bind(':ip_address', $_SERVER['REMOTE_ADDR'] ?? '');
            $this->db->bind(':user_agent', $_SERVER['HTTP_USER_AGENT'] ?? '');
            $this->db->bind(':created_at', date('Y-m-d H:i:s'));
            
            return $this->db->execute();
            
        } catch (Exception $e) {
            error_log('Track click error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get banner analytics
     */
    public function getAnalytics($bannerId = null, $dateRange = '7days') {
        try {
            // Calculate date range
            $interval = match($dateRange) {
                '1day' => '1 DAY',
                '7days' => '7 DAY',
                '30days' => '30 DAY',
                '90days' => '90 DAY',
                default => '7 DAY'
            };
            
            $sql = "
                SELECT 
                    DATE(created_at) as date,
                    event_type,
                    COUNT(*) as count
                FROM banner_analytics 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL $interval)
            ";
            
            if ($bannerId) {
                $sql .= " AND banner_id = :banner_id";
            }
            
            $sql .= " GROUP BY DATE(created_at), event_type ORDER BY date ASC";
            
            $this->db->query($sql);
            
            if ($bannerId) {
                $this->db->bind(':banner_id', $bannerId);
            }
            
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            error_log('Get analytics error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get active banners for frontend
     */
    public function getActiveBanners($type = null, $limit = null) {
        try {
            $sql = "
                SELECT * FROM banners 
                WHERE status = 'active' 
                AND (start_date IS NULL OR start_date <= NOW()) 
                AND (end_date IS NULL OR end_date >= NOW())
            ";
            
            if ($type) {
                $sql .= " AND type = :type";
            }
            
            $sql .= " ORDER BY sort_order ASC";
            
            if ($limit) {
                $sql .= " LIMIT :limit";
            }
            
            $this->db->query($sql);
            
            if ($type) {
                $this->db->bind(':type', $type);
            }
            
            if ($limit) {
                $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            }
            
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            error_log('Get active banners error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get banner statistics
     */
    public function getStatistics() {
        try {
            $stats = [];
            
            // Total banners
            $this->db->query("SELECT COUNT(*) as total FROM banners");
            $stats['total_banners'] = $this->db->single()['total'];
            
            // Active banners
            $this->db->query("SELECT COUNT(*) as active FROM banners WHERE status = 'active'");
            $stats['active_banners'] = $this->db->single()['active'];
            
            // Total impressions
            $this->db->query("SELECT COUNT(*) as impressions FROM banner_analytics WHERE event_type = 'impression'");
            $stats['total_impressions'] = $this->db->single()['impressions'];
            
            // Total clicks
            $this->db->query("SELECT COUNT(*) as clicks FROM banner_analytics WHERE event_type = 'click'");
            $stats['total_clicks'] = $this->db->single()['clicks'];
            
            // CTR
            if ($stats['total_impressions'] > 0) {
                $stats['ctr'] = round(($stats['total_clicks'] / $stats['total_impressions']) * 100, 2);
            } else {
                $stats['ctr'] = 0;
            }
            
            return $stats;
            
        } catch (Exception $e) {
            error_log('Get statistics error: ' . $e->getMessage());
            return [
                'total_banners' => 0,
                'active_banners' => 0,
                'total_impressions' => 0,
                'total_clicks' => 0,
                'ctr' => 0
            ];
        }
    }
    
    /**
     * Search banners
     */
    public function searchBanners($query, $type = null, $status = null) {
        try {
            $sql = "
                SELECT b.*, 
                       (SELECT COUNT(*) FROM banner_analytics WHERE banner_id = b.id AND event_type = 'impression') as impressions,
                       (SELECT COUNT(*) FROM banner_analytics WHERE banner_id = b.id AND event_type = 'click') as clicks
                FROM banners b 
                WHERE (title LIKE :query OR description LIKE :query)
            ";
            
            if ($type) {
                $sql .= " AND type = :type";
            }
            
            if ($status) {
                $sql .= " AND status = :status";
            }
            
            $sql .= " ORDER BY b.sort_order ASC, b.created_at DESC";
            
            $this->db->query($sql);
            $this->db->bind(':query', '%' . $query . '%');
            
            if ($type) {
                $this->db->bind(':type', $type);
            }
            
            if ($status) {
                $this->db->bind(':status', $status);
            }
            
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            error_log('Search banners error: ' . $e->getMessage());
            return [];
        }
    }
}
