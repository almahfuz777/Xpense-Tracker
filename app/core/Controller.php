<?php
// app/core/Controller.php
require_once __DIR__ . '/../config/config.php';

// Base Controller class to load models and views in controllers 
// This class will be extended by all other controllers
// It contains two methods: loadModel() and loadView()
// loadModel() is used to load a model file and return an instance of the model
// loadView() is used to load a view file and pass data to it
// The loadView() method accepts an optional second parameter to pass data to the view
// The data parameter is an associative array where the keys are the variable names and the values are the data to be passed to the view
// The loadView() method will extract the data array into individual variables that can be used in the view
// This allows the controller to pass data to the view without explicitly defining each variable
// This is a common pattern in MVC frameworks to keep the controller code clean and decoupled from the view

class Controller {
    // Base Controller Constructor, runs before any child controller method is called
    public function __construct(){
        // Start session once for the whole site
        if(session_status() == PHP_SESSION_NONE){
            session_start();
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
        if(file_exists($viewPath)) {
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