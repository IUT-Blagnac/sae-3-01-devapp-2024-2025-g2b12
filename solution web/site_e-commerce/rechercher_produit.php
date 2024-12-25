<!-- partie html & head -->
<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>

<?php require_once('./include/Connect.inc.php'); ?>

<!-- Conteneur principal -->
<div class="container-fluid flex-grow-1">
    <div class="row">
        <!-- partie contenu principal -->
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="max-width: 800px; margin: 0 auto;">
            <div class="search-popup is-visible" style="margin-top: 20px;">
                <!-- Bouton Retour -->
                <a href="index.php" class="btn btn-secondary retour-btn">Retour</a>

                <!-- Formulaire de recherche -->
                <div class="search-popup-container">
                    <form role="search" method="post" class="search-form">
                        <input type="search" id="search-form" class="search-field" placeholder="Type and press enter"
                            value="<?= isset($_POST['s']) ? htmlentities($_POST['s']) : '' ?>" name="s">
                    </form>

                    <!-- Liste des catégories -->
                    <h5 class="cat-list-title">Browse Categories</h5>
                    <ul class="cat-list">
                        <li class="cat-list-item"><a href="consulter_categorie.php?pIdCateg=10">Combat</a></li>
                        <li class="cat-list-item"><a href="consulter_categorie.php?pIdCateg=20">Mobilité</a></li>
                        <li class="cat-list-item"><a href="consulter_categorie.php?pIdCateg=30">Infiltration</a></li>
                        <li class="cat-list-item"><a href="consulter_categorie.php?pIdCateg=40">Surveillance et enregistrement</a></li>
                        <li class="cat-list-item"><a href="consulter_categorie.php?pIdCateg=50">Evasion</a></li>
                        <li class="cat-list-item"><a href="consulter_categorie.php?pIdCateg=60">Technologie Médicale</a></li>
                    </ul>

                    <!-- Résultats de la recherche -->
                    <div class="results">
                        <?php
                        if (isset($_POST["s"])) {
                            $pdostat = $conn->prepare("SELECT * FROM PRODUIT WHERE nomProduit LIKE :nomP");
                            $pdostat->execute([':nomP' => '%' . htmlentities($_POST['s']) . '%']);
                            echo "<div class='row'>";
                            while ($produits = $pdostat->fetch(PDO::FETCH_ASSOC)) {
                                $imagePath = "image/produits/prod" . htmlspecialchars($produits["idProduit"]) . ".png";
                                echo "<div class='col-md-4'>";
                                echo "<a href='detail_produit.php?idProduit=" . htmlspecialchars($produits['idProduit']) . "'>";
                                echo "<div class='card mb-4 shadow-sm'>";
                                echo "<div class='card-img-top' style='height: 200px; background-image: url(\"$imagePath\");background-color: #f0f0f0;background-size: cover; background-position: center;'></div>";
                                echo "<div class='card-body'>";
                                echo "<p>" . htmlspecialchars($produits['nomProduit']) . "<p>";
                                echo "<p>" . htmlspecialchars($produits['prixVariete']) . "<p>";
                                echo "<p>" . htmlspecialchars($produits['specVariete']) . "<p>";
                                echo "</div>";
                                echo "</div>";
                                echo "<a>";
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    /* Positionnement du bouton Retour */
    .retour-btn {
        position: fixed;
        top: 10px;
        right: 20px;
        z-index: 1000;
    }

    .custom-swal-popup {
        border-radius: 10px;
        /* Arrondi des bords */
        padding: 20px;
        /* Assure une taille uniforme */
        margin: 0 !important;
    }

    /* Supprime tout padding ou marge sur le body */
    body,
    html {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        /* Empêche les défilements horizontaux */
    }

    /* Empêche la barre grise de s'afficher */
    .swal2-container {
        margin: 0 !important;
        padding: 0 !important;
    }


    /* Conteneur principal de la barre de recherche */
    .search-popup {
        margin-top: 50px;
        /* Barre légèrement remontée */
        background-color: white;
        padding: 20px;
        border-radius: 10px;
    }

    /* Formulaire de recherche */
    .search-form {
        display: flex;
        gap: 10px;
    }

    .search-field {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }



    /* Résultats produits */
    .results {
        margin-top: 20px;
        max-height: 400px;
        /* Limite la hauteur du conteneur pour un défilement */
        overflow-y: auto;
        /* Active le défilement vertical */
        border-top: 1px solid #ddd;
        padding-top: 20px;
    }

    .product {
        margin-bottom: 10px;
        padding: 10px;
        background: #f9f9f9;
        border: 1px solid #e5e5e5;
        border-radius: 5px;
    }
</style>