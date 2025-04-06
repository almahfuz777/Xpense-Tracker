<!-- app/models/User.php -->
<?php
require_once APP_PATH . '/core/Database.php';

class User{
    private $conn;
    public function __construct(){
        $this->conn = Database::getInstance();
    }

    // Check if the email already exists
    public function checkUsernameExists($username) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
        if (!$stmt) return false;

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
        if (!$stmt) return false;

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
        if (!$stmt) return false;

        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if($stmt->execute()){
            $userId = $stmt->insert_id;
            $stmt->close();

            // Create user profile and default account
            $this->createUserProfile($userId);
            $this->addDefaultAccount($userId);

            return $userId;
        }
        else{
            $error = $stmt->error;
            $stmt->close();
            return false;
        }
    }

    public function login($email, $password){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) return false;

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

    public function createUserProfile($userId) {
        $defaultPicture = 'default_profile.jpg';
    
        $stmt = $this->conn->prepare("
            INSERT INTO user_profiles (
                user_id, fname, lname, gender, date_of_birth, profile_picture, phone, address
            ) VALUES (?, NULL, NULL, NULL, NULL, ?, NULL, NULL)
        ");
        
        if (!$stmt) return false;
    
        $stmt->bind_param("is", $userId, $defaultPicture);
        $stmt->execute();
        $stmt->close();
    
        return true;
    }
    

    public function addDefaultAccount($userId) {
        $stmt = $this->conn->prepare("INSERT INTO accounts (user_id, account_name, balance) VALUES (?, 'Cash', 0.00)");
        if (!$stmt) return false;
    
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
    

    public function getUserById($id){
        $stmt = $this->conn->prepare("SELECT id, username FROM users WHERE id = ?");
        if (!$stmt) return false;
    
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function getProfilePicture($userId) {
        $stmt = $this->conn->prepare("SELECT profile_picture FROM user_profiles WHERE user_id = ?");
        if (!$stmt) return 'default_profile.jpg'; // fallback if query fails
    
        $stmt->bind_param("i", $userId);
    
        $profilePicture = null;
        $stmt->execute();
        $stmt->bind_result($profilePicture);
        $stmt->fetch();
        $stmt->close();
    
        return $profilePicture ?: 'default_profile.jpg';
    }

}
?>