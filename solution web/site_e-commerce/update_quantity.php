<?php
session_start();
include('include/Connect.inc.php'); // Assurez-vous d'inclure votre fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $variete_id = intval($_POST['variete_id']);
    $user_id = $_SESSION['user_id'];

    // Vérifier si l'action est définie
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Récupérer la quantité actuelle
        $query = $conn->prepare("SELECT qteEnregistree FROM ENREGISTRER WHERE idClient = :user_id AND idVariete = :variete_id");
        $query->bindParam(':user_id', $user_id);
        $query->bindParam(':variete_id', $variete_id);
        $query->execute();
        $produit = $query->fetch();

        if ($produit) {
            $qteEnregistree = $produit['qteEnregistree'];

            if ($action == 'increase') {
                $qteEnregistree++;
            } elseif ($action == 'decrease' && $qteEnregistree > 0) {
                $qteEnregistree--;
            } elseif ($action == 'update' && isset($_POST['quantity'])) {
                $qteEnregistree = intval($_POST['quantity']);
            }

            if ($qteEnregistree > 0) {
                // Mettre à jour la quantité dans la base de données
                $update = $conn->prepare("UPDATE ENREGISTRER SET qteEnregistree = :qteEnregistree WHERE idClient = :user_id AND idVariete = :variete_id");
                $update->bindParam(':qteEnregistree', $qteEnregistree);
                $update->bindParam(':user_id', $user_id);
                $update->bindParam(':variete_id', $variete_id);
                $update->execute();
            } else {
                // Supprimer le produit du panier si la quantité est 0
                $delete = $conn->prepare("DELETE FROM ENREGISTRER WHERE idClient = :user_id AND idVariete = :variete_id");
                $delete->bindParam(':user_id', $user_id);
                $delete->bindParam(':variete_id', $variete_id);
                $delete->execute();
            }
        }
    }
}

header('Location: visualiser_panier.php');
exit;
?>