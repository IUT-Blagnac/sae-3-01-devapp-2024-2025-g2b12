<?php
require_once('./include/session.php');
require_once('./include/Connect.inc.php');
require_once('./include/header.php');
require_once('./include/menu.php');
require_once('./include/sidebar_compte.php'); 

$idClient = $_SESSION['user_id'];

$commande = $conn->prepare("SELECT idCommande, dateCommande, montantCommande FROM COMMANDE WHERE idClient = :idClient");
$commande->execute(['idClient' => $idClient]);
$commandes = $commande->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once('./include/head.php'); ?>
    <div class="container-fluid flex-grow-1">
        <div class="row">     
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="max-width: 800px; margin: 0 auto; margin-top:8%; margin-bottom:5%;">
    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <style>
        h1 {
            text-align: center;
        }

        .result-container {
            margin: 20px auto;
            width: 80%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        .table-title, .order-row {
            display: flex;
            justify-content: space-between;
            text-align: center;
            background-color: #f8f8f8;
            padding: 10px 0;
        }

        .table-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }

        .order-row {
            padding: 8px 0;
        }

        .table-title div, .order-row div {
            flex: 1;
            padding: 0 10px;
        }
    </style>
</head>

<body>
<main>
    <h1>Mes commandes</h1>
    <div class="result-container">
        <div class="table-title">
            <div>Numéro de commande</div>
            <div>Date de la commande</div>
            <div>Montant total</div>
        </div>
        <?php foreach ($commandes as $commande): ?> 
            <div class="order-row">
                <div><?php echo "<a href='detail_commande.php?pIdCommande=" . $commande['idCommande'] . "'>" . htmlspecialchars($commande['idCommande']) . "</a>"; ?></div>
                <div><?php echo htmlspecialchars($commande['dateCommande']); ?></div>
                <div><?php echo htmlspecialchars($commande['montantCommande']); ?> €</div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>