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
        $lieuLivraison = htmlentities($_POST['lieuLivraison']);
        $modePaiement = htmlentities($_POST['modePaiement']);
        $idAdresseDeLivraison = null;
        $numCB = null;
        $numPaypal = null;
        $numVirement = null;

        if ($lieuLivraison === 'Domicile') {
            $numRueAdresse = htmlentities($_POST['numRueAdresse']);
            $rueAdresse = htmlentities($_POST['rueAdresse']);
            $cdPostalAdresse = htmlentities($_POST['cdPostalAdresse']);
            $villeAdresse = htmlentities($_POST['villeAdresse']);
            $paysAdresse = htmlentities($_POST['paysAdresse']);

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
            $idAdresseDeLivraison = htmlentities($_POST['idRelais']);
        }

        if ($modePaiement === 'CB') {
            $numCB = htmlentities($_POST['numCB']);
            $dateExpCB = htmlentities($_POST['dateExpCB']);
            $titulaireCB = htmlentities($_POST['titulaireCB']);

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
            $numPaypal = htmlentities($_POST['numPaypal']);
        } elseif ($modePaiement === 'Virement') {
            $numVirement = htmlentities($_POST['numVirement']);
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

        $req = $conn->prepare("SELECT * FROM COMMANDE WHERE idClient = :pIdClient");
        $req->execute(["pIdClient" => $idClient]);
        $commandes = $req->fetchAll(PDO::FETCH_ASSOC);

        $LastCommande = ["idCommande" => 0, "montantCommande" => 0];
        $NbPointF = 0;

        foreach ($commandes as $commande) {
            if ($commande["idCommande"] > $LastCommande["idCommande"]) {
                $LastCommande = $commande;
            }
        }


        if ($LastCommande["idCommande"] > 0) {
            $req = $conn->prepare("SELECT nbPointsClient FROM CLIENT WHERE idClient = :pIdClient");
            $req->execute(["pIdClient" => $idClient]);
            $client = $req->fetch(PDO::FETCH_ASSOC);

            if ($client) {
                if (isset($_POST['Total']) && is_numeric($_POST['Total'])) {
                    $nouveauMontant = (float) $_POST['Total'];

                    $pointsSoustraits = ($LastCommande['montantCommande'] - $nouveauMontant) * 10;

                    $req = $conn->prepare("UPDATE CLIENT SET nbPointsClient = nbPointsClient - :pPointsSoustraits WHERE idClient = :pIdClient");
                    $req->execute([
                        "pPointsSoustraits" => $pointsSoustraits,
                        "pIdClient" => $idClient
                    ]);

                    $req = $conn->prepare("UPDATE COMMANDE SET montantCommande = :pMontantCommande WHERE idCommande = :pIdCommande");
                    $req->execute([
                        "pMontantCommande" => $nouveauMontant,
                        "pIdCommande" => $LastCommande["idCommande"]
                    ]);

                    $LastCommande["montantCommande"] = $nouveauMontant;
                }

                $newPoints = $client["nbPointsClient"] + $LastCommande["montantCommande"];

                $req = $conn->prepare("UPDATE CLIENT SET nbPointsClient = :pNewPoints WHERE idClient = :pIdClient");
                $req->execute([
                    "pNewPoints" => $newPoints,
                    "pIdClient" => $idClient
                ]);
            }
        }



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