<?php
// app/models/category.php
require_once APP_PATH . '/core/Database.php';

class Category {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getCategoriesByType($userId, $type) {
        $query = "SELECT id, category_name FROM categories WHERE (user_id = ? OR created_by = 'default') AND type = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return [];

        $stmt->bind_param("is", $userId, $type);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllCategories($userId) {
        $query = "SELECT id, category_name FROM categories WHERE user_id = ? OR created_by = 'default' ORDER BY category_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        return $categories;
    }
}
?>
