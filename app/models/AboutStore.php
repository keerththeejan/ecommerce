<?php
class AboutStore {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create new about entry
    public function insert($data) {
        $sql = "INSERT INTO about_store (title, content, image_path) VALUES (:title, :content, :image_path)";
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':image_path' => $data['image_path'] ?? null
        ]);
        return $conn->lastInsertId();
    }

    // Update about entry
    public function update($id, $data) {
        $sql = "UPDATE about_store SET title = :title, content = :content, image_path = :image_path WHERE id = :id";
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':image_path' => $data['image_path'] ?? null
        ]);
        return $stmt->rowCount();
    }

    // Delete about entry
    public function delete($id) {
        $sql = "DELETE FROM about_store WHERE id = :id";
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // Get single about entry by ID
    public function getById($id) {
        $sql = "SELECT * FROM about_store WHERE id = :id";
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Get all entries
    public function getAll() {
        $sql = "SELECT * FROM about_store ORDER BY created_at DESC";
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
