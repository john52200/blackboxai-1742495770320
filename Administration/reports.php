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
    <title>Gestion des Rapports</title>
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <h1>Gestion des Rapports</h1>
    </nav>
    <div class="container">
        <h2>Gestion des Rapports</h2>
        <button onclick="showModal('report-modal')" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Créer un Rapport</button>
        <div id="reports-list" class="mt-4">
            <!-- Les rapports seront ajoutés ici dynamiquement -->
        </div>

        <?php
        // Fetch report data from the database
        $connection = get_db_connection();
        $query = "SELECT * FROM reports"; // Example query
        $result = $connection->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div>{$row['title']}</div>"; // Adjust according to your data structure
            }
        } else {
            echo "No reports found.";
        }
        $connection->close();
        ?>
    </div>

    <div id="report-modal" class="modal hidden">
        <h2 class="text-2xl font-bold mb-4">Créer un Rapport</h2>
        <form id="report-form" class="space-y-4" method="POST" action="create_report.php">
            <div>
                <label class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Contenu</label>
                <textarea name="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Département</label>
                <select name="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Classification</label>
                <select name="classification" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="Public">Public</option>
                    <option value="Restreint">Restreint</option>
                    <option value="Confidentiel">Confidentiel</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideModal('report-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Annuler</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-md hover:bg-yellow-600">Créer</button>
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
