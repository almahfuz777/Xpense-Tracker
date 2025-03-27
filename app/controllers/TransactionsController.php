<!-- app/controllers/TransactionsController.php -->
<?php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/Transaction.php';

class TransactionsController extends Controller {
    // load the homepage view
    public function index() {
        $this -> requireLogin();

        $transactionModel = $this->loadModel('Transaction');
        $userId = $_SESSION['user_id'];

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
            'filterType' => $filterType
        ];

        // Load the homepage view
        $this->loadView('dashboard/transactions', $data);
    }
}

// Instantiate and dispatch the controller
$transactionsController = new TransactionsController();
$transactionsController->dispatch();
