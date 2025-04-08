<?php
// app/controllers/LendBorrowController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/LendBorrow.php';

class LendBorrowController extends Controller {
    public function index() {
        $this->requireLogin();
        $model = $this->loadModel('LendBorrow');
        $userId = $_SESSION['user_id'];

        $openItems = $model->getByStatus($userId, false);
        $closedItems = $model->getByStatus($userId, true);

        $data = [
            'pageTitle' => 'Lend/Borrow | Xpense-Tracker',
            'page' => 'lend_borrow',
            'openItems' => $openItems,
            'closedItems' => $closedItems
        ];

        $this->loadView('dashboard/lend_borrow', $data);
    }

    public function store() {
        $this->requireLogin();
        $model = $this->loadModel('LendBorrow');
        $userId = $_SESSION['user_id'];

        $type = $_POST['type'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $amount = floatval($_POST['amount'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        if ($type && $name && $amount > 0) {
            $model->add($userId, $type, $name, $amount, $description);
            $_SESSION['alert-success'] = 'Lend/Borrow entry added.';
        } else {
            $_SESSION['alert-error'] = 'Invalid input.';
        }

        header("Location: LendBorrowController.php");
        exit;
    }

    public function close() {
        $this->requireLogin();
        $model = $this->loadModel('LendBorrow');
        $userId = $_SESSION['user_id'];
        $id = intval($_POST['id']);

        if ($model->markAsClosed($userId, $id)) {
            $_SESSION['alert-success'] = 'Marked as closed.';
        } else {
            $_SESSION['alert-error'] = 'Failed to update entry.';
        }

        header("Location: LendBorrowController.php");
        exit;
    }
}

$controller = new LendBorrowController();
$controller->dispatch();
