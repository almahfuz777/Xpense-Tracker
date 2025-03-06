<!-- app/controllers/HomeController.php -->
<?php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';

class HomeController extends Controller {
    // load the homepage view
    public function index() {
        $data = [
            'pageTitle' => 'Home | XpenseTracker',
            'page' => 'homepage',
        ];

        // Load the homepage view
        $this->loadView('home', $data);
    }
}

// Instantiate and dispatch the controller
$homeController = new HomeController();
$homeController->dispatch();
