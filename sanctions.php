<?php
include 'db_connection.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Sanctions</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
<body class="min-h-screen bg-gray-100">
    <nav class="bg-gray-800 text-white p-4">
        <h1 class="text-xl font-bold">Gestion des Sanctions</h1>
    </nav>
    <div class="container mx-auto mt-5">
        <button onclick="showModal('sanction-modal')" class="bg-red-500 text-white px-4 py-2 rounded-md">Ajouter une Sanction</button>
        <div id="sanctions-list" class="mt-4">
            <!-- Les sanctions seront ajoutées ici dynamiquement -->
        </div>
    </div>

    <script src="js/utils.js"></script>
    <script src="js/app.js"></script>

    </div>

    <!-- Sanction Modal -->
    <div id="sanction-modal" class="modal hidden">
        <h2 class="text-2xl font-bold mb-4">Ajouter une Sanction</h2>
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

    <script src="js/utils.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
