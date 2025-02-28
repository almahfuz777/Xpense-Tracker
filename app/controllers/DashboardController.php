<?php
// app/controllers/DashboardController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';

class DashboardController extends Controller{
    // Default method to load the dashboard view
    public function index(){
        if(!isset($_SESSION['user_id'])){
            // User is not logged in, set message and redirect to login page
            $_SESSION['message'] = "You must log in first.";
            header('Location: ' . BASE_URL . 'app/controllers/LoginController.php');
            // echo "You are not logged in!";
            exit();
        }
        $_SESSION['message'] = "Yousdfsdf";
        // User is logged in, get username
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
        $data = [ 
            'username' => $username,
        ];
        // Load the dashboard view
        $this->loadView('dashboard', $data);
    }
}

// Instantiate the controller and call the dispatcher
$dashboardController = new DashboardController();
$dashboardController->dispatch();
?>