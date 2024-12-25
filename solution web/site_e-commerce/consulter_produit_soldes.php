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
$req = $conn->prepare("SELECT * FROM PRODUIT P, VARIETE V, REGROUPER R1, REGROUPEMENT R2 WHERE P.idProduit = V.idProduit AND V.idVariete = R1.idVariete AND R1.idRegroupement = R2.idRegroupement AND R2.nomRegroupement = 'Soldes'");
$req->execute();

echo "<div class='row'>";
while($produit = $req->fetch()){
$imagePath = "image/produits/prod" . htmlspecialchars($produit['idVariete']) . ".png";
echo "<div class='col-md-4'>";
echo "<a href='detail_produit.php?idProduit=" . htmlspecialchars($produit['idProduit']) . "'>";
echo "<div class='card mb-4 shadow-sm' id='product-" . htmlspecialchars($produit['idProduit']) . "'>";
echo "<div class='card-img-top' style='height: 200px; background-image: url(\"$imagePath\"); background-size: cover; background-position: center;'></div>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>" . htmlspecialchars($produit['nomProduit']) . "</h5>";
echo "<p class='card-text'>" . htmlspecialchars($produit['prixVariete']) . " €</p>";
echo "</div>";
echo "</div>";
echo "</a>";
echo "</div>";
}
echo "</div>";

$req->closeCursor();
?>