<?php
class Banner {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Insert new banner
    public function insert($data) {
        $sql = "INSERT INTO banners (title, description, image_url, status) 
                VALUES (:title, :description, :image_url, :status)";
        
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':image_url' => $data['image_url'],
            ':status' => $data['status'] ?? 'active'
        ]);
        
        return $conn->lastInsertId();
    }

    // Update existing banner
    public function update($id, $data) {
        $sql = "UPDATE banners 
                SET title = :title, 
                    description = :description, 
                    image_url = :image_url, 
                    status = :status 
                WHERE id = :id";
        
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':image_url' => $data['image_url'],
            ':status' => $data['status']
        ]);
        
        return $stmt->rowCount();
    }

    // Delete banner
    public function delete($id) {
        $sql = "DELETE FROM banners WHERE id = :id";
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // Get banner by ID
    public function getById($id) {
        $sql = "SELECT * FROM banners WHERE id = :id";
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Get all banners
    public function getAll($status = null) {
        $sql = "SELECT * FROM banners";
        if ($status) {
            $sql .= " WHERE status = :status";
        }
        $sql .= " ORDER BY created_at DESC";
        
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        if ($status) {
            $stmt->execute([':status' => $status]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll();
    }
}
