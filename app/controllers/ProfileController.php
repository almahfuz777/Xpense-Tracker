<?php
// app/controllers/ProfileController.php
require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Controller.php';
require_once MODEL_PATH . '/User.php';

class ProfileController extends Controller {
    public function index() {
        $this->requireLogin();
        $userModel = $this->loadModel('User');
        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username'];

        $profile = $userModel->getProfile($userId);

        $data = [
            'pageTitle' => "$username | Profile",
            'page' => 'profile',
            'profile' => $profile,
        ];

        $this->loadView('user/profile', $data);
    }

    public function updateField() {
        $this->requireLogin();
        $userModel = $this->loadModel('User');
    
        $userId = $_SESSION['user_id'];
        $field = $_POST['field'] ?? '';
        $value = $_POST['value'] ?? '';
    
        $allowedFields = ['fname', 'lname', 'phone', 'address', 'gender', 'date_of_birth'];
        if (!in_array($field, $allowedFields)) {
            echo json_encode(['success' => false, 'message' => 'Invalid field']);
            return;
        }
    
        $updated = $userModel->updateProfileField($userId, $field, $value);
        echo json_encode([
            'success' => $updated ? true : false,
            'message' => $updated ? 'Field updated successfully' : 'Update failed'
        ]);
        exit;
    }

    public function uploadPicture() {
        $this->requireLogin();
        $userModel = $this->loadModel('User');
        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username'];
    
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_picture'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $uploadDir = PUBLIC_PATH . '/assets/images/uploads/';
            $filename = 'user_' . $username . '_' . $userId . '_' . time() . '_' . basename($file['name']);
            $targetPath = $uploadDir . $filename;
    
            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['alert-error'] = 'Only JPG, PNG, and WEBP images are allowed.';
                header("Location: ProfileController.php");
                exit;
            }
    
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $userModel->updateProfileField($userId, 'profile_picture', $filename);
                $_SESSION['profile_picture'] = $filename;
                $_SESSION['alert-success'] = 'Profile picture updated successfully.';
            } else {
                $_SESSION['alert-error'] = 'Failed to upload the image.';
            }
        } else {
            $_SESSION['alert-error'] = 'No file selected or upload error.';
        }
    
        header("Location: ProfileController.php");
        exit;
    }
    
}

$profileController = new ProfileController();
$profileController->dispatch();
