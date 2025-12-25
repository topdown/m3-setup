<?php

// This script tests connections to MySQL, MongoDB, and Redis.
// Do not put this on a public server.

require 'vendor/autoload.php';

echo "=== Database Connections ===<br /><br />";

// MySQL (MySQLi)
try {
    $mysqli = new mysqli("localhost", "root", "");
    echo "✓ MySQL Connected<br />";
    $mysqli->close();
} catch (Exception $e) {
    echo "✗ MySQL Error: " . $e->getMessage() . "<br />";
}

// MongoDB
try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $client->listDatabases();
    echo "✓ MongoDB Connected<br />";
} catch (Exception $e) {
    echo "✗ MongoDB Error: " . $e->getMessage() . "<br />";
}

// Redis (using Predis)
try {
    $redis = new Predis\Client('tcp://localhost:6379');
    $redis->ping();
    echo "✓ Redis Connected<br />";
} catch (Exception $e) {
    echo "✗ Redis Error: " . $e->getMessage() . "<br />";
}
