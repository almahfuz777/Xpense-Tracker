<!-- app/controllers/LogoutController.php -->
<?php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';

class LogoutController extends Controller {
        public function index() {
        // Check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            $_SESSION = [];         // Unset all session variables
            session_destroy();      // Destroy the session

            $_SESSION['message'] = "You have successfully logged out.";
        } 
        else {
            $_SESSION['message'] = "You are not logged in.";
        }
        header('Location: ' . BASE_URL . 'app/controllers/LoginController.php');
        exit();
    }
}

// Instantiate the controller and call the dispatcher
$logoutController = new LogoutController();
$logoutController->dispatch();
?>