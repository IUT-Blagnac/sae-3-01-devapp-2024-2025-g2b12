<?php
session_start();
include('include/Connect.inc.php'); // Assurez-vous d'inclure votre fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $variete_id = intval($_POST['variete_id']);
    $user_id = $_SESSION['user_id'];

    // Supprimer le produit du panier
    $delete = $conn->prepare("DELETE FROM ENREGISTRER WHERE idClient = :user_id AND idVariete = :variete_id");
    $delete->bindParam(':user_id', $user_id);
    $delete->bindParam(':variete_id', $variete_id);
    $delete->execute();
}

header('Location: visualiser_panier.php');
exit;
?>