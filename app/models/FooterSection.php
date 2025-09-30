<?php
namespace App\Models;

class FooterSection {
    private $db;
    private $table = 'footer_sections';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all footer sections
     */
    public function getAllSections() {
        $query = "SELECT * FROM {$this->table} ORDER BY sort_order ASC, created_at DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get active footer sections
     */
    public function getActiveSections() {
        $query = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get footer section by ID
     */
    public function getSectionById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Create a new footer section
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} (title, content, type, status, sort_order) 
                 VALUES (:title, :content, :type, :status, :sort_order)";
        
        $stmt = $this->db->prepare($query);
        
        // Prepare data
        $content = is_array($data['content']) ? json_encode($data['content']) : $data['content'];
        
        $params = [
            ':title' => $data['title'],
            ':content' => $content,
            ':type' => $data['type'],
            ':status' => $data['status'] ?? 'active',
            ':sort_order' => $data['sort_order'] ?? 0
        ];
        
        return $stmt->execute($params) ? $this->db->lastInsertId() : false;
    }

    /**
     * Update a footer section
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} 
                 SET title = :title, 
                     content = :content, 
                     type = :type, 
                     status = :status, 
                     sort_order = :sort_order,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        // Prepare data
        $content = is_array($data['content']) ? json_encode($data['content']) : $data['content'];
        
        $params = [
            ':id' => $id,
            ':title' => $data['title'],
            ':content' => $content,
            ':type' => $data['type'],
            ':status' => $data['status'] ?? 'active',
            ':sort_order' => $data['sort_order'] ?? 0
        ];
        
        return $stmt->execute($params);
    }

    /**
     * Delete a footer section
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get section types with their labels
     */
    public static function getSectionTypes() {
        return [
            'about' => 'About Us',
            'links' => 'Quick Links',
            'contact' => 'Contact Info',
            'social' => 'Social Media'
        ];
    }

    /**
     * Get default fields for section type
     */
    public static function getDefaultFields($type) {
        switch ($type) {
            case 'about':
                return [
                    'title' => 'About Us',
                    'content' => 'Add your about us content here...',
                    'type' => 'about',
                    'status' => 'active',
                    'sort_order' => 0
                ];
            case 'links':
                return [
                    'title' => 'Quick Links',
                    'content' => json_encode([
                        ['text' => 'Home', 'url' => BASE_URL],
                        ['text' => 'Shop', 'url' => BASE_URL . 'products'],
                        ['text' => 'About Us', 'url' => BASE_URL . 'about'],
                        ['text' => 'Contact', 'url' => BASE_URL . 'contact']
                    ]),
                    'type' => 'links',
                    'status' => 'active',
                    'sort_order' => 1
                ];
            case 'contact':
                return [
                    'title' => 'Contact Us',
                    'content' => json_encode([
                        'address' => '123 Main St, City, Country',
                        'phone' => '+1 234 567 890',
                        'email' => 'info@example.com'
                    ]),
                    'type' => 'contact',
                    'status' => 'active',
                    'sort_order' => 2
                ];
            case 'social':
                return [
                    'title' => 'Follow Us',
                    'content' => json_encode([
                        'facebook' => 'https://facebook.com',
                        'twitter' => 'https://twitter.com',
                        'instagram' => 'https://instagram.com',
                        'youtube' => 'https://youtube.com',
                        'linkedin' => 'https://linkedin.com'
                    ]),
                    'type' => 'social',
                    'status' => 'active',
                    'sort_order' => 3
                ];
            default:
                return [
                    'title' => 'New Section',
                    'content' => '',
                    'type' => $type,
                    'status' => 'active',
                    'sort_order' => 99
                ];
        }
    }
}
