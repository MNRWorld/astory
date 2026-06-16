<?php
// api/admin/config.php

/**
 * Robust environment variable getter.
 * Checks $_ENV, $_SERVER, and getenv() fallback.
 */
function getEnvVar($name, $default = null) {
    if (isset($_ENV[$name]) && $_ENV[$name] !== '') {
        return $_ENV[$name];
    }
    if (isset($_SERVER[$name]) && $_SERVER[$name] !== '') {
        return $_SERVER[$name];
    }
    $val = getenv($name);
    if ($val !== false && $val !== '') {
        return $val;
    }
    return $default;
}

// Simple generic .env loader without dependencies
// Checks for .env.local first (standard for Vite) then .env as fallback
$rootPath = dirname(dirname(__DIR__));
$envPath = $rootPath . '/.env.local';

if (!file_exists($envPath)) {
    $envPath = $rootPath . '/.env';
}

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'"); // remove quotes and spaces
            
            // Check if it's already defined in the server environment
            $serverVal = getenv($name);
            if ($serverVal === false || $serverVal === '') {
                if (!isset($_ENV[$name])) {
                    $_ENV[$name] = $value;
                }
                if (!isset($_SERVER[$name])) {
                    $_SERVER[$name] = $value;
                }
                @putenv("$name=$value");
            } else {
                // If it is already defined in getenv, make sure $_ENV and $_SERVER also have it
                if (!isset($_ENV[$name])) {
                    $_ENV[$name] = $serverVal;
                }
                if (!isset($_SERVER[$name])) {
                    $_SERVER[$name] = $serverVal;
                }
            }
        }
    }
}

// Database configuration for CPanel PostgreSQL using Environment Variables
define('DB_HOST', getEnvVar('DB_HOST', 'localhost'));
define('DB_NAME', getEnvVar('DB_NAME', 'your_database_name'));
define('DB_USER', getEnvVar('DB_USER', 'your_database_user'));
define('DB_PASS', getEnvVar('DB_PASS', 'your_database_password'));
define('DB_PORT', getEnvVar('DB_PORT', '5432'));
define('DB_SSLMODE', getEnvVar('DB_SSLMODE', 'prefer'));

// CORS configuration (Adjust for production)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
