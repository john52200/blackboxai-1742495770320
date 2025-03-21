<?php
include 'db_connection.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Départements</title>
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


    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-100">
    <nav class="bg-gray-800 text-white p-4">
        <h1 class="text-xl font-bold">Gestion des Départements</h1>
    </nav>
    <div class="container mx-auto mt-5">
        <button onclick="showModal('department-modal')" class="bg-blue-500 text-white px-4 py-2 rounded-md">Ajouter un Département</button>
        <div id="departments-list" class="mt-4">
            <button onclick="showSubSection('economic-management')" class="bg-green-500 text-white px-4 py-2 rounded-md">Gestion Économique</button>
            <div id="economic-management" class="hidden">
                <!-- Economic management content goes here -->
            </div>
            <button onclick="showSubSection('scientific-experience')" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Expérience Scientifique</button>
            <div id="scientific-experience" class="hidden">
                <!-- Scientific experience content goes here -->
            </div>
            <button onclick="showSubSection('security-management')" class="bg-red-500 text-white px-4 py-2 rounded-md">Gestion Sécurité</button>
            <div id="security-management" class="hidden">
                <!-- Security management content goes here -->
            </div>
            <button onclick="showSubSection('salary-management')" class="bg-blue-500 text-white px-4 py-2 rounded-md">Gestion des Salaires</button>
            <div id="salary-management" class="hidden">
                <!-- Salary management content goes here -->
            </div>

            <!-- Les départements seront ajoutés ici dynamiquement -->
        </div>
    </div>

    <script src="js/utils.js"></script>
    <script src="js/app.js"></script>
    <script>
        function showSubSection(sectionId) {
            const sections = ['economic-management', 'scientific-experience', 'security-management', 'salary-management'];
            sections.forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');
        }
    </script>

    <!-- Department Modal -->
    <div id="department-modal" class="modal hidden">
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

    <script src="js/utils.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
