<?php
// app/core/Controller.php
require_once __DIR__ . '/../config/config.php';
/*
- Base Controller class to load models and views in controllers. 
- This class will be extended by all other controllers.
- It contains two methods: loadModel() and loadView()
- loadModel() is used to load a model file and return an instance of the model
- loadView() is used to load a view file and pass data to it
*/
class Controller {
    // Base Controller Constructor, runs before any child controller method is called
    public function __construct(){
        // Start session once for the whole site
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Authenticate user before loading restricted pages
    protected function requireLogin(){
        if (!isset($_SESSION['user_id'])) {
            // Check if remember me cookie is set
            if(isset($_COOKIE['remember_me'])){
                $userModel = $this->loadModel('User');
                $user = $userModel->getUserById($_COOKIE['remember_me']);
                if($user){
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    return;
                }
            }
            
            // If no session and no valid cookie, redirect to login
            $_SESSION['message'] = "You must log in first.";
            header('Location: ' . BASE_URL . 'app/controllers/LoginController.php');
            exit();
        }
    }

    public function loadModel($model) {
        $modelPath = MODEL_PATH . '/' . $model . '.php';
        if(file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } 
        else {
            die("Model file not found! Path: " . $modelPath);
        }
    }

    public function loadView($view, $data = []) {
        $viewPath = VIEW_PATH . '/' . $view . '.php';

        // pass data to all views


        if(file_exists($viewPath)) {
            $data['isLoggedIn'] = isset($_SESSION['user_id']);
            $data['username'] = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
            
            extract($data);
            require_once $viewPath;
        } 
        else {
            die("View file not found! Path: " . $viewPath);
        }
    }

    // Dispatch the request to the correct controller and method
    public function dispatch(){
        $action = $_GET['action'] ?? 'index';   // Default action is index

        // Check if the method exists in the controller
        if(method_exists($this, $action)){
            $this->$action();
        }
        else{
            die("Invalid action: " . htmlspecialchars($action));
        }
    }
}

?>