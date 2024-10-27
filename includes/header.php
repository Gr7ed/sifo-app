<!DOCTYPE html>
<html>

<head>
    <title><?php echo translate('welcome'); ?></title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/scripts.js"></script>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php"><?php echo translate('welcome'); ?></a></li>
                <li><a href="individual_dashboard.php"><?php echo translate('individual_dashboard'); ?></a></li>
                <li><a href="organization_dashboard.php"><?php echo translate('organization_dashboard'); ?></a></li>
                <li><a href="charity_dashboard.php"><?php echo translate('charity_dashboard'); ?></a></li>
                <li><a href="admin_dashboard.php"><?php echo translate('admin_dashboard'); ?></a></li>
                <li><a href="logout.php"><?php echo translate('logout'); ?></a></li>
            </ul>
        </nav>
        <form method="GET" action="change_language.php">
            <select name="lang" onchange="this.form.submit()">
                <option value="en">English</option>
                <option value="ar">عربي</option>
            </select>
        </form>
    </header>