<?php
// Update these values for your local MySQL / MariaDB setup.
const DB_HOST = 'localhost';
const DB_NAME = 'kitchen71';
const DB_USER = 'root';
const DB_PASS = '';

session_start();

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=3307;dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    exit('Database connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function require_login()
{
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

function require_admin()
{
    $user = current_user();
    if (!$user || ($user['role'] ?? 'customer') !== 'admin') {
        header('Location: index.php');
        exit;
    }
}

