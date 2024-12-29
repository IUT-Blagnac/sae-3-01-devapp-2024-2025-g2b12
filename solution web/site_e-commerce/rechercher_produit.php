<?php require_once('./include/Connect.inc.php'); ?>
<?php require_once('./include/head.php'); ?>

<!-- Barre de recherche fixe -->
<div class="search-box">
    <form role="search" id="search-form" method="post" class="search-form">
        <input name="s" type="search" id="search-input" placeholder="Rechercher un produit..."
            value="<?= isset($_POST['s']) ? htmlentities($_POST['s']) : '' ?>" class="search-field">
        <select name="sort" id="sort-dropdown" class="sort-dropdown">
            <option value="">Trier par</option>
            <option value="asc" <?= (isset($_POST['sort']) && $_POST['sort'] === 'asc') ? 'selected' : '' ?>>Prix croissant</option>
            <option value="desc" <?= (isset($_POST['sort']) && $_POST['sort'] === 'desc') ? 'selected' : '' ?>>Prix décroissant</option>
        </select>
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
                    <li><a href="consulter_categorie.php?pIdCateg=60">Technologie Médicale</a></li>
                </ul>

                <div class="results">
                    <?php
                    if (isset($_POST["s"])) {
                        $sortOrder = (isset($_POST['sort']) && $_POST['sort'] === 'desc') ? 'DESC' : 'ASC';
                        $req = $conn->prepare("
                            SELECT * 
                            FROM PRODUIT P
                            , VARIETE V WHERE P.idProduit = V.idProduit
                            AND nomProduit LIKE :nomP
                            ORDER BY V.prixVariete $sortOrder
                        ");
                        $req->execute([':nomP' => '%' . htmlspecialchars($_POST['s']) . '%']);

                        $produits = $req->fetchAll(PDO::FETCH_ASSOC);
                        if ($produits) {
                            echo "<div class='row'>";
                            foreach ($produits as $produit) {
                                $imagePath = "image/produits/prod" . htmlspecialchars($produit["idVariete"]) . ".png";
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
    justify-content: center;
    align-items: center;
    padding: 15px;
    background-color: white;
    border-bottom: 1px solid #ddd;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.search-form {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    gap: 10px;
    width: 100%;
    max-width: 800px;
}

.search-field {
    flex: 1;
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.sort-dropdown {
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn-search {
    padding: 10px 20px;
    font-size: 1em;
    color: #fff;
    background-color: #0077b6;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-search:hover {
    background-color: #005f8d;
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
    background-color: #0077b6;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.categories-list a:hover {
    background-color: #005f8d;
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
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.card {
    width: calc(33% - 20px);
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
</style>