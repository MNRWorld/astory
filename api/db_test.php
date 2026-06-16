<?php
header('Content-Type: application/json');

// 1. Load config
require_once 'config.php';

echo json_encode([
    'status' => 'Testing database connection...',
    'config' => [
        'DB_HOST' => DB_HOST,
        'DB_PORT' => DB_PORT,
        'DB_NAME' => DB_NAME,
        'DB_USER' => DB_USER,
        'DB_SSLMODE' => DB_SSLMODE,
    ],
    'connection_attempts' => [
        'attempt_with_current_config' => tryConnection(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, DB_SSLMODE),
        'attempt_forcing_prefer' => tryConnection(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, 'prefer'),
        'attempt_forcing_disable' => tryConnection(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, 'disable'),
    ]
]);

function tryConnection($host, $port, $dbname, $user, $pass, $sslmode) {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    if ($sslmode !== '') {
        $dsn .= ";sslmode=$sslmode";
    }
    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 3
        ]);
        return "SUCCESS";
    } catch (PDOException $e) {
        return "FAILED: " . $e->getMessage();
    }
}
?>
