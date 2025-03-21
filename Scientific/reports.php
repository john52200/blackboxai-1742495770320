<?php
include '../db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirect to login if not authorized
    exit();
}

$user_role = $_SESSION['user_role']; // Get user role from session

// Check user role for access control
if ($user_role !== 'directeur de site' && $user_role !== 'directeur scientifique' && $user_role !== 'superviseur') {
    echo "Access denied.";
    exit();
}

// Fetch report data relevant to the user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM reports WHERE user_id = ?"; // Example query
if (!$stmt) {
    echo "Error preparing statement: " . $connection->error;
    exit();
}

$stmt = $connection->prepare($query);
if (!$stmt->bind_param("i", $user_id)) {
    echo "Error binding parameters: " . $stmt->error;
    exit();
}

if (!$stmt->execute()) {
    echo "Error executing statement: " . $stmt->error;
    exit();
}

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rapports</title>
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <h1>Gestion des Rapports</h1>
    </nav>
    <div class="container">
        <h2>Gestion des Rapports</h2>
        <div id="reports-list" class="mt-4">
            <!-- Report data relevant to the user will be displayed here -->
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div>{$row['report_title']} - Date: {$row['date']}</div>"; // Display report title and date
                }
            } else {
                echo "No reports found.";
            }
            ?>
        </div>
    </div>
</body>
</html>
