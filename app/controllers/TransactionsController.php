<!-- app/controllers/HomeController.php -->
<?php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';

class TransactionsController extends Controller {
    // load the homepage view
    public function index() {
        $this -> requireLogin();
        $data = [
            'pageTitle' => 'Transactions | Xpense-Tracker',
            'page' => 'transactions',
        ];

        // Load the homepage view
        $this->loadView('dashboard/transactions', $data);
    }
}

// Instantiate and dispatch the controller
$TransactionsController = new TransactionsController();
$TransactionsController->dispatch();
