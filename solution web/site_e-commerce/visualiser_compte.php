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
    echo "Erreur : Informations du compte non trouvées.";
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
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php require_once('./include/footer.php'); ?>
</body>

</html>