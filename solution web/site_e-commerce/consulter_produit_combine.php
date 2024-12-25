<?php
session_start();
require_once('./include/head.php');
require_once("./include/Connect.inc.php");

// Partie HTML et affichage des produits

require_once('./include/header.php');
require_once('./include/menu.php');
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
    echo "<h1 style='text-align: center;'>Combinez pour économiser ! </h1><br><br>";
    $apparenter = $conn->prepare("SELECT idProduit1, idProduit2 FROM APPARENTER");
    $apparenter->execute();
    $produitApparenter = $apparenter->fetchAll();

    if(!empty($produitApparenter)){
        echo "<div class='row'>" ; 
    
        foreach($produitApparenter as $app){

        $produit1 = $conn->prepare("SELECT P.*, V.idVariete, V.prixVariete 
            FROM PRODUIT P 
            JOIN VARIETE V ON P.idProduit = V.idProduit 
            WHERE P.idProduit = :idProduit 
            AND V.idVariete = (SELECT MIN(V2.idVariete) FROM VARIETE V2 WHERE V2.idProduit = P.idProduit)");
        $produit1->execute(['idProduit' => $app['idProduit1']]);
        $prod1 = $produit1->fetch();

        $produit2 = $conn->prepare("SELECT P.*, V.idVariete, V.prixVariete 
            FROM PRODUIT P 
            JOIN VARIETE V ON P.idProduit = V.idProduit 
            WHERE P.idProduit = :idProduit 
            AND V.idVariete = (SELECT MIN(V2.idVariete) FROM VARIETE V2 WHERE V2.idProduit = P.idProduit)");
        $produit2->execute(['idProduit' => $app['idProduit2']]);
        $prod2 = $produit2->fetch();

        if($prod1 && $prod2){
            $imagePath1 = "image/produits/prod" . htmlspecialchars($prod1['idVariete']) . ".png";
            $imagePath2 = "image/produits/prod" . htmlspecialchars($prod2['idVariete']) . ".png";
            $titre = htmlspecialchars($prod1['nomProduit']) . " + " . htmlspecialchars($prod2['nomProduit']);
            //$prix = floatval($prod1['prixVariete']) + floatval($prod2['prixVariete']);
            //$prixReduction = $prix * 0.8 ;
            
            $detail_produit = "detail_produit_combine.php?idProduit1=" . htmlspecialchars($prod1['idProduit']) . 
                "&idProduit2=" . htmlspecialchars($prod2['idProduit']) . 
                "&idVariete1=" . htmlspecialchars($prod1['idVariete']) .
                "&idVariete2=" . htmlspecialchars($prod2['idVariete']) ; 
 
            echo "<div class='col-md-4'>";
            // echo "<a href='detail_produit_combine.php?idProduit1=" . htmlspecialchars($prod1['idProduit']) . "&idProduit2=" . htmlspecialchars($prod2['idProduit']) . "'>";
            echo "<a href='$detail_produit'>" ;
            echo "<div class='card mb-4 shadow-sm'>";
            
            // Images côte à côte
            echo "<div class='card-img-top' style='display: flex; height: 200px;'>";
            echo "<div style='flex: 1; background-image: url(\"$imagePath1\"); background-size: cover; background-position: center;'></div>";
            echo "<div style='flex: 1; background-image: url(\"$imagePath2\"); background-size: cover; background-position: center;'></div>";
            echo "</div>";

            // Titre combiné
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>$titre</h5>";
            
            // echo "<p class='card-text'>";
            // echo "<span style='text-decoration: line-through; color: red;'>" . number_format($prix, 2, ',', ' ') . " €</span> ";  // Ancien prix barré
            // echo "<span style='color: green;'>" . number_format($prixReduction, 2, ',', ' ') . " €</span>";  // Nouveau prix avec réduction
            // echo "</p>";

            echo "</div>";
            echo "</div>";
            echo "</a>";
            echo "</div>";
        }
    }
    echo "</div>";
    
    }  else {
        echo "<p style='text-align: center;'>Aucun produit combinés pour l'instant :). </p>";
    }

?>

<?php include_once('include/footer.php'); ?>
