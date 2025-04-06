<!-- app/controllers/AccountsController.php -->
<?php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/Account.php';

class AccountsController extends Controller {
    public function index() {
        $this->requireLogin();

        $accountModel = $this->loadModel('Account');
        $userId = $_SESSION['user_id'];

        // Fetch all user accounts
        $accounts = $accountModel->getAllAccounts($userId);

        $data = [
            'pageTitle' => 'Manage Accounts | Xpense-Tracker',
            'page' => 'accounts',
            'accounts' => $accounts
        ];

        $this->loadView('dashboard/accounts', $data);
    }

    public function store() {
        $this->requireLogin();
        $accountModel = $this->loadModel('Account');
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountName = trim($_POST['account_name']);
            $balance = floatval($_POST['initial_balance']);

            if (empty($accountName) || $balance < 0) {
                $_SESSION['message'] = "Invalid account details.";
            }
            elseif ($accountModel->countAccountsByUser($userId) >= 4){
                $_SESSION['message'] = "You can only have a maximum of 4 accounts.";
            } else {
                $accountModel->addAccount($userId, $accountName, $balance);
                $_SESSION['message'] = "Account added successfully!";
            }

            header('Location: ' . BASE_URL . 'app/controllers/AccountsController.php');
            exit();
        }
    }

    public function delete() {
        $this->requireLogin();
        $accountModel = $this->loadModel('Account');
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountId = intval($_POST['account_id']);
            
            if ($accountId > 0) {
                $account = $accountModel->getAccountById($userId, $accountId);

                if ($account && strtolower($account['account_name']) === 'cash') {
                    $_SESSION['message'] = "Cannot delete the default 'Cash' account.";
                }
                else{
                    $accountModel->deleteAccount($userId, $accountId);
                    $_SESSION['message'] = "Account deleted successfully!";
                }
            } else {
                $_SESSION['message'] = "Invalid account.";
            }

            header('Location: ' . BASE_URL . 'app/controllers/AccountsController.php');
            exit();
        }
    }
}

// Instantiate and dispatch the controller
$accountsController = new AccountsController();
$accountsController->dispatch();
