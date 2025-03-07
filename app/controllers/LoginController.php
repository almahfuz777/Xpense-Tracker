<?php
// app/controllers/LoginController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/User.php';

class LoginController extends Controller {
    // Default method to load the dashboard view
    public function index() {
        $message = '';
        // Check if there's a message in the session
        if(isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);        // Clear the message after retrieving it
        }
        // Pass the message to the view
        $data = [
            'pageTitle' => 'Login | XpenseTracker',
            'page' => 'authentication',
            'message' => $message,
        ];

        $this->loadView('auth/login', $data);
    }

    public function authenticate() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve form data
            $email = trim($_POST['email'] ?? '');
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = htmlspecialchars($_POST['password'] ?? '');
            
            // Validate form data
            if(empty($email) || empty($password)) {
                $data['message'] = 'Both fields are required.';
                $this->loadView('auth/login', $data);
                return;
            }
            
            $userModel = $this->loadModel('User');
            $user = $userModel->login($email, $password);

            if ($user) {
                // Store user information in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to dashboard
                header('Location: ' . BASE_URL . 'app/controllers/DashboardController.php');
                exit();
            } 
            else {
                $data['message'] = 'Invalid email or password!';
                $this->loadView('auth/login', $data);
            }
        }
        else{
            // If not a POST request, redirect to login form
            header('Location: ' . BASE_URL . 'app/controllers/LoginController.php');
            exit();
        }
    }
}

// Instantiate the controller and call the dispathcer
$loginController = new LoginController();
$loginController->dispatch();
?>
