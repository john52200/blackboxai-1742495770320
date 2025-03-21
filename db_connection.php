<?php
$servername = "193.160.130.155:3306";
$username = "Scpidata";
$password = "Z~792ru0u";
$dbname = "Scpinet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>