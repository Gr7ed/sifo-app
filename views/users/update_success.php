<?php
include __DIR__ . '/../layouts/header.php';
?>

<!-- Styles -->
<style>
    body {
        font-family: "Alexandria", sans-serif;
        background-color: #faf7f0;
        margin: 0;
        padding: 0;
    }

    .success-container {
        max-width: 600px;
        margin: 100px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .success-container h1 {
        font-size: 2.5em;
        color: #4a4947;
        margin-bottom: 20px;
    }

    .success-container p {
        font-size: 1.2em;
        color: #6c6a68;
        margin-bottom: 30px;
    }

    .success-container a {
        display: inline-block;
        background-color: #4a4947;
        color: #faf7f0;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 1em;
        transition: background-color 0.3s ease;
    }

    .success-container a:hover {
        background-color: #fccd2a;
    }
</style>

<div class="success-container">
    <h1>Account Updated Successfully!</h1>
    <p>Your account information has been updated.</p>
    <a href="/sifo-app/views/users/<?php echo strtolower($userType); ?>_account.php">Return to Account</a>
</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>