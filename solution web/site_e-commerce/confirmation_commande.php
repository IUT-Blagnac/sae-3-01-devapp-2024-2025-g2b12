<?php
session_start();
include './include/Connect.inc.php'; // Fichier pour la connexion à la base de données

// Partie HTML et HEAD
require_once('./include/head.php');
require_once('./include/header.php');
require_once('./include/menu.php');
?>

<div class="container-fluid flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="row w-100">
        <main class="container" style="max-width: 800px; margin: 0 auto; margin-top:8%; margin-bottom:5%;">
            <div class="confirmation-container">
                <div class="confirmation-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Commande confirmée</h2>
                <p>Votre commande a été enregistrée avec succès. Vous recevrez un email de confirmation sous peu.</p>
                <a href="index.php" class="btn btn-custom">Retour à l'accueil</a>
            </div>
        </main>
    </div>
</div>

<?php include_once('include/footer.php'); ?>

<style>
    .confirmation-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .confirmation-container h2 {
        color: #28a745;
        font-size: 2.5em;
        margin-bottom: 20px;
    }

    .confirmation-container p {
        font-size: 1.2em;
        color: #333;
        margin-bottom: 30px;
    }

    .confirmation-container .btn-custom {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        font-size: 1.1em;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .confirmation-container .btn-custom:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .confirmation-icon {
        font-size: 4em;
        color: #28a745;
        margin-bottom: 20px;
    }
</style>