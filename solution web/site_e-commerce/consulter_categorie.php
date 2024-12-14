<!-- partie html & head -->
<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>
<?php require_once('./include/menu.php'); ?>

<!-- Conteneur principal -->
<div class="container-fluid flex-grow-1">
    <div class="row">
        <!-- partie contenu principal -->
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="max-width: 800px; margin: 0 auto;">
            <?php 
                echo "<h1 style='text-align: center;'>Produits de la catégorie : Catégorie Exemple</h1></BR></BR>";

                // Produits fictifs pour l'aperçu
                $produits = [
                    [
                        'nomProduit' => 'Produit A',
                        'imageProduit' => '',
                        'prixProduit' => '15.00'
                    ],
                    [
                        'nomProduit' => 'Produit B',
                        'imageProduit' => '',
                        'prixProduit' => '25.00'
                    ],
                    [
                        'nomProduit' => 'Produit C',
                        'imageProduit' => '',
                        'prixProduit' => '35.00'
                    ]
                ];

                // Affichage des résultats
                if (!empty($produits)) {
                    echo "<div class='row'>";
                    foreach($produits as $prod) {
                        echo "<div class='col-md-4'>";
                        echo "<div class='card mb-4 shadow-sm'>";
                        echo "<div class='card-img-top' style='height: 200px; background-color: #f0f0f0;'></div>"; // Placeholder pour l'image
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>".$prod['nomProduit']."</h5>";
                        echo "<p class='card-text'>".$prod['prixProduit']." €</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>Aucun produit trouvé.</p>";
                }
            ?>
        </main>
    </div>
</div>