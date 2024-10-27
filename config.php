<?php
session_start();

// Load the appropriate language file based on the session
$lang = $_SESSION['lang'] ?? 'en';
$translations = json_decode(file_get_contents("language/{$lang}.json"), true);

function translate($key)
{
    global $translations;
    return $translations[$key] ?? $key;
}

// Database connection settings
$host = 'localhost';
$db = 'sifo_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}
