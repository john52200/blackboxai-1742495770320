<?php
include 'db_connection.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Scientifique</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <h1>Gestion Scientifique</h1>
    </nav>
    <div class="container">
        <h2>Gestion Scientifique</h2>
        <!-- Scientific management content goes here -->
        <?php
        // Fetch scientific data from the database
        $connection = get_db_connection();
        $query = "SELECT * FROM scientific_data"; // Example query
        $result = $connection->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div>{$row['data_field']}</div>"; // Adjust according to your data structure
            }
        } else {
            echo "No data found.";
        }
        $connection->close();
        ?>
    </div>
</body>
</html>
