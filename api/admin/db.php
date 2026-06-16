<?php
require_once 'config.php';

try {
    $dsn = "pgsql:dbname=" . DB_NAME;
    if (DB_HOST !== '') {
        $dsn .= ";host=" . DB_HOST;
    }
    if (DB_PORT !== '') {
        $dsn .= ";port=" . DB_PORT;
    }
    if (DB_SSLMODE !== '' && strpos(DB_HOST, '/') !== 0) {
        $dsn .= ";sslmode=" . DB_SSLMODE;
    }
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(["error" => "Database Connection Failed: " . $e->getMessage()]);
    exit();
}
?>
