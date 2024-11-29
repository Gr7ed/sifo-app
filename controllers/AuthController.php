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

        // Validate password and confirm password match
        if ($userData['password'] !== $userData['confirm_password']) {
            die("Passwords do not match. Please ensure the password and confirm password fields are the same.");
        }

        // Check if the username already exists
        if ($this->userModel->isUsernameExists($userData['username'])) {
            die("The username '{$userData['username']}' is already taken. Please choose another.");
        }

        // Check if the email already exists
        if ($this->userModel->isEmailExists($userData['email'])) {
            die("The email '{$userData['email']}' is already registered. Please use another email.");
        }

        // Check if the charity registration number exists (only for charities)
        if (
            $userData['user_type'] === 'charity' &&
            $this->userModel->isCharityRegistrationNumberExists($userData['charity_registration_number'])
        ) {
            die("The charity registration number '{$userData['charity_registration_number']}' is already registered. Please check your information.");
        }

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
                $userData['charity_name'],
                $userData['accepted_types'],
                $userData['city'],
                $userData['district']
            );
        }

        // Redirect to login page
        header("Location: /sifo-app/views/auth/login.php");
        exit();
    }

    /**
     * Log in an existing user
     * @param string $identifier
     * @param string $password
     */
    public function login($identifier, $password)
    {
        // Check if the user exists by email or username
        $user = $this->userModel->findUserByIdentifier($identifier);

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
            die("Invalid email, username, or password.");
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
            'confirm_password' => $_POST['confirm_password'] ?? null,
            'user_type' => $_POST['user_type'] ?? null,
            'charity_registration_number' => $_POST['charity_registration_number'] ?? null,
            'charity_name' => $_POST['charity_name'] ?? null,
            'accepted_types' => $_POST['accepted_types'] ?? null, // For charities
            'city' => $_POST['city'] ?? null,
            'district' => $_POST['district'] ?? null
        ];
        // Validate password and confirm password match
        if (!empty($password) && $password !== $confirmPassword) {
            die("Passwords do not match. Please ensure the password and confirm password fields are the same.");
        }

        $auth->register($userData);
    } elseif ($_GET['action'] === 'login') {
        $identifier = $_POST['identifier'] ?? null; // Can be email or username
        $password = $_POST['password'] ?? null;

        if (empty($identifier) || empty($password)) {
            die("Email/Username and password are required.");
        }

        $auth->login($identifier, $password);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'logout') {
    $auth = new AuthController();
    $auth->logout();
}