<?php
session_start();
include './include/Connect.inc.php'; // Fichier pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$idClient = $_SESSION['user_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $numRueAdresse = $_POST['numRueAdresse'];
        $rueAdresse = $_POST['rueAdresse'];
        $cdPostalAdresse = $_POST['cdPostalAdresse'];
        $villeAdresse = $_POST['villeAdresse'];
        $paysAdresse = $_POST['paysAdresse'];
        $modePaiement = $_POST['modePaiement'];

        // Enregistrer l'adresse
        $query = $conn->prepare("INSERT INTO ADRESSE (numRueAdresse, rueAdresse, cdPostalAdresse, villeAdresse, paysAdresse) VALUES (:numRueAdresse, :rueAdresse, :cdPostalAdresse, :villeAdresse, :paysAdresse)");
        $query->execute([
            'numRueAdresse' => $numRueAdresse,
            'rueAdresse' => $rueAdresse,
            'cdPostalAdresse' => $cdPostalAdresse,
            'villeAdresse' => $villeAdresse,
            'paysAdresse' => $paysAdresse
        ]);
        $idAdresse = $conn->lastInsertId();

        // Enregistrer la commande
        $query = $conn->prepare("INSERT INTO COMMANDE (dateCommande, modeLivraison, modePaiement, idClient, idAdresse) VALUES (NOW(), 'Domicile', :modePaiement, :idClient, :idAdresse)");
        $query->execute([
            'modePaiement' => $modePaiement,
            'idClient' => $idClient,
            'idAdresse' => $idAdresse
        ]);
        $idCommande = $conn->lastInsertId();

        // Enregistrer les détails de paiement
        if ($modePaiement === 'CB') {
            $numCB = $_POST['numCB'];
            $dateExpCB = $_POST['dateExpCB'];
            $titulaireCB = $_POST['titulaireCB'];
            $query = $conn->prepare("INSERT INTO CARTE_BANCAIRE (numCB, dateExpCB, titulaireCB) VALUES (:numCB, :dateExpCB, :titulaireCB)");
            $query->execute([
                'numCB' => $numCB,
                'dateExpCB' => $dateExpCB,
                'titulaireCB' => $titulaireCB
            ]);
        } elseif ($modePaiement === 'Paypal') {
            $numPaypal = $_POST['numPaypal'];
            $query = $conn->prepare("UPDATE COMMANDE SET numPaypal = :numPaypal WHERE idCommande = :idCommande");
            $query->execute([
                'numPaypal' => $numPaypal,
                'idCommande' => $idCommande
            ]);
        } elseif ($modePaiement === 'Virement') {
            $numVirement = $_POST['numVirement'];
            $query = $conn->prepare("UPDATE COMMANDE SET numVirement = :numVirement WHERE idCommande = :idCommande");
            $query->execute([
                'numVirement' => $numVirement,
                'idCommande' => $idCommande
            ]);
        }

        // Enregistrer les produits commandés
        $query = $conn->prepare("SELECT * FROM ENREGISTRER WHERE idClient = :idClient");
        $query->execute(['idClient' => $idClient]);
        $produits = $query->fetchAll();
        foreach ($produits as $produit) {
            $query = $conn->prepare("INSERT INTO COMMANDER (idCommande, idProduit, qteCommandee) VALUES (:idCommande, :idProduit, :qteCommandee)");
            $query->execute([
                'idCommande' => $idCommande,
                'idProduit' => $produit['idProduit'],
                'qteCommandee' => $produit['qteEnregistree']
            ]);
        }

        // Vider le panier
        $query = $conn->prepare("DELETE FROM ENREGISTRER WHERE idClient = :idClient");
        $query->execute(['idClient' => $idClient]);

        // Rediriger vers une page de confirmation
        header('Location: confirmation_commande.php');
        exit();
    }
} catch (Exception $e) {
    // Rediriger vers la page de commande avec un paramètre d'erreur
    $errorMessage = urlencode($e->getMessage());
    header("Location: commander.php?error=true&message=$errorMessage");
    exit();
}
?>