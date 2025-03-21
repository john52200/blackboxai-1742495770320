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
    <title>Gestion du Personnel</title>
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <h1>Gestion du Personnel</h1>
    </nav>
    <div class="container">
        <h2>Gestion du Personnel</h2>
        <button onclick="showModal('personnel-modal')" class="bg-green-500 text-white px-4 py-2 rounded-md">Ajouter un Membre du Personnel</button>
        <div id="personnel-list" class="mt-4">
            <!-- Le personnel sera ajouté ici dynamiquement -->
        </div>

        <?php
        // Fetch personnel data from the database
        $connection = get_db_connection();
        $query = "SELECT * FROM personnel"; // Example query
        $result = $connection->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div>{$row['name']}</div>"; // Adjust according to your data structure
            }
        } else {
            echo "No personnel found.";
        }
        $connection->close();
        ?>
    </div>

    <div id="personnel-modal" class="modal hidden">
        <h2 class="text-2xl font-bold mb-4">Ajouter un Membre du Personnel</h2>
        <form id="personnel-form" class="space-y-4" method="POST" action="create_personnel.php">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Département</label>
                <select name="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Rôle</label>
                <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="user">Utilisateur</option>
                    <option value="department_head">Chef de département</option>
                    <option value="director">Directeur</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideModal('personnel-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Annuler</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Ajouter</button>
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
