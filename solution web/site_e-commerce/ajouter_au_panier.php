<?php
session_start();
require_once("./include/Connect.inc.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour ajouter un produit au panier.']);
    exit();
}

if (isset($_POST['idProduit'])) {
    $idProduit = $_POST['idProduit'];
    $idClient = $_SESSION['user_id'];

    // Vérifiez si le produit est déjà dans le panier
    $checkPanier = $conn->prepare("SELECT qteEnregistree FROM ENREGISTRER WHERE idClient = :idClient AND idProduit = :idProduit");
    $checkPanier->execute(array('idClient' => $idClient, 'idProduit' => $idProduit));
    $result = $checkPanier->fetch(PDO::FETCH_ASSOC);

    if ($result !== false) {
        // Si le produit est déjà dans le panier, mettez à jour la quantité
        $newQuantity = $result['qteEnregistree'] + 1;
        $updatePanier = $conn->prepare("UPDATE ENREGISTRER SET qteEnregistree = :qteEnregistree WHERE idClient = :idClient AND idProduit = :idProduit");
        $updatePanier->execute(array(
            'qteEnregistree' => $newQuantity,
            'idClient' => $idClient,
            'idProduit' => $idProduit
        ));
    } else {
        // Sinon, insérez le produit dans le panier
        $ajoutPanier = $conn->prepare("INSERT INTO ENREGISTRER (idClient, idProduit, qteEnregistree) VALUES (:idClient, :idProduit, :qteEnregistree)");
        $ajoutPanier->execute(array(
            'idClient' => $idClient,
            'idProduit' => $idProduit,
            'qteEnregistree' => 1
        ));
    }

    echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout au panier.']);
}
?>