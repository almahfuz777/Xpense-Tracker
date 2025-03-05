<?php
class User{
    private $conn;

    public function __construct(){
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);   

        if($this->conn->connect_error){
            die("Database connection error: " . $this->conn->connect_error);
        }
        // echo "Database connection successful";
    }

    // Check if the email already exists
    public function checkUsernameExists($username) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
        if (!$stmt) {
            return "Database preparation error: " . $this->conn->error;
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Check if the email already exists
    public function checkEmailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$stmt) {
            return "Database preparation error: " . $this->conn->error;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Register a new user
    public function register($username, $email, $password){
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        // Insert new user record
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            return "Database preparation error: " . $this->conn->error;
        }

        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if($stmt->execute()){
            $userId = $stmt->insert_id;
            $stmt->close();
            return $userId;
        }
        else{
            $error = $stmt->error;
            $stmt->close();
            return "Error: " . $error;
        }
    }

    public function login($email, $password){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) {
            return "Database preparation error: " . $this->conn->error;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows===1){
            $user = $result->fetch_assoc();

            // Verify the password
            if(password_verify($password, $user['password'])){
                $stmt->close();
                return [
                    'id'=>$user['id'],
                    'username'=>$user['username']
                ];
            }
            else{
                $stmt->close();
                return false;       // Invalid password
            }
        }
        else{
            $stmt->close();
            return false;           // Email not found
        }
    }

    // public function getUserById($id){
    //     $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
    //     if (!$stmt) {
    //         return "Database preparation error: " . $this->conn->error;
    //     }

    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     if($stmt->num_rows===1){
    //         $user = $result->fetch_assoc();
    //         $stmt->close();
    //         return $user;
    //     }
    //     else{
    //         $stmt->close();
    //         return "User not found.";
    //     }   
    // }

    // Destructor to close the database connection
    public function __destruct(){
        if($this->conn){
            $this->conn->close();
        }
    }

}
?>