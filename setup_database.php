<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medilink";

// Create connection without specifying database to create it first
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read SQL file
$sql = file_get_contents('database_schema.sql');
if ($sql === false) {
    die("Failed to read database_schema.sql file.");
}

// Split SQL statements by semicolon
$statements = array_filter(array_map('trim', explode(';', $sql)));

foreach ($statements as $statement) {
    if (stripos($statement, 'CREATE DATABASE') === 0) {
        // Execute CREATE DATABASE statement
        if ($conn->query($statement) === TRUE) {
            echo "Database created successfully.<br>";
        } else {
            echo "Error creating database: " . $conn->error . "<br>";
        }
    }
}

// Select the database
$conn->select_db($dbname);

// Execute other statements
foreach ($statements as $statement) {
    if (stripos($statement, 'CREATE DATABASE') === 0) {
        continue; // already executed
    }
    if ($conn->query($statement) === TRUE) {
        echo "Executed: " . htmlspecialchars($statement) . "<br>";
    } else {
        echo "Error executing statement: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
