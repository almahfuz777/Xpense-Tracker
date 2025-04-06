<!-- app/models/Account.php -->
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

    public function getAccountById($userId, $accountId) {
        $stmt = $this->conn->prepare("SELECT account_name FROM accounts WHERE id = ? AND user_id = ?");
        if (!$stmt) return false;
    
        $stmt->bind_param("ii", $accountId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $account = $result->fetch_assoc();
        $stmt->close();
    
        return $account;
    }    

    public function countAccountsByUser($userId) {
        $count = 0;
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM accounts WHERE user_id = ?");
        if (!$stmt) return 0;
    
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }

    // Add a new account for a user
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
