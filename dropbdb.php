#!/usr/bin/env php
<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
try {
    $dotenv->load();
    echo ".env file loaded successfully.\n";
} catch (Exception $e) {
    echo "Could not load .env file: " . $e->getMessage();
}

// Database configuration from environment variables
$host = getenv('DB_HOST');
$db = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT');

// Debug statements to print environment variables
echo "Host: $host\n";
echo "Database: $db\n";
echo "User: $user\n";
echo "Port: $port\n";

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the list of tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // Drop each table
    foreach ($tables as $table) {
        $dropStmt = $pdo->prepare("DROP TABLE `$table`");
        $dropStmt->execute();
        echo "Dropped table: $table\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
