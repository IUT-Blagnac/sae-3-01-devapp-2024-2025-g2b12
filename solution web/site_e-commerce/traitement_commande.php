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
        $lieuLivraison = $_POST['lieuLivraison'];
        $modePaiement = $_POST['modePaiement'];
        $idAdresseDeLivraison = null;
        $numCB = null;
        $numPaypal = null;
        $numVirement = null;

        if ($lieuLivraison === 'Domicile') {
            $numRueAdresse = $_POST['numRueAdresse'];
            $rueAdresse = $_POST['rueAdresse'];
            $cdPostalAdresse = $_POST['cdPostalAdresse'];
            $villeAdresse = $_POST['villeAdresse'];
            $paysAdresse = $_POST['paysAdresse'];

            // Vérifiez si l'adresse existe déjà
            $query = $conn->prepare("SELECT idAdresse FROM ADRESSE WHERE numRueAdresse = :numRueAdresse AND rueAdresse = :rueAdresse AND cdPostalAdresse = :cdPostalAdresse AND villeAdresse = :villeAdresse AND paysAdresse = :paysAdresse");
            $query->execute([
                'numRueAdresse' => $numRueAdresse,
                'rueAdresse' => $rueAdresse,
                'cdPostalAdresse' => $cdPostalAdresse,
                'villeAdresse' => $villeAdresse,
                'paysAdresse' => $paysAdresse
            ]);
            $adresse = $query->fetch();

            if ($adresse) {
                // Adresse existante
                $idAdresseDeLivraison = $adresse['idAdresse'];
            } else {
                // Insérer la nouvelle adresse
                $query = $conn->prepare("INSERT INTO ADRESSE (numRueAdresse, rueAdresse, cdPostalAdresse, villeAdresse, paysAdresse) VALUES (:numRueAdresse, :rueAdresse, :cdPostalAdresse, :villeAdresse, :paysAdresse)");
                $query->execute([
                    'numRueAdresse' => $numRueAdresse,
                    'rueAdresse' => $rueAdresse,
                    'cdPostalAdresse' => $cdPostalAdresse,
                    'villeAdresse' => $villeAdresse,
                    'paysAdresse' => $paysAdresse
                ]);
                $idAdresseDeLivraison = $conn->lastInsertId();
            }
        } elseif ($lieuLivraison === 'Relais') {
            $idAdresseDeLivraison = $_POST['idRelais'];
        }

        if ($modePaiement === 'CB') {
            $numCB = $_POST['numCB'];
            $dateExpCB = $_POST['dateExpCB'];
            $titulaireCB = $_POST['titulaireCB'];

            // Vérifiez si la carte bancaire existe déjà
            $query = $conn->prepare("SELECT numCB FROM CARTE_BANCAIRE WHERE numCB = :numCB");
            $query->execute(['numCB' => $numCB]);
            $carte = $query->fetch();

            if (!$carte) {
                // Insérer la nouvelle carte bancaire
                $query = $conn->prepare("INSERT INTO CARTE_BANCAIRE (numCB, dateExpCB, titulaireCB) VALUES (:numCB, :dateExpCB, :titulaireCB)");
                $query->execute([
                    'numCB' => $numCB,
                    'dateExpCB' => $dateExpCB,
                    'titulaireCB' => $titulaireCB
                ]);
            }
        } elseif ($modePaiement === 'Paypal') {
            $numPaypal = $_POST['numPaypal'];
        } elseif ($modePaiement === 'Virement') {
            $numVirement = $_POST['numVirement'];
        }

        // Vérifiez que l'adresse de livraison est définie
        if (empty($idAdresseDeLivraison)) {
            throw new Exception("L'adresse de livraison n'est pas définie.");
        }

        // Appel de la procédure stockée passerCommande
        $query = $conn->prepare("CALL passerCommande(:pIdClient, :pModeLivraison, :pIdAdresseDeLivraison, :pModePaiement, :pNumCB, :pNumPaypal, :pNumVirement)");
        $query->execute([
            'pIdClient' => $idClient,
            'pModeLivraison' => $lieuLivraison,
            'pIdAdresseDeLivraison' => $idAdresseDeLivraison,
            'pModePaiement' => $modePaiement,
            'pNumCB' => $numCB,
            'pNumPaypal' => $numPaypal,
            'pNumVirement' => $numVirement
        ]);

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
