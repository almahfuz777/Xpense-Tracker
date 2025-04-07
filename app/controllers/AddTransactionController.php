<!-- app/controllers/AddTransactionController.php -->
<?php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/Transaction.php';
require_once MODEL_PATH . '/Category.php';
require_once MODEL_PATH . '/Account.php';

class AddTransactionController extends Controller {
    public function index() {
        $this -> requireLogin();

        $categoryModel = $this->loadModel('Category');
        $accountModel = $this->loadModel('Account');

        $userId = $_SESSION['user_id'];

        // Fetch categories
        $incomeCategories = $categoryModel->getCategoriesByType($userId, 'income');
        $expenseCategories = $categoryModel->getCategoriesByType($userId, 'expense');

        // Fetch accounts
        $accounts = $accountModel->getAllAccounts($userId);

        $data = [
            'pageTitle' => 'Add Transaction | Xpense-Tracker',
            'page' => 'add_transaction',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'accounts' => $accounts,
        ];

        // Load the homepage view
        $this->loadView('dashboard/add_transaction', $data);
    }

    public function store(){
        $this -> requireLogin();
        $transactionModel = $this->loadModel('Transaction');
        $userId = $_SESSION['user_id'];

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $type = $_POST['transaction_type'] ?? '';
            
            // Common fields
            $description = trim($_POST['description'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $transactionDateTime = $_POST['datetime'] ?? date('Y-m-d H:i:s');
            $createdAt = date('Y-m-d H:i:s');
            $result = ['success' => false, 'error' => 'Invalid transaction type.'];

            if ($type === 'income' || $type === 'expense') {
                $categoryId = intval($_POST['category_id'] ?? 0);
                $accountId = intval($_POST['account_id'] ?? 0);
                if ($categoryId > 0 && $accountId > 0) {
                    $result  = ($type === 'income') 
                        ? $transactionModel->addIncome($userId, $accountId, $categoryId, $description, $amount, $transactionDateTime, $createdAt) 
                        : $transactionModel->addExpense($userId, $accountId, $categoryId, $description, $amount, $transactionDateTime, $createdAt);
                } else {
                    $result = ['success' => false, 'error' => 'Missing category or account.'];
                }
            } 
            elseif ($type === 'transfer') {
                $fromAccountId = intval($_POST['from_account_id'] ?? 0);
                $toAccountId = intval($_POST['to_account_id'] ?? 0);
                
                if ($fromAccountId > 0 && $toAccountId > 0 && $fromAccountId !== $toAccountId) {
                    $result = $transactionModel->addTransfer($userId, $fromAccountId, $toAccountId, $description, $amount, $transactionDateTime, $createdAt);
                } else {
                    $result = ['success' => false, 'error' => 'Missing transfer account information.'];
                }
            }
            else {
                $result = ['success' => false, 'error' => 'Invalid transaction type.'];
            }

            if ($result['success']) {
                $_SESSION['alert-success'] = 'Transaction added successfully!';
                header('Location: ' . BASE_URL . 'app/controllers/TransactionsController.php');

            } else{
                $_SESSION['alert-error'] = $result['error'] ?? 'Transaction failed.';
                header('Location: ' . BASE_URL . 'app/controllers/AddTransactionController.php');
            }
            
            exit();
        }
    }
}

// Instantiate and dispatch the controller
$addTransactionController = new AddTransactionController();
$addTransactionController->dispatch();
?>