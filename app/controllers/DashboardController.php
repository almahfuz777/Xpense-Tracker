<?php
// app/controllers/DashboardController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/Transaction.php';
require_once MODEL_PATH . '/Account.php';

class DashboardController extends Controller{
    // Default method to load the dashboard view
    public function index(){
        $this -> requireLogin();

        $transactionModel = $this->loadModel('Transaction');
        $accountModel = $this->loadModel('Account');

        $userId = $_SESSION['user_id'];
        
        // Fetch account balances
        $accounts = $accountModel->getAllAccounts($userId);
        $noOfAccounts = $accountModel->countAccountsByUser($userId);
        $totalBalance = $accounts ? array_sum(array_column($accounts, 'balance')) : 0;
        
        // Fetch cash-flow
        $period = $_GET['period'] ?? 'month'; // week, month, or year
        $income = $transactionModel->getTotalAmount($userId, $period, 'income');
        $expenses = $transactionModel->getTotalAmount($userId, $period, 'expense');

        // Fetch recent transactions (limit 3)
        $recentTransactions = $transactionModel->getRecentTransactions($userId, 3);

        $data = [
            'pageTitle' => 'Dashboard | XpenseTracker',
            'page' => 'dashboard',
            'totalBalance' => $totalBalance,
            'accounts' => $accounts,
            'noOfAccounts' => $noOfAccounts,
            'income' => $income,
            'expenses' => $expenses,
            'recentTransactions' => $recentTransactions,
            'selectedPeriod' => $period,
        ];
        
        // Load the dashboard view
        $this->loadView('dashboard/dashboard', $data);
    }

    public function getCashFlow(){
        $this->requireLogin();

        $transactionModel = $this->loadModel('Transaction');
        $userId = $_SESSION['user_id'];

        $period = $_POST['period'] ?? 'month';

        $income = $transactionModel->getTotalAmount($userId, $period, 'income');
        $expense = $transactionModel->getTotalAmount($userId, $period, 'expense');

        header('Content-Type: application/json');
        echo json_encode([
            'income' => $income,
            'expense' => $expense
        ]);
        exit;    
    }

    public function getExpenseBreakdown() {
        $this->requireLogin();
    
        $transactionModel = $this->loadModel('Transaction');
        $userId = $_SESSION['user_id'];
        $period = $_POST['period'] ?? 'month';
    
        $data = $transactionModel->getExpenseBreakdown($userId, $period);
    
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
}

// Instantiate the controller and call the dispatcher
$dashboardController = new DashboardController();
$dashboardController->dispatch();
?>