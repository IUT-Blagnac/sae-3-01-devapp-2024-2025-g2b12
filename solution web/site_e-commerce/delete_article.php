<?php
session_start();
require_once('./include/Connect.inc.php');

if (isset($_POST['produit_id']) && isset($_SESSION['user_id'])) {
    $produit_id = intval($_POST['produit_id']);
    $user_id = intval($_SESSION['user_id']);
    
    $pdostat = $conn->prepare("DELETE FROM ENREGISTRER WHERE idProduit = :produit_id AND idClient = :user_id");
    $pdostat->bindParam(':produit_id', $produit_id);
    $pdostat->bindParam(':user_id', $user_id);
    $pdostat->execute();
    
    echo "Article supprimé du panier.";
} else {
    echo "Erreur : impossible de supprimer l'article.";
}
?>