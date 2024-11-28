<?php
date_default_timezone_set('Asia/Riyadh'); // Set the time zone to Riyadh

// Start a session only if one is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load the appropriate language file based on the session
// Default language (set to )
$lang = $_SESSION['lang'] ?? 'ar';

// Path to language files
$langFile = __DIR__ . '/../assets/lang/' . $lang . '.json';

if (file_exists($langFile)) {
    $translations = json_decode(file_get_contents($langFile), true);
} else {
    $translations = []; // Fallback if file doesn't exist
}

// Check if the function already exists before declaring it
if (!function_exists('translate')) {
    /**
     * Translate function
     * @param string $key
     * @return string
     */
    function translate($key)
    {
        global $translations;
        return $translations[$key] ?? $key; // Return the key if translation not found
    }
}
if (!function_exists('translateStatus')) {
    function translateStatus($status)
    {
        $translations = [
            'Pending' => translate('pending'),
            'Forwarded' => translate('forwarded'),
            'Delivered' => translate('delivered'),
            'Available' => translate('available'),
            'Food' => translate('food'),
            'Non-Food' => translate('nonfood'),
            'Riyadh' => translate('riyadh'),
            'Jeddah' => translate('jeddah'),
            'Dammam' => translate('dammam'),
            'Mecca' => translate('mecca'),
            'Medina' => translate('medina')
        ];

        return $translations[$status] ?? $status; // Return the original status if no translation is found
    }

}


// Database connection settings
$host = 'localhost';
$dbname = 'sifo_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $db = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
