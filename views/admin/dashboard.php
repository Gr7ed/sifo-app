<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /sifo-app/views/auth/login.php");
    exit();
}

// Ensure only admins can access this page
require_once '../../models/AdminModel.php';
require_once '../../config/config.php';

$adminModel = new AdminModel($db);
if (!$adminModel->isAdmin($_SESSION['user_id'])) {
    die("Access denied: You do not have admin privileges.");
}

include_once '../layouts/header.php';
?>

<h1>Admin Dashboard</h1>
<p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
<p>This is the admin control panel.</p>

<a href="/sifo-app/controllers/auth_controller.php?action=logout">Logout</a>

<?php include_once '../layouts/footer.php'; ?>