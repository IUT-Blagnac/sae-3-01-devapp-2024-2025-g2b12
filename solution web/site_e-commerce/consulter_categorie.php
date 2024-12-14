<?php
session_start();
require_once('./include/head.php');
require_once("./include/Connect.inc.php");

// Vérifiez si un produit doit être ajouté au panier
if (isset($_GET['ajouterAuPanier']) && isset($_GET['idProduit']) && isset($_GET['pIdCateg'])) {
    if (!isset($_SESSION['user_id'])) {
        // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        header("Location: connexion.php");
        exit();
    }

    $idProduit = $_GET['idProduit'];
    $idClient = $_SESSION['user_id'];

    // Vérifiez si le produit est déjà dans le panier
    $checkPanier = $conn->prepare("SELECT qteEnregistree FROM ENREGISTRER WHERE idClient = :idClient AND idProduit = :idProduit");
    $checkPanier->execute(array('idClient' => $idClient, 'idProduit' => $idProduit));
    $result = $checkPanier->fetch(PDO::FETCH_ASSOC);

    if ($result !== false) {
        // Si le produit est déjà dans le panier, mettez à jour la quantité
        $newQuantity = $result['qteEnregistree'] + 1;
        $updatePanier = $conn->prepare("UPDATE ENREGISTRER SET qteEnregistree = :qteEnregistree WHERE idClient = :idClient AND idProduit = :idProduit");
        $updatePanier->execute(array(
            'qteEnregistree' => $newQuantity,
            'idClient' => $idClient,
            'idProduit' => $idProduit
        ));
    } else {
        // Sinon, insérez le produit dans le panier
        $ajoutPanier = $conn->prepare("INSERT INTO ENREGISTRER (idClient, idProduit, qteEnregistree) VALUES (:idClient, :idProduit, :qteEnregistree)");
        $ajoutPanier->execute(array(
            'idClient' => $idClient,
            'idProduit' => $idProduit,
            'qteEnregistree' => 1
        ));
    }

    // Rediriger vers la page actuelle sans les paramètres GET pour éviter les répétitions
    header("Location: consulter_categorie.php?pIdCateg=" . $_GET['pIdCateg']);
    exit();
}

// Partie HTML et affichage des produits
require_once('./include/header.php');
require_once('./include/menu.php');
?>

<div class="container-fluid flex-grow-1">
    <div class="row">
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="max-width: 800px; margin: 0 auto;">
            <?php
            if (isset($_GET['pIdCateg'])) {
                $idCateg = $_GET['pIdCateg'];

                // Récupérer la catégorie actuelle
                $categorie = $conn->prepare("SELECT idCategorie, nomCategorie FROM CATEGORIE WHERE idCategorie = :idCategorie");
                $categorie->execute(['idCategorie' => $idCateg]);
                $categ = $categorie->fetch();

                if ($categ) {
                    echo "<h1 style='text-align: center;'>Produits de la catégorie : " . htmlspecialchars($categ['nomCategorie']) . "</h1><br><br>";

                    // Afficher les sous-catégories
                    $sousCategorie = $conn->prepare("SELECT idCategorie, nomCategorie FROM CATEGORIE WHERE idParent = :idCategorie");
                    $sousCategorie->execute(['idCategorie' => $idCateg]);
                    $SC = $sousCategorie->fetchAll();

                    if (!empty($SC)) {
                        echo "<ul>";
                        foreach ($SC as $sc) {
                            echo "<li><a href='consulter_categorie.php?pIdCateg=" . $sc['idCategorie'] . "'>" . htmlspecialchars($sc['nomCategorie']) . "</a></li>";
                        }
                        echo "</ul>";
                    }

                    // Récupérer les produits de la catégorie principale
                    $produitsPrincipaux = $conn->prepare("SELECT * FROM PRODUIT WHERE idCategorie = :idCategorie");
                    $produitsPrincipaux->execute(['idCategorie' => $idCateg]);
                    $prodPrincipaux = $produitsPrincipaux->fetchAll();

                    // Récupérer les produits des sous-catégories
                    $produitsSousCateg = $conn->prepare("
                        SELECT P.* 
                        FROM PRODUIT P
                        JOIN CATEGORIE C ON P.idCategorie = C.idCategorie
                        WHERE C.idParent = :idCategorie
                    ");
                    $produitsSousCateg->execute(['idCategorie' => $idCateg]);
                    $prodSousCateg = $produitsSousCateg->fetchAll();

                    // Fusionner les résultats
                    $tousProduits = array_merge($prodPrincipaux, $prodSousCateg);

                    if (!empty($tousProduits)) {
                        echo "<div class='row'>";
                        foreach ($tousProduits as $produit) {
                            echo "<div class='col-md-4'>";
                            echo "<div class='card mb-4 shadow-sm'>";
                            echo "<div class='card-img-top' style='height: 200px; background-color: #f0f0f0;'></div>"; // Placeholder pour l'image
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>" . htmlspecialchars($produit['nomProduit']) . "</h5>";
                            echo "<p class='card-text'>" . htmlspecialchars($produit['prixProduit']) . " €</p>";
                            echo "<p class='card-text'>" . htmlspecialchars($produit['specProduit']) . "</p>";
                            echo "<form action='consulter_categorie.php' method='get'>";
                            echo "<input type='hidden' name='pIdCateg' value='" . htmlspecialchars($idCateg) . "'>";
                            echo "<input type='hidden' name='idProduit' value='" . htmlspecialchars($produit['idProduit']) . "'>";
                            echo "<button type='submit' name='ajouterAuPanier' value='1' class='btn btn-primary'>Ajouter au panier</button>";
                            echo "</form>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                    // } else {
                    //     echo "<p>Aucun produit trouvé.</p>";
                    // }
                } else {
                    echo "<h1 style='text-align: center;'>Catégorie non trouvée.</h1>";
                }
            }
            ?>
        </main>
    </div>
</div>

<?php require_once('./include/footer.php'); ?>
