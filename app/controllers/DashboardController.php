<?php
// app/controllers/DashboardController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';

class DashboardController extends Controller{
    // Default method to load the dashboard view
    public function index(){
        $this -> requireLogin();

        // User is logged in, get username
        $data = [
            'pageTitle' => 'Dashboard | XpenseTracker',
            'page' => 'dashboard',
        ];
        // Load the dashboard view
        $this->loadView('dashboard/dashboard', $data);
    }
}

// Instantiate the controller and call the dispatcher
$dashboardController = new DashboardController();
$dashboardController->dispatch();
?>