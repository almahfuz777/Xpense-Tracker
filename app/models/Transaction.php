<?php
// app/models/Transaction.php
require_once APP_PATH . '/core/Database.php';

class Transaction {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    // Add income transaction
    public function addIncome($userId, $accountId, $categoryId, $description, $amount, $transactionDateTime, $createdAt) {
        if (!$this->isValidTransaction($userId, $accountId, $categoryId, $amount)) {
            return ['success' => false, 'error' => 'Invalid transaction data'];
        }

        $transactionDateTime = $this->formatDateTime($transactionDateTime);

        $query = "INSERT INTO income (user_id, account_id, category_id, description, amount, transaction_time, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $executed = $this->executeTransactionQuery(
            $query, "iiisdss", $userId, $accountId, $categoryId, $description, $amount, $transactionDateTime, $createdAt
        );

        if ($executed && $this->addToAccount($accountId, $amount)) {
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'Transaction failed'];
    }

    // Add expense transaction
    public function addExpense($userId, $accountId, $categoryId, $description, $amount, $transactionDateTime, $createdAt) {
        if (!$this->isValidTransaction($userId, $accountId, $categoryId, $amount)) {
            return ['success' => false, 'error' => 'Invalid transaction data'];
        }

        $balance = $this->getAccountBalance($accountId);
        if ($balance === false || $balance < $amount) {
            return ['success' => false, 'error' => 'Insufficient balance'];
        }

        $transactionDateTime = $this->formatDateTime($transactionDateTime);

        $query = "INSERT INTO expense (user_id, account_id, category_id, description, amount, transaction_time, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $executed = $this->executeTransactionQuery(
            $query, "iiisdss", $userId, $accountId, $categoryId, $description, $amount, $transactionDateTime, $createdAt
        );
        if ($executed && $this->deductFromAccount($accountId, $amount)) {
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'Transaction failed'];
    }

    // Add transfer transaction
    public function addTransfer($userId, $fromAccountId, $toAccountId, $description, $amount, $transactionDateTime, $createdAt) {
        if (!$this->isValidTransaction($userId, $fromAccountId, $toAccountId, $amount)) {
            return ['success' => false, 'error' => 'Invalid transfer data'];
        }

        if ($fromAccountId === $toAccountId) {
            return ['success' => false, 'error' => 'Cannot transfer to the same account'];
        }

        $balance = $this->getAccountBalance($fromAccountId);
        if ($balance === false || $balance < $amount) {
            return ['success' => false, 'error' => 'Insufficient balance'];
        }

        $transactionDateTime = $this->formatDateTime($transactionDateTime);

        $query = "INSERT INTO transfer (user_id, from_account_id, to_account_id, description, amount, transaction_time, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $executed = $this->executeTransactionQuery(
            $query, "iiisdss", $userId, $fromAccountId, $toAccountId, $description, $amount, $transactionDateTime, $createdAt
        );

        if ($executed && 
            $this->deductFromAccount($fromAccountId, $amount) && 
            $this->addToAccount($toAccountId, $amount)
        ){
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'Transfer failed'];
    }

    // Validate transaction data
    private function isValidTransaction($userId, $accountId, $categoryId, $amount) {
        return !empty($userId) && !empty($accountId) && ($categoryId !== null || $categoryId === 0) && $amount > 0;
    }

    // Common method to execute transaction insert queries
    private function executeTransactionQuery($query, $types, ...$params) {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("SQL Prepare Error: " . $this->conn->error);
            return false;
        }

        if (count($params) !== strlen($types)) {
            error_log("Error: Mismatched parameters in bind_param.");
            return false;
        }

        $stmt->bind_param($types, ...$params);
        $success = $stmt->execute();
        if (!$success) {
            error_log("SQL Execution Error: " . $stmt->error);
        }

        $stmt->close();
        return $success;
    }

    private function formatDateTime($datetime) {
        return date('Y-m-d H:i:s', strtotime($datetime));
    }

    // Add balance to an account
    private function addToAccount($accountId, $amount) {
        $stmt = $this->conn->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("di", $amount, $accountId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Deduct balance from an account
    private function deductFromAccount($accountId, $amount) {
        $stmt = $this->conn->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("di", $amount, $accountId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Fetch current balance of an account
    public function getAccountBalance($accountId) {
        $stmt = $this->conn->prepare("SELECT balance FROM accounts WHERE id = ?");
        if (!$stmt) return false;

        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $balance = 0;
        $stmt->bind_result($balance);
        $stmt->fetch();
        $stmt->close();

        return $balance;
    }

    // Get total income or expense for a user
    public function getTotalAmount($userId, $period, $type) {
        // Ensure valid table selection
        $table = ($type === 'income') ? 'income' : 'expense';
    
        // Set the date filter based on the period
        $dateFilter = "AND transaction_time >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";  // Default: last 30 days
        if ($period === 'week') {
            $dateFilter = "AND transaction_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        } elseif ($period === 'year') {
            $dateFilter = "AND transaction_time >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        }
        
        // SQL query to get total income or expense
        $query = "SELECT COALESCE(SUM(amount), 0) AS total FROM $table WHERE user_id = ? $dateFilter";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("SQL Error: " . $this->conn->error);
            return 0;
        }
        
        $totalAmount = null;
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($totalAmount);
        $stmt->fetch();
        $stmt->close();
    
        return ($totalAmount !== null) ? $totalAmount : 0;
    }
    
    // Fetch transactions by type (income, expense, transfer)
    public function getTransactionsByType($userId, $type) {
        $transactions = [];

        if ($type === 'income') {
            $query = "
                SELECT 
                    i.id, 'income' AS transaction_type, i.transaction_time, i.amount, 
                    i.description, a.account_name, c.category_name
                FROM income i
                JOIN accounts a ON i.account_id = a.id
                JOIN categories c ON i.category_id = c.id
                WHERE i.user_id = ?
            ";
        } 
        elseif ($type === 'expense') {
            $query = "
                SELECT 
                    e.id, 'expense' AS transaction_type, e.transaction_time, e.amount, 
                    e.description, a.account_name, c.category_name
                FROM expense e
                JOIN accounts a ON e.account_id = a.id
                JOIN categories c ON e.category_id = c.id
                WHERE e.user_id = ?
            ";
        } 
        elseif ($type === 'transfer') {
            $query = "
                SELECT 
                    t.id, 'transfer' AS transaction_type, t.transaction_time, t.amount, 
                    t.description, a1.account_name AS from_account, a2.account_name AS to_account
                FROM transfer t
                JOIN accounts a1 ON t.from_account_id = a1.id
                JOIN accounts a2 ON t.to_account_id = a2.id
                WHERE t.user_id = ?
            ";
        } 
        else {
            return $transactions;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }

        $stmt->close();
        return $transactions;
    }

    // Fetch all transactions for a user
    public function getAllTransactions($userId) {
        $transactions = array_merge(
            $this->getTransactionsByType($userId, 'income'),
            $this->getTransactionsByType($userId, 'expense'),
            $this->getTransactionsByType($userId, 'transfer')
        );
        
        // Sort transactions by transaction_time (latest first)
        usort($transactions, function ($a, $b) {
            return strtotime($b['transaction_time']) - strtotime($a['transaction_time']);
        });

        return $transactions;
    }

    // Fetch recent transactions for a user
    public function getRecentTransactions($userId, $limit) {
        $transactions = [];
    
        $query = "
            (SELECT i.id, 'income' AS transaction_type, i.transaction_time, i.amount, 
                    i.description, a.account_name, c.category_name, NULL AS from_account, NULL AS to_account
            FROM income i
            JOIN accounts a ON i.account_id = a.id
            JOIN categories c ON i.category_id = c.id
            WHERE i.user_id = ?
            ORDER BY i.transaction_time DESC
            LIMIT ?)
    
            UNION ALL
    
            (SELECT e.id, 'expense' AS transaction_type, e.transaction_time, e.amount, 
                    e.description, a.account_name, c.category_name, NULL AS from_account, NULL AS to_account
            FROM expense e
            JOIN accounts a ON e.account_id = a.id
            JOIN categories c ON e.category_id = c.id
            WHERE e.user_id = ?
            ORDER BY e.transaction_time DESC
            LIMIT ?)
    
            UNION ALL
    
            (SELECT t.id, 'transfer' AS transaction_type, t.transaction_time, t.amount, 
                    t.description, NULL AS account_name, NULL AS category_name, 
                    a1.account_name AS from_account, a2.account_name AS to_account
            FROM transfer t
            JOIN accounts a1 ON t.from_account_id = a1.id
            JOIN accounts a2 ON t.to_account_id = a2.id
            WHERE t.user_id = ?
            ORDER BY t.transaction_time DESC
            LIMIT ?)
    
            ORDER BY transaction_time DESC
            LIMIT ?
        ";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("SQL Prepare Error: " . $this->conn->error);
            return [];
        }
    
        $stmt->bind_param("iiiiiii", $userId, $limit, $userId, $limit, $userId, $limit, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
    
        $stmt->close();
        return $transactions;
    }

    public function getExpenseBreakdown($userId, $period = 'month') {
        $dateFilter = "AND e.transaction_time >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        if ($period === 'week') {
            $dateFilter = "AND e.transaction_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        } elseif ($period === 'year') {
            $dateFilter = "AND e.transaction_time >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        }
    
        $query = "
            SELECT c.category_name, SUM(e.amount) as total
            FROM expense e
            JOIN categories c ON e.category_id = c.id
            WHERE e.user_id = ? $dateFilter
            GROUP BY c.category_name
            ORDER BY total DESC
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $breakdown = [];
        while ($row = $result->fetch_assoc()) {
            $breakdown[] = $row;
        }
        return $breakdown;
    }    

    public function getFilteredTransactions($userId, $type, $search, $category, $startDate, $endDate, $minAmount, $maxAmount) {
        $search = '%' . $this->conn->real_escape_string($search) . '%';
        $transactions = [];
        $queryParts = [];

        // Date range filter
        $dateFilter = "";
        if (!empty($startDate)) {
            $dateFilter .= " AND transaction_time >= '" . $this->conn->real_escape_string($startDate) . "'";
        }
        if (!empty($endDate)) {
            $dateFilter .= " AND transaction_time <= '" . $this->conn->real_escape_string($endDate) . "'";
        }

        // Amount range filter
        $amountFilter = "";
        if ($minAmount !== '') {
            $amountFilter .= " AND amount >= " . floatval($minAmount);
        }
        if ($maxAmount !== '') {
            $amountFilter .= " AND amount <= " . floatval($maxAmount);
        }

        // Category filter
        $categoryFilter = $category !== '' ? " AND category_id = " . intval($category) : "";

        // Income
        if ($type === 'income' || $type === 'all') {
            $queryParts[] = "
                SELECT i.id, 'income' AS transaction_type, i.transaction_time AS transaction_time, i.amount,
                    i.description, a.account_name, c.category_name, NULL AS from_account, NULL AS to_account
                FROM income i
                JOIN accounts a ON i.account_id = a.id
                JOIN categories c ON i.category_id = c.id
                WHERE i.user_id = $userId
                AND i.description LIKE '$search'
                $categoryFilter
                $dateFilter
                $amountFilter
            ";
        }
    
        // Expense
        if ($type === 'expense' || $type === 'all') {
            $queryParts[] = "
                SELECT e.id, 'expense' AS transaction_type, e.transaction_time AS transaction_time, e.amount,
                    e.description, a.account_name, c.category_name, NULL AS from_account, NULL AS to_account
                FROM expense e
                JOIN accounts a ON e.account_id = a.id
                JOIN categories c ON e.category_id = c.id
                WHERE e.user_id = $userId
                AND e.description LIKE '$search'
                $categoryFilter
                $dateFilter
                $amountFilter
            ";
        }
    
        // Transfer
        if (($type === 'transfer' || $type === 'all') && $category === '') {
            $queryParts[] = "
                SELECT t.id, 'transfer' AS transaction_type, t.transaction_time AS transaction_time, t.amount,
                    t.description, NULL AS account_name, NULL AS category_name,
                    a1.account_name AS from_account, a2.account_name AS to_account
                FROM transfer t
                JOIN accounts a1 ON t.from_account_id = a1.id
                JOIN accounts a2 ON t.to_account_id = a2.id
                WHERE t.user_id = $userId
                AND t.description LIKE '$search'
                $dateFilter
                $amountFilter
            ";
        }
    
        $finalQuery = implode(" UNION ALL ", $queryParts) . " ORDER BY transaction_time DESC";
    
        $result = $this->conn->query($finalQuery);
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
    
        return $transactions;
    }

    public function getCashFlowByDate($userId, $days = 30) {
        $query = "
            SELECT DATE(transaction_time) AS date, SUM(amount) AS amount, 'income' AS type
            FROM income
            WHERE user_id = ? AND transaction_time >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(transaction_time)
            UNION ALL
            SELECT DATE(transaction_time), SUM(amount), 'expense'
            FROM expense
            WHERE user_id = ? AND transaction_time >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(transaction_time)
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiii", $userId, $days, $userId, $days);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $flow = [];
        while ($row = $result->fetch_assoc()) {
            $flow[] = $row;
        }
    
        return $flow;
    }
    
    
}
?>
