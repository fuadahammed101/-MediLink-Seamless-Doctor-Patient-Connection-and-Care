<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medilink";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to database.<br>";

// Test a simple query
$sql = "SELECT * FROM users LIMIT 1";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "Query executed successfully. User data:<br>";
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Name: " . $row["name"] . " - Email: " . $row["email"] . "<br>";
        }
    } else {
        echo "Query executed successfully but no users found.<br>";
    }
} else {
    echo "Query failed: " . $conn->error;
}

$conn->close();
?>
