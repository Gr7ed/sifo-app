<!-- INSERT INTO admins (user_id) VALUES (1); -->
<!-- INSERT INTO users (user_type, username, email, password, first_name, last_name, phone, city, district, created_at)
VALUES ('donor', 'admin_user', 'admin@example.com', '$2y$10$abcdefghijklmnopqrstuvwx', 'Admin', 'User', '1234567890', 'Riyadh', 'Olaya', NOW()); -->
<!-- $hashed_pass = password_hash('admin1234', PASSWORD_BCRYPT);
// echo $hashed_pass; -->
<?php
require_once 'config/config.php';
require_once 'models/AdminModel.php';

$adminModel = new AdminModel($db);

// Replace with the user_id of the user to promote
$userIdToPromote = 44;

try {
    $adminModel->addAdmin($userIdToPromote);
    echo "User with ID $userIdToPromote is now an admin.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// 
