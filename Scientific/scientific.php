<?php
include '../db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authorized
    exit();
}

$user_role = $_SESSION['user_role']; // Get user role from session

// Check user role for access control
if ($user_role !== 'directeur de site' && $user_role !== 'directeur scientifique' && $user_role !== 'superviseur') {
    echo "Access denied.";
    exit();
}

// Fetch scientific data relevant to the user
$user_id = $_SESSION['user_id'];
$query = "SELECT scientific_data.*, users.role FROM scientific_data JOIN users ON scientific_data.user_id = users.id WHERE scientific_data.user_id = ?"; // Example query
echo "User ID: " . $user_id; // Debugging output
echo "SQL Query: " . $query; // Debugging output


$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Scientifique</title>
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <h1>Gestion Scientifique</h1>
    </nav>
    <div class="container">
        <h2>Gestion Scientifique</h2>
        <div id="scientific-data-list" class="mt-4">
            <!-- Scientific data relevant to the user will be displayed here -->
            <?php
if ($result->num_rows > 0) {
    echo "Number of rows returned: " . $result->num_rows; // Debugging output

                while($row = $result->fetch_assoc()) {
                    echo "<div>{$row['data_field']} - Role: {$row['role']}</div>"; // Display scientific data and role
                }
            } else {
                echo "No scientific data found.";
            }
            ?>
        </div>
    </div>
</body>
</html>
