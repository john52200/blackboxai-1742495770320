<?php
include '../db_connection.php';
session_start();

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not authorized
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Sanctions</title>
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <h1>Gestion des Sanctions</h1>
    </nav>
    <div class="container">
        <h2>Gestion des Sanctions</h2>
        <button onclick="showModal('sanction-modal')" class="bg-red-500 text-white px-4 py-2 rounded-md">Ajouter une Sanction</button>
        <div id="sanctions-list" class="mt-4">
            <!-- Les sanctions seront ajoutées ici dynamiquement -->
        </div>

        <?php
        // Fetch sanction data from the database
        $connection = get_db_connection();
        $query = "SELECT * FROM sanctions"; // Example query
        $result = $connection->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div>{$row['reason']}</div>"; // Adjust according to your data structure
            }
        } else {
            echo "No sanctions found.";
        }
        $connection->close();
        ?>
    </div>

    <div id="sanction-modal" class="modal hidden">
        <h2 class="text-2xl font-bold mb-4">Ajouter une Sanction</h2>
        <form id="sanction-form" class="space-y-4" method="POST" action="create_sanction.php">
            <div>
                <label class="block text-sm font-medium text-gray-700">Personnel</label>
                <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Raison</label>
                <textarea name="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sévérité</label>
                <select name="severity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="légère">Légère</option>
                    <option value="modérée">Modérée</option>
                    <option value="sévère">Sévère</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideModal('sanction-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Annuler</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600">Ajouter</button>
            </div>
        </form>
    </div>

    <script src="../js/utils.js"></script>
    <script>
        function showModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function hideModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</body>
</html>
