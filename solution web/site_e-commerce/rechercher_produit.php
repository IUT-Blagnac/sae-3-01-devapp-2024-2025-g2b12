<?php require_once('./include/Connect.inc.php'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">

<style>
    a {
        text-decoration: none !important;
    }
</style>

<!-- Barre de recherche fixe -->
<div class="search-box">
    <img src="image/WOOHP.png" class="logo" width="100px" alt="Logo">
    <form role="search" id="search-form" method="post" class="search-form">
        <input name="s" type="search" id="search-input" placeholder="Rechercher un produit..."
            value="<?= isset($_POST['s']) ? htmlentities($_POST['s']) : '' ?>" class="search-field">
        <select name="sort" id="sort-dropdown" class="sort-dropdown">
            <option value="Trier">Trier par</option>
            <option value="asc" <?= (isset($_POST['sort']) && $_POST['sort'] === 'asc') ? 'selected' : '' ?>>Prix croissant
            </option>
            <option value="desc" <?= (isset($_POST['sort']) && $_POST['sort'] === 'desc') ? 'selected' : '' ?>>Prix
                décroissant</option>
        </select>
        <input type="number" name="min_price" placeholder="Prix min"
            value="<?= isset($_POST['min_price']) ? htmlentities($_POST['min_price']) : '' ?>" class="sort-dropdown"
            min="0">
        <input type="number" name="max_price" placeholder="Prix max"
            value="<?= isset($_POST['max_price']) ? htmlentities($_POST['max_price']) : '' ?>" class="sort-dropdown"
            min="0">
        <button type="submit" class="btn-search">Rechercher</button>
        <a href="index.php" class="back-button">Retour</a>
    </form>
</div>

<div class="container-fluid flex-grow-1">
    <div class="row">
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="max-width: 1200px; margin: 0 auto;">

            <div class="search-popup-container">
                <center>
                    <h5 class="categories-title">Parcourir les catégories</h5>
                </center>
                <ul class="categories-list">
                    <li><a href="consulter_categorie.php?pIdCateg=10">Combat</a></li>
                    <li><a href="consulter_categorie.php?pIdCateg=20">Mobilité</a></li>
                    <li><a href="consulter_categorie.php?pIdCateg=30">Infiltration</a></li>
                    <li><a href="consulter_categorie.php?pIdCateg=40">Surveillance</a></li>
                    <li><a href="consulter_categorie.php?pIdCateg=50">Évasion</a></li>
                    <li><a href="consulter_categorie.php?pIdCateg=60">Médicale</a></li>
                </ul>

                <div class="results">
                    <?php
                    if (isset($_POST["s"])) {

                        $sql = "
                            SELECT * 
                            FROM PRODUIT P
                            , VARIETE V WHERE P.idProduit = V.idProduit
                            AND nomProduit LIKE :nomP
                        ";

                        $params = [':nomP' => '%' . htmlspecialchars($_POST['s']) . '%'];
                        if (!empty($_POST['min_price'])) {
                            $sql .= " AND V.prixVariete >= :minPrice";
                            $params[':minPrice'] = (float) $_POST['min_price'];
                        }
                        if (!empty($_POST['max_price'])) {
                            $sql .= " AND V.prixVariete <= :maxPrice";
                            $params[':maxPrice'] = (float) $_POST['max_price'];
                        }

                        if (isset($_POST['sort']) && $_POST['sort'] === 'asc') {
                            $sql .= " ORDER BY V.prixVariete ASC";
                        } elseif (isset($_POST['sort']) && $_POST['sort'] === 'desc') {
                            $sql .= " ORDER BY V.prixVariete DESC";
                        } else {
                            $sql .= " ORDER BY P.idProduit";
                        }

                        $req = $conn->prepare($sql);
                        $req->execute($params);

                        $produits = $req->fetchAll(PDO::FETCH_ASSOC);

                        if ($produits) {
                            echo "<div class='row'>";
                            foreach ($produits as $produit) {
                                $imagePath = "image/produits/prod" . htmlspecialchars($produit["idVariete"]) . ".png";
                                echo "<div class='col-md-3'>";
                                echo "<a href='detail_produit.php?idProduit=" . htmlspecialchars($produit['idProduit']) . "'>";
                                echo "<div class='card mb-4 shadow-sm' id='product-" . htmlspecialchars($produit['idVariete']) . "'>";
                                echo "<div class='card-img-top' style='height: 200px; background-image: url(\"$imagePath\"); background-size: cover; background-position: center;'></div>";
                                echo "<div class='card-body'>";
                                echo "<h5 class='card-title'>" . htmlspecialchars($produit['nomProduit']) . "</h5>";
                                echo "<p><class='card-text'>" . htmlspecialchars($produit['specVariete']) . "</p>";
                                echo "<p><class='card-text'>" . htmlspecialchars($produit['prixVariete']) . " €</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</a>";
                                echo "</div>";
                            }
                            echo "</div>";
                        } else {
                            echo "<center><p class='no-results'>Aucun produit ne correspond à votre recherche.</p></center>";
                        }
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>
</div>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
    color: #333;
}

.search-box {
    display: flex;
    flex-wrap: wrap; /* Permet de passer les éléments à la ligne si nécessaire */
    justify-content: center;
    align-items: center;
    padding: 15px;
    background-color: white;
    border-bottom: 1px solid #ddd;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.logo {
    height: 50px;
    margin-right: 20px;
}

.search-form {
    display: flex;
    flex-wrap: wrap; /* Permet aux éléments de s'adapter sur plusieurs lignes */
    align-items: center;
    gap: 10px; /* Espacement entre les éléments */
    width: 100%;
    max-width: 1200px;
    justify-content: center;
}

.search-field {
    flex: 1;
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%; /* Prend toute la largeur disponible */
    max-width: 400px; /* Limite la largeur */
}

.sort-dropdown,
.btn-search,
.back-button {
    padding: 10px 15px;
    font-size: 1em;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.btn-search,
.back-button {
    background-color: rgba(136, 172, 223);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.btn-search:hover,
.back-button:hover {
    background-color: rgb(67, 83, 107);
}

.categories-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 15px 0;
}

.categories-list li {
    margin: 5px;
}

.categories-list a {
    display: inline-block;
    padding: 10px 20px;
    color: #fff;
    background-color: rgba(136, 172, 223);
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.categories-list a:hover {
    background-color: rgb(67, 83, 107);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.results {
    margin-top: 20px;
}

.no-results {
    color: #999;
    font-size: 1.2em;
}

.row {
    display: flex;
    flex-wrap: wrap; /* Permet aux cartes de s'adapter à la taille de l'écran */
    gap: 20px;
    justify-content: center;
}

.card {
    width: calc(33.333% - 20px);
    min-width: 250px;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.card-body {
    padding: 15px;
}

.card-title {
    font-size: 1.2em;
    color: #333;
    margin-bottom: 10px;
}

.card-text {
    font-size: 1em;
    color: #666;
}

@media (max-width: 768px) {
    /* Adaptations pour tablettes et petits écrans */
    .search-box {
        flex-direction: column; /* Affiche les éléments les uns sous les autres */
        align-items: center;
    }

    .search-form {
        width: 100%;
        max-width: 600px;
    }

    .search-field {
        width: 100%; /* Le champ de recherche prend toute la largeur */
    }

    .categories-list {
        flex-direction: column;
        align-items: center;
    }

    .card {
        width: calc(50% - 20px); /* Deux colonnes pour les tablettes */
    }
}

@media (max-width: 480px) {
    /* Adaptations pour téléphones */
    .search-box {
        flex-direction: column;
        padding: 10px;
    }

    .search-field {
        width: 100%;
        max-width: none;
    }

    .card {
        width: 100%; /* Une seule colonne pour les petits écrans */
    }

    .categories-list {
        align-items: center;
    }

    .categories-list a {
        width: 100%;
        text-align: center;
    }
}

</style>