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
            'errors' => $_SESSION['errors'] ?? [],
        ];
        unset($_SESSION['errors']);
        $this->loadView('auth/login', $data);
    }

    public function authenticate() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve form data
            $email = trim($_POST['email'] ?? '');
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = htmlspecialchars($_POST['password'] ?? '');
            $remember = isset($_POST['remember']);
            
            // Validate form data
            $errors = [];
            if(empty($email) || empty($password)) {
                $_SESSION['errors'] = ['Both fields are required.'];
                header('Location: ' . BASE_URL . 'app/controllers/LoginController.php');
                exit();
            }
            
            $userModel = $this->loadModel('User');
            $user = $userModel->login($email, $password);

            if ($user) {
                // Store user information in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                if($remember){
                    setcookie("remember_me", $user['id'], [
                        'expires' => time() + (30 * 24 * 60 * 60), // 30 days
                        'path' => '/',  
                        'httponly' => true,
                        'samesite' => 'Strict',
                    ]);
                }

                // Redirect to dashboard
                header('Location: ' . BASE_URL . 'app/controllers/DashboardController.php');
                exit();
            } 
            else {
                $_SESSION['errors'] = ['Invalid email or password!'];
                header('Location: ' . BASE_URL . 'app/controllers/LoginController.php');
                exit();
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
