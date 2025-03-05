<!-- app/core/Database.php -->
<?php
class Database {
    private static $instance = null;
    private $conn;

    public function __construct(){
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);   

        if($this->conn->connect_error){
            die("Database connection error: " . $this->conn->connect_error);
        }
        // echo "Database connection successful";
    }
    
    public static function getInstance(){
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>