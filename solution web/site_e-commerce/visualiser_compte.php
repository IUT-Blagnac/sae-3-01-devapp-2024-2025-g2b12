<?php
require_once('./include/session.php');
require_once('./include/Connect.inc.php');
require_once('./include/header.php');
require_once('./include/menu.php');

// Récupérer les informations du compte
$idClient = $_SESSION['user_id'];
$query = $conn->prepare("SELECT * FROM CLIENT WHERE idClient = :idClient");
$query->execute(['idClient' => $idClient]);
$client = $query->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    //echo "Erreur : Informations du compte non trouvées.";
    header("location: ./connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once('./include/head.php'); ?>
    <style>
        .content {
            padding: 20px;
            margin-top: 20px;
            margin-left: 220px;
            /* Ajuster la marge gauche pour éviter le chevauchement */
            display: flex;
            justify-content: center;
            /* Centrer le contenu horizontalement */
            align-items: center;
            /* Centrer le contenu verticalement */
            height: calc(100vh - 40px);
            /* Ajuster la hauteur pour inclure le padding */
        }

        .card {
            margin-top: 20px;
            width: 100%;
            /* Prendre toute la largeur disponible */
            max-width: 600px;
            /* Limiter la largeur maximale */
            position: relative;
            /* Nécessaire pour positionner l'image avec `absolute` */
        }

        .card img {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 50px;
            height: auto;
        }
    </style>
</head>

<body>
    <?php require_once('./include/header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php require_once('./include/sidebar_compte.php'); ?>
            <main class="content">
                <div class="card">
                    <div class="card-body">
                        <a href="modifier_compte.php">
                            <img src="image/ModificationCompte.png" class="card-image-top" alt="Modification Compte">
                        </a>
                        <h2 class="card-title text-center">Mon compte</h2>
                        <p><strong>Nom :</strong> <?php echo htmlspecialchars($client['nomClient']); ?></p>
                        <p><strong>Prénom :</strong> <?php echo htmlspecialchars($client['prenomClient']); ?></p>
                        <p><strong>Email :</strong> <?php echo htmlspecialchars($client['emailClient']); ?></p>
                        <p><strong>Points Fidélités :</strong>
                            <?php echo htmlspecialchars($client['nbPointsClient']); ?></p>
                        <?php
                        if (isset($_GET['Pts']) && htmlentities($_GET['Pts']) == "oui") {
                            if (isset($_GET['error']) && $_GET['error'] === "insufficient_points") {
                                echo "<p class='error text-center'>Erreur : Vous avez sélectionné plus de points que ceux disponibles.</p>";
                            }
                            echo "<form method='post' action='visualiser_panier.php'>";
                            echo "<input type='number' name='NbPts' placeholder='Montant Réduction'>";
                            echo "<button class='btn btn-sm btn-sobre' type='submit'>Confirmez</button>";
                            echo "</form>";
                        }
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php require_once('./include/footer.php'); ?>
</body>

<style>
    .btn-sobre {
        background-color: #000;
        /* Couleur de fond noir */
        color: #fff;
        /* Couleur du texte blanc */
        border: none;
        /* Pas de bordure */
        padding: 5px 15px;
        /* Réduction de la taille du bouton */
        font-size: 14px;
        /* Police plus petite */
        border-radius: 5px;
        /* Coins arrondis */
        margin: 0 10px;
        /* Espacement latéral */
        cursor: pointer;
        /* Curseur en pointeur */
        transition: background-color 0.3s ease;
        /* Animation au survol */
    }

    .btn-sobre:hover {
        background-color: #333;
        /* Couleur plus claire au survol */
    }

    .error {
        color: red;
        font-size: 14px;
        margin-top: 10px;
    }
</style>

</html>