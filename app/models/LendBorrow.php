<?php
// app/models/LendBorrow.php
require_once APP_PATH . '/core/Database.php';

class LendBorrow {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getByStatus($userId, $closed = false) {
        $stmt = $this->conn->prepare("SELECT * FROM lend_borrow WHERE user_id = ? AND is_closed = ? ORDER BY created_at DESC");
        $closedInt = $closed ? 1 : 0;
        $stmt->bind_param("ii", $userId, $closedInt);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function add($userId, $type, $name, $amount, $description) {
        $stmt = $this->conn->prepare("INSERT INTO lend_borrow (user_id, type, name, amount, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issds", $userId, $type, $name, $amount, $description);
        return $stmt->execute();
    }

    public function markAsClosed($userId, $id) {
        $stmt = $this->conn->prepare("UPDATE lend_borrow SET is_closed = 1, closed_at = NOW() WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }
}
