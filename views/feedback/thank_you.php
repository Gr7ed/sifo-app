<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    body {
        font-family: 'Alexandria', sans-serif;
        background-color: #faf7f0;
        color: #4a4947;
        margin: 0;
        padding: 0;
    }

    main {
        padding: 20px;
        max-width: 600px;
        margin: 50px auto;
        background-color: #faf7f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h1 {
        font-size: 24px;
        color: #4a4947;
        margin-bottom: 20px;
    }

    p {
        font-size: 16px;
        color: #4a4947;
        margin-bottom: 30px;
        line-height: 1.5;
    }

    a {
        display: inline-block;
        background-color: #4a4947;
        color: #faf7f0;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 4px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    a:hover {
        background-color: #fccd2a;
        color: #4a4947;
    }
</style>

<main>
    <h1><?php echo translate('thanks'); ?></h1>
    <p><?php echo translate('feedback-thanks'); ?></p>

    <a href="/sifo-app/views/"><?php echo translate('return-home'); ?></a>
</main>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>