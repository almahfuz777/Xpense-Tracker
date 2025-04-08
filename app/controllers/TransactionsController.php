<?php
// app/controllers/TransactionsController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/Transaction.php';

class TransactionsController extends Controller {
    // load the homepage view
    public function index() {
        $this -> requireLogin();

        $transactionModel = $this->loadModel('Transaction');
        $categoryModel = $this->loadModel('Category');

        $userId = $_SESSION['user_id'];
        $categories = $categoryModel->getAllCategories($userId);

        // Check if a filter is applied
        $filterType = $_GET['type'] ?? 'all';

        if ($filterType === 'all') {
            $transactions = $transactionModel->getAllTransactions($userId);
        } else {
            $transactions = $transactionModel->getTransactionsByType($userId, $filterType);
        }

        $data = [
            'pageTitle' => 'Transactions | Xpense-Tracker',
            'page' => 'transactions',
            'transactions' => $transactions,
            'filterType' => $filterType,
            'categories' => $categories,
        ];

        // Load the homepage view
        $this->loadView('dashboard/transactions', $data);
    }

    public function fetch() {
        $this->requireLogin();
        $transactionModel = $this->loadModel('Transaction');
        $userId = $_SESSION['user_id'];
    
        $type = $_POST['type'] ?? 'all';
        $search = $_POST['search'] ?? '';
        $category = $_POST['category'] ?? '';
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $minAmount = $_POST['minAmount'] ?? '';
        $maxAmount = $_POST['maxAmount'] ?? '';
    
        $transactions = $transactionModel->getFilteredTransactions($userId, $type, $search, $category, $startDate, $endDate, $minAmount, $maxAmount);
    
        header('Content-Type: application/json');
        echo json_encode($transactions);
        exit;
    }
    
}

// Instantiate and dispatch the controller
$transactionsController = new TransactionsController();
$transactionsController->dispatch();
