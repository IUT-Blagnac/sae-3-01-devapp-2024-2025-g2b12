<?php
require_once('./include/session.php');
require_once('./include/Connect.inc.php');

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
</head>
<body>
    <?php require_once('./include/header.php'); ?>
    <?php require_once('./include/menu.php'); ?>

    <div class="container">
        <h2>Mon compte</h2>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($client['nomClient']); ?></p>
        <p><strong>Prénom :</strong> <?php echo htmlspecialchars($client['prenomClient']); ?></p>
        <p><strong>ID :</strong> <?php echo htmlspecialchars($client['idClient']); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($client['emailClient']); ?></p>
        <a href="deconnexion.php" class="btn btn-danger">Déconnexion</a>
    </div>

    <?php require_once('./include/footer.php'); ?>
</body>
</html>