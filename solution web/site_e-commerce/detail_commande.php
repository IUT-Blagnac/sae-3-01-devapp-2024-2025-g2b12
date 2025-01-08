<?php
require_once('./include/head.php');
require_once('./include/session.php');
require_once("./include/Connect.inc.php");
require_once('./include/header.php');
require_once('./include/menu.php');
require_once('./include/sidebar_compte.php');   
?>

<div class="container-fluid flex-grow-1">
    <div class="row">
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="max-width: 800px; margin: 0 auto; margin-top:8%; margin-bottom:5%;">
            <style>
                .btn-custom {
                    background-color: rgba(136, 172, 223);
                    color: #fff;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
                }

                .btn-custom:hover {
                    background-color: rgb(67, 83, 107);
                    color: #fff;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                }

                .product-title {
                    margin-top: 20px;
                    margin-bottom: 20px;
                }

                .card {
                    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
                }

                .card:hover {
                    transform: scale(1.05);
                    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
                    background-color: rgba(136, 172, 223, 0.1);
                }

                .card-body {
                    transition: background-color 0.3s ease;
                }

                .card-body:hover {
                    background-color: rgba(136, 172, 223, 0.2);
                }
            </style>

<?php 
if (isset($_GET['pIdCommande'])) {
    $idCommande = $_GET['pIdCommande'];

    // Récupérer les informations de la commande
    $commande = $conn->prepare("SELECT * FROM COMMANDE C1, CLIENT C2, COMMANDER C3 WHERE C1.idClient = C2.idClient AND C1.idCommande = C3.idCommande AND C1.idCommande = :idCommande");
    $commande->execute(['idCommande' => $idCommande]);
    $cmd = $commande->fetchAll();

    // Récupérer les détails du produit commandé 
    $produit = $conn->prepare("SELECT * FROM COMMANDE C, PRODUIT P, VARIETE V, COMMANDER CMD WHERE C.idCommande = CMD.idCommande AND CMD.idVariete = V.idVariete AND V.idProduit = P.idProduit AND C.idCommande = :idCommande");
    $produit->execute(['idCommande' => $idCommande]);
    $prod = $produit->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once('./include/head.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #444;
        }

        main {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section h1 {
            font-size: 20px;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #555;
        }

        .section div {
            margin-bottom: 10px;
        }

        .result-container {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table-title,
        .order-row {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #f4f4f4;
            border-bottom: 1px solid #ddd;
        }

        .order-row {
            background-color: #fff;
            border-bottom: 1px solid #eee;
        }

        .table-title div,
        .order-row div {
            flex: 1;
            text-align: center;
            padding: 5px;
        }

        .order-row div img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .table-title {
            font-weight: bold;
            background-color: #f8f8f8;
            color: #444;
            border-bottom: 2px solid #ddd;
        }

        @media (max-width: 768px) {
            .table-title,
            .order-row {
                flex-direction: column;
                text-align: left;
            }

            .table-title div,
            .order-row div {
                flex: unset;
                padding: 10px 0;
            }
        }

        .info-row {
            display: flex;
            align-items: center; /* Aligne verticalement les éléments */
            margin-bottom: 10px; /* Espacement entre les lignes */
        }

        .info-row b {
            flex: 0 0 200px; /* Largeur fixe pour les titres */
            text-align: left; /* Aligne le texte des titres à gauche */
        }

        .info-row span {
            flex: 1; /* Permet au texte à droite de s'étendre */
            text-align: left; /* Aligne le texte à gauche également */
            padding-left: 20px; /* Ajoute un espacement entre le titre et la valeur */
        }
    </style>
</head>
<body>
<main>
    <div class="section">
    <h1>Informations de commande</h1>
        <?php if (!empty($cmd)) : ?>
                <?php $commande = $cmd[0];?>
                <div class="info-row"><?php echo "<b>Numéro de commande </b>" . htmlspecialchars($idCommande); ?></div>
                <div class="info-row"><?php echo "<b>Date de la commande </b>" . htmlspecialchars($commande['dateCommande']); ?></div>
                <div class="info-row"><?php echo "<b>Nom du client </b>" . htmlspecialchars($commande['prenomClient']) . " " . htmlspecialchars($commande['nomClient']) ; ?></div>
                <div class="info-row"><?php echo "<b>Email </b>" . htmlspecialchars($commande['emailClient']); ?></div>
                <div class="info-row"><?php echo "<b>Numéro de téléphone </b>" . htmlspecialchars($commande['telephoneClient']); ?></div>
                <div class="info-row"><?php echo "<b>Montant total </b>" . htmlspecialchars($commande['montantCommande']); ?> €</div>
        <?php endif; ?>
    </div>

    <div class="section">
    <h1>Informations sur la livraison</h1>
        <?php if (!empty($cmd)) : ?>
                <?php $commande = $cmd[0];?>
                <div class="info-row"><?php
                if ($commande['idAdresse'] == null) {
                    $pointRelais = $conn->prepare("SELECT * FROM COMMANDE C, POINT_RELAIS PR WHERE C.idRelais = PR.idRelais AND C.idCommande = :idCommande");
                    $pointRelais->execute(['idCommande' => $idCommande]);
                    $PR = $pointRelais->fetch();
                    if($PR){
                        echo "<b>Adresse de livraison </b>" . htmlspecialchars($PR['nomRelais']) . ", " . htmlspecialchars($PR['numRueRelais']) . " " . htmlspecialchars($PR['rueRelais']) . " " . htmlspecialchars($PR['cdPostalRelais']) . " " . htmlspecialchars($PR['villeRelais']) . " " . htmlspecialchars($PR['paysRelais']) ;
                    }
                } else if ($commande['idRelais'] == null) {
                    $domicile = $conn->prepare("SELECT * FROM COMMANDE C, ADRESSE A WHERE C.idAdresse = A.idAdresse AND C.idCommande = :idCommande");
                    $domicile->execute(['idCommande' => $idCommande]);
                    $DM = $domicile->fetch();
                    if($DM){
                        echo "<b>Adresse de livraison </b>" . htmlspecialchars($DM['numRueAdresse']) . " " . htmlspecialchars($DM['rueAdresse']) . " " . htmlspecialchars($DM['cdPostalAdresse']) . " " . htmlspecialchars($DM['villeAdresse']) . " " . htmlspecialchars($DM['paysAdresse']) ;
                    }
                }
                ?></div>
                <div class="info-row"><?php echo "<b>Mode de livraison </b>" . htmlspecialchars($commande['modeLivraison']); ?></div>
        <?php endif; ?>
    </div>

    <div class="section">
    <h1>Informations de paiement</h1>
        <?php if (!empty($cmd)) : ?>
            <?php $commande = $cmd[0];?>
                <div class="info-row"><?php echo "<b>Mode de paiement </b>" . htmlspecialchars($commande['modePaiement']); ?></div>
        <?php endif; ?>
    </div>

    <div class="section">
    <h1>Détails du produit</h1>
    <div class="result-container">
        <div class="table-title">
            <div>Photo du produit</div>
            <div>Nom du produit</div>
            <div>Qté</div>
            <div>Prix total</div>
        </div>
        <?php foreach ($prod as $produit): ?>
            <div class="order-row">
                <div>
                    <?php $imagePath = "image/produits/prod" . htmlspecialchars($produit['idVariete']) . ".png"; ?>
                    <img src="<?php echo $imagePath; ?>" alt="Produit">
                </div>
                <div><?php echo "<a href='detail_produit.php?idProduit=" . htmlspecialchars($produit['idProduit']) . "'>" . htmlspecialchars($produit['nomProduit']) . "</a>"; ?></div>
                <div><?php echo htmlspecialchars($produit['qteCommandee']); ?></div>
                <div><?php echo htmlspecialchars($produit['prixVariete']); ?> €</div>
            </div>
        <?php endforeach; ?>
    </div>

</main>
</body>
</html>