<!-- partie html & head -->
<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>
<!-- <?php require_once('./include/menu.php'); ?>  -->

<!-- Conteneur principal -->
<div class="container-fluid flex-grow-1">
    <div class="row">
        <!-- partie contenu principal -->
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="max-width: 800px; margin: 0 auto;">
            <?php 
            require_once("./include/Connect.inc.php");

            if(isset($_GET['pIdCateg'])){
                
                $idCateg = $_GET['pIdCateg'];
                $categorie = $conn->prepare("SELECT idCategorie, nomCategorie FROM CATEGORIE WHERE idCategorie = :idCategorie");
                $categorie-> execute(['idCategorie' => $idCateg]);
                $categ = $categorie->fetch();
                echo "<h1 style='text-align: center;'>Produits de la catégorie : " . htmlspecialchars($categ['nomCategorie']) . "</h1></BR></BR>";
                
                $sousCategorie = $conn->prepare("SELECT idCategorie, nomCategorie FROM CATEGORIE WHERE idParent = :idCategorie");
                $sousCategorie->execute(['idCategorie' => $idCateg]) ;
                $SC = $sousCategorie->fetchAll(); 
                
                echo "<ul>" ; 
                foreach($SC as $sc) {
                     echo "<li> <a href='consulter_categorie.php?pIdCateg=" . $sc['idCategorie']."'>" .$sc['nomCategorie']. "</a></li>" ;
                }
                echo "</ul>" ; 

             
                $produit = $conn->prepare("SELECT * FROM PRODUIT P WHERE P.idCategorie = :idCategorie");
                $produit->execute(['idCategorie'=>$idCateg]);
                $prod = $produit->fetchAll();

            // Affichage des résultats
            if (!empty($prod)) {
                echo "<div class='row'>";
                foreach($prod as $produit) {
                    echo "<div class='col-md-4'>";
                    echo "<div class='card mb-4 shadow-sm'>";
                    echo "<div class='card-img-top' style='height: 200px; background-color: #f0f0f0;'></div>"; // Placeholder pour l'image
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>".$produit['nomProduit']."</h5>";
                    echo "<p class='card-text'>".$produit['prixProduit']." €</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            } 

               if($categ){
                
                     $produits = $conn->prepare(
                         "SELECT * 
                         FROM PRODUIT P, CATEGORIE C1, CATEGORIE C2
                         WHERE P.idCategorie = C1.idCategorie
                         AND C2.idCategorie = C1.idParent
                         AND C2.idCategorie = :idCategorie");
                     $produits->execute(array('idCategorie' => $idCateg));
                     $produits->execute();
                     $prod = $produits->fetchAll();

                // Affichage des résultats
                if (!empty($prod)) {
                    echo "<div class='row'>";
                    foreach($prod as $produit) {
                        echo "<div class='col-md-4'>";
                        echo "<div class='card mb-4 shadow-sm'>";
                        echo "<div class='card-img-top' style='height: 200px; background-color: #f0f0f0;'></div>"; // Placeholder pour l'image
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>".$produit['nomProduit']."</h5>";
                        echo "<p class='card-text'>".$produit['prixProduit']." €</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>Aucun produit trouvé.</p>";
                }
            }
        }
            ?>
        </main>
    </div>
</div>