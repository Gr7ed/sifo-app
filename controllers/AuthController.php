<?php
require_once '../config/config.php';
require_once '../models/UserModel.php';
require_once '../models/AdminModel.php';

class AuthController
{
    private $userModel;
    private $adminModel;

    public function __construct()
    {
        global $db; // Use the global PDO instance from config.php
        $this->userModel = new UserModel($db);
        $this->adminModel = new AdminModel($db);
    }

    /**
     * Register a new user
     * @param array $userData
     */
    public function register($userData)
    {
        // Hash the password
        $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);

        // Save user to `users` table
        $userId = $this->userModel->saveUser($userData);

        // Save additional details based on user type
        if ($userData['user_type'] === 'donor') {
            $this->userModel->saveDonorDetails($userId, $userData);
        } elseif ($userData['user_type'] === 'charity') {
            $this->userModel->saveCharityDetails(
                $userId,
                $userData['charity_registration_number'],
                $userData['accepted_types']
            );
        }

        // Redirect to login page
        header("Location: /sifo-app/views/auth/login.php");
        exit();
    }

    /**
     * Log in an existing user
     * @param string $email
     * @param string $password
     */
    public function login($email, $password)
    {
        $user = $this->userModel->findUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Start session if not already active
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];

            // Redirect based on user type
            if ($this->adminModel->isAdmin($user['user_id'])) {
                header("Location: /sifo-app/views/admin/dashboard.php");
            } elseif ($user['user_type'] === 'charity') {
                header("Location: /sifo-app/views/dashboard/charity_dashboard.php");
            } elseif ($user['user_type'] === 'donor') {
                header("Location: /sifo-app/views/dashboard/donor_dashboard.php");
            }
            exit();
        } else {
            echo "Invalid email or password.";
        }
    }

    /**
     * Log out the user
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        header("Location: /sifo-app/views/auth/login.php");
        exit();
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();

    if ($_GET['action'] === 'register') {
        $userData = [
            'username' => $_POST['username'] ?? null,
            'first_name' => $_POST['first_name'] ?? null,
            'last_name' => $_POST['last_name'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'email' => $_POST['email'] ?? null,
            'password' => $_POST['password'] ?? null,
            'user_type' => $_POST['user_type'] ?? null,
            'charity_registration_number' => $_POST['charity_registration_number'] ?? null,
            'accepted_types' => $_POST['accepted_types'] ?? null, // For charities
            'city' => $_POST['city'] ?? null,
            'district' => $_POST['district'] ?? null
        ];

        // Validate required fields
        foreach (['username', 'first_name', 'last_name', 'phone', 'email', 'password', 'user_type', 'city', 'district'] as $field) {
            if (empty($userData[$field])) {
                die("All fields are required.");
            }
        }

        $auth->register($userData);
    } elseif ($_GET['action'] === 'login') {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (empty($email) || empty($password)) {
            die("Email and password are required.");
        }

        $auth->login($email, $password);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'logout') {
    $auth = new AuthController();
    $auth->logout();
}
