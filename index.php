<?php
// db_connection.php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet Role Play</title>
    <?php include 'db_connection.php'; ?>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Add Auth0 script -->
    <script src="https://cdn.auth0.com/js/auth0-spa-js/2.0/auth0-spa-js.production.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .loading-spinner {
            border: 3px solid rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            border-top: 3px solid #3b82f6;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-width: 90%;
            width: 500px;
        }
        .modal.active {
            display: block;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Loading Indicator -->
    <div id="loading-indicator" class="fixed inset-0 modal-backdrop flex items-center justify-center z-50 hidden">
        <?php
        // Fetch departments from the database
        $sql = "SELECT * FROM departments";
        $result = $conn->query($sql);
        ?>

        <div class="bg-white p-6 rounded-lg shadow-xl flex items-center space-x-4">
            <div class="loading-spinner"></div>
            <p class="text-gray-700 font-medium">Chargement en cours...</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold">Intranet RP</h1>
                    <div id="nav-links" class="hidden md:flex space-x-4">
                        <a href="index.php" class="hover:text-gray-300 transition-colors">Accueil</a>
                        <a href="departments.php" class="hover:text-gray-300 transition-colors">Départements</a>
                        <a href="personnel.php" class="hover:text-gray-300 transition-colors">Personnel</a>
                        <a href="reports.php" class="hover:text-gray-300 transition-colors">Rapports</a>
                        <a href="sanctions.php" class="hover:text-gray-300 transition-colors">Sanctions</a>
                        <a href="budget.php" class="hover:text-gray-300 transition-colors">Budget</a>

                    </div>
                </div>
                <div id="user-info" class="hidden items-center space-x-4">
                    <span id="user-name" class="text-sm"></span>
                    <button id="logout-btn" class="bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </button>
                </div>
                <button id="login-btn" class="bg-blue-500 px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                </button>
            </div>
        </div>
    </nav>

    <!-- Login Container -->
    <div id="login-container" class="container mx-auto mt-10 px-4">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8">
            <div class="text-center">
                <i class="fas fa-user-shield text-5xl text-blue-500 mb-4"></i>
                <h2 class="text-2xl font-bold mb-4">Bienvenue sur l'Intranet</h2>
                <p class="text-gray-600 mb-6">Veuillez vous connecter pour accéder à l'interface.</p>
                <div class="space-y-4">
                    <button id="auth0-login-btn" class="bg-black text-white px-8 py-3 rounded-lg hover:bg-gray-800 transition-colors w-full flex items-center justify-center">
                        <i class="fas fa-lock mr-2"></i>Se connecter avec Auth0
                    </button>
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Ou</span>
                        </div>
                    </div>
                    <button id="main-login-btn" class="bg-blue-500 text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition-colors w-full flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter en local
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard -->
    <div id="dashboard" class="container mx-auto mt-10 px-4 hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Départements -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-building text-blue-500 mr-3"></i>
                        Départements
                    </h3>
                    <button onclick="showModal('department-modal')" class="text-blue-500 hover:text-blue-700 transition-colors">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div id="departments-list" class="space-y-4">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='department-item'>" . $row["name"] . "</div>";
                        }
                    } else {
                        echo "Aucun département trouvé.";
                    }
                    ?>

                </div>
            </div>

            <!-- Personnel -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-users text-green-500 mr-3"></i>
                        Personnel
                    </h3>
                    <button onclick="showModal('personnel-modal')" class="text-green-500 hover:text-green-700 transition-colors">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
    <div id="personnel-list" class="space-y-4">
                    <?php
                    // Fetch personnel from the database
                    $sql_personnel = "SELECT * FROM personnel";
                    $result_personnel = $conn->query($sql_personnel);
                    if ($result_personnel->num_rows > 0) {
                        while($row = $result_personnel->fetch_assoc()) {
                            echo "<div class='personnel-item'>" . $row["name"] . "</div>";
                        }
                    } else {
                        echo "Aucun personnel trouvé.";
                    }
                    ?>

                </div>
            </div>

            <!-- Rapports -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-file-alt text-yellow-500 mr-3"></i>
                        Rapports
                    </h3>
                    <button onclick="showModal('report-modal')" class="text-yellow-500 hover:text-yellow-700 transition-colors">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
    <div id="reports-list" class="space-y-4">
                    <?php
                    // Fetch reports from the database
                    $sql_reports = "SELECT * FROM reports";
                    $result_reports = $conn->query($sql_reports);
                    if ($result_reports->num_rows > 0) {
                        while($row = $result_reports->fetch_assoc()) {
                            echo "<div class='report-item'>" . $row["title"] . "</div>";
                        }
                    } else {
                        echo "Aucun rapport trouvé.";
                    }
                    ?>

                </div>
            </div>

            <!-- Sanctions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                        Sanctions
                    </h3>
                    <button onclick="showModal('sanction-modal')" class="text-red-500 hover:text-red-700 transition-colors">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
    <div id="sanctions-list" class="space-y-4">
                    <?php
                    // Fetch sanctions from the database
                    $sql_sanctions = "SELECT * FROM sanctions";
                    $result_sanctions = $conn->query($sql_sanctions);
                    if ($result_sanctions->num_rows > 0) {
                        while($row = $result_sanctions->fetch_assoc()) {
                            echo "<div class='sanction-item'>" . $row["reason"] . "</div>";
                        }
                    } else {
                        echo "Aucune sanction trouvée.";
                    }
                    ?>

                </div>
            </div>

            <!-- Budget -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-money-bill-wave text-emerald-500 mr-3"></i>
                        Budget
                    </h3>
                    <button onclick="showModal('budget-modal')" class="text-emerald-500 hover:text-emerald-700 transition-colors">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
    <div id="budget-list" class="space-y-4">
                    <?php
                    // Fetch budget requests from the database
                    $sql_budget = "SELECT * FROM budget_requests";
                    $result_budget = $conn->query($sql_budget);
                    if ($result_budget->num_rows > 0) {
                        while($row = $result_budget->fetch_assoc()) {
                            echo "<div class='budget-item'>" . $row["amount"] . "</div>";
                        }
                    } else {
                        echo "Aucune demande de budget trouvée.";
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Department Modal -->
    <div id="department-modal" class="modal">
        <h2 class="text-2xl font-bold mb-4">Ajouter un département</h2>
        <form id="department-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Budget</label>
                <input type="number" name="budget" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideModal('department-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Annuler</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">Ajouter</button>
            </div>
        </form>
    </div>

    <!-- Personnel Modal -->
    <div id="personnel-modal" class="modal">
        <h2 class="text-2xl font-bold mb-4">Ajouter un membre du personnel</h2>
        <form id="personnel-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Département</label>
                <select name="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Rôle</label>
                <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

    <!-- Report Modal -->
    <div id="report-modal" class="modal">
        <h2 class="text-2xl font-bold mb-4">Créer un rapport</h2>
        <form id="report-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Contenu</label>
                <textarea name="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Département</label>
                <select name="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Classification</label>
                <select name="classification" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

    <!-- Sanction Modal -->
    <div id="sanction-modal" class="modal">
        <h2 class="text-2xl font-bold mb-4">Ajouter une sanction</h2>
        <form id="sanction-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Personnel</label>
                <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Raison</label>
                <textarea name="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sévérité</label>
                <select name="severity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

    <!-- Budget Request Modal -->
    <div id="budget-modal" class="modal">
        <h2 class="text-2xl font-bold mb-4">Demande de budget</h2>
        <form id="budget-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Département</label>
                <select name="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Montant</label>
                <input type="number" name="amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Motif</label>
                <textarea name="purpose" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideModal('budget-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Annuler</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-500 rounded-md hover:bg-emerald-600">Soumettre</button>
            </div>
        </form>
    </div>

    <!-- Notifications Container -->
    <div id="error-container" class="fixed bottom-4 right-4 z-50"></div>

    <!-- Scripts -->
    <script src="js/utils.js"></script>
    <script src="js/auth.js"></script>
    <script src="js/app.js"></script>
    <?php $conn->close(); ?>
</body>
</html>
