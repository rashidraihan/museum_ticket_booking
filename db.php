<?php
declare(strict_types=1);

/**
 * Database connection. Credentials live in config.php (not committed to Git).
 * Copy config.sample.php to config.php and fill in your own details.
 */

require_once __DIR__ . '/functions.php';

$configFile = __DIR__ . '/config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('Configuration missing: copy config.sample.php to config.php and enter your database details.');
}
$config = require $configFile;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['name']);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $ex) {
    error_log('Database connection failed: ' . $ex->getMessage());
    http_response_code(500);
    exit('Could not connect to the database. Please check config.php.');
}
