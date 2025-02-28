<?php
// app/controllers/SignupController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/User.php';


class SignupController extends Controller{
    // Default method to load the dashboard view
    public function index(){
        $this->loadView('auth/signup');
    }

    // Handle form submission and user registration
    public function register(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve and sanitize form data
            $username = htmlspecialchars(trim($_POST['username']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = htmlspecialchars($_POST['password']);
            $confirm_password = htmlspecialchars($_POST['confirm_password']);

            // Validation
            $errors = [];
            if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                $errors[] = 'All fields are required';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email address';
            }
            if ($password !== $confirm_password) {
                $errors[] = 'Passwords do not match';
            }

            // Check if the username or email already exists
            $userModel = $this->loadModel('User');
            if($userModel->checkUsernameExists($username)){
                $errors[] = 'Username already taken';
            }
            if($userModel->checkEmailExists($email)){
                $errors[] = 'Email already registered';
            }

            // If there are errors, reload the form with error message
            if (!empty($errors)) {
                $this->loadView('auth/signup', ['errors' => $errors]);
                return;
            }

            // If no errors, register the user
            $userId = $userModel->register($username, $email, $password);
            if(is_int($userId)){
                // Registration successful, redirect to login with success message
                $_SESSION['message'] = "Account created successfully. Please log in.";
                header('Location: ' . BASE_URL . 'app/controllers/LoginController.php');
                exit();
            }
            else{
                // Registration failed, reload the form with error message
                $errors[] = 'Failed to create account. Please try again.';
                $this->loadView('auth/signup', ['errors' => $errors]);
            }
        }
    }

}

$signupController = new SignupController();
$signupController->dispatch();
?>