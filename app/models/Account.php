<?php
require_once APP_PATH . '/core/Database.php';

class Account {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    // Fetch all accounts for a given user
    public function getAllAccounts($userId) {
        $stmt = $this->conn->prepare("SELECT id, account_name, balance FROM accounts WHERE user_id = ?");
        if (!$stmt) return false;

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $accounts = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $accounts;
    }

    public function addAccount($userId, $accountName, $balance) {
        $query = "INSERT INTO accounts (user_id, account_name, balance) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;

        $stmt->bind_param("isd", $userId, $accountName, $balance);
        return $stmt->execute();
    }

    public function deleteAccount($userId, $accountId) {
        $query = "DELETE FROM accounts WHERE user_id = ? AND id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;

        $stmt->bind_param("ii", $userId, $accountId);
        return $stmt->execute();
    }
}
?>
