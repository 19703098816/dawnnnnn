<?php
// Test session file to check if session is working
session_start();
require_once 'config/database.php';

header('Content-Type: application/json; charset=utf-8');

$result = [
    'session_id' => session_id(),
    'session_status' => session_status() === PHP_SESSION_ACTIVE ? 'ACTIVE' : 'INACTIVE',
    'session_name' => session_name(),
    'session_keys' => array_keys($_SESSION),
    'session_data' => $_SESSION,
    'cookie_received' => isset($_COOKIE[session_name()]) ? $_COOKIE[session_name()] : 'NOT SET',
    'all_cookies' => $_COOKIE,
    'user_id' => $_SESSION['user_id'] ?? 'NOT SET',
    'user_email' => $_SESSION['user_email'] ?? 'NOT SET',
    'server' => [
        'HTTP_COOKIE' => $_SERVER['HTTP_COOKIE'] ?? 'NOT SET',
        'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'],
        'REQUEST_URI' => $_SERVER['REQUEST_URI']
    ]
];

echo json_encode($result, JSON_PRETTY_PRINT);
?>

