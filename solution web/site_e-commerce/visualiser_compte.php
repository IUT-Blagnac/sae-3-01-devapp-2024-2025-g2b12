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
        .sidebar {
            background-color: #f8f9fa;
            padding: 15px;
            border-right: 1px solid #dee2e6;
            height: 100vh; /* Prendre toute la hauteur de la vue */
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 200px; /* Réduire la largeur de la barre latérale */
            position: fixed; /* Fixer la barre latérale */
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
            text-align: center;
        }
        .sidebar a:hover {
            background-color: #e9ecef;
            color: #007bff;
        }
        .sidebar .logout {
            color: #fff;
            background-color: #dc3545; /* Rouge Bootstrap */
        }
        .sidebar .logout:hover {
            background-color: #c82333; /* Rouge plus foncé pour le hover */
        }
        .content {
            padding: 20px;
            margin-top: 20px;
            margin-left: 220px; /* Ajuster la marge gauche pour éviter le chevauchement */
            display: flex;
            justify-content: center; /* Centrer le contenu horizontalement */
            align-items: center; /* Centrer le contenu verticalement */
            height: calc(100vh - 40px); /* Ajuster la hauteur pour inclure le padding */
        }
        .card {
            margin-top: 20px;
            width: 100%; /* Prendre toute la largeur disponible */
            max-width: 600px; /* Limiter la largeur maximale */
        }
    </style>
</head>
<body>
    <?php require_once('./include/header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <nav class="sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Détail compte</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link logout" href="deconnexion.php">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">Mon compte</h2>
                        <p><strong>Nom :</strong> <?php echo htmlspecialchars($client['nomClient']); ?></p>
                        <p><strong>Prénom :</strong> <?php echo htmlspecialchars($client['prenomClient']); ?></p>
                        <p><strong>ID :</strong> <?php echo htmlspecialchars($client['idClient']); ?></p>
                        <p><strong>Email :</strong> <?php echo htmlspecialchars($client['emailClient']); ?></p>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php require_once('./include/footer.php'); ?>
</body>
</html>