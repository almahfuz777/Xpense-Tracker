<?php
// app/controllers/AnalyticsController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/Transaction.php';

class AnalyticsController extends Controller {
    public function index() {
        $this->requireLogin();
        $transactionModel = $this->loadModel('Transaction');
        $userId = $_SESSION['user_id'];

        $income = $transactionModel->getTotalAmount($userId, 'month', 'income');
        $expense = $transactionModel->getTotalAmount($userId, 'month', 'expense');
        $categoryExpenses = $transactionModel->getExpenseBreakdown($userId, 'month');
        $cashFlow = $transactionModel->getCashFlowByDate($userId, 30); // last 30 days

        $data = [
            'pageTitle' => 'Analytics | Xpense Tracker',
            'page' => 'analytics',
            'income' => $income,
            'expense' => $expense,
            'categoryExpenses' => $categoryExpenses,
            'cashFlow' => $cashFlow
        ];

        $this->loadView('dashboard/analytics', $data);
    }
}

$controller = new AnalyticsController();
$controller->dispatch();
