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

                .subcategory-button {
                    display: inline-block;
                    margin: 5px;
                    padding: 10px 15px;
                    font-size: 16px;
                    color: #fff;
                    background-color: rgba(136, 172, 223);
                    border: none;
                    border-radius: 5px;
                    text-decoration: none;
                    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
                }

                .subcategory-button:hover {
                    background-color: rgb(67, 83, 107);
                    transform: translateY(-2px);
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                }

                .sort-dropdown {
                    padding: 10px 15px;
                    font-size: 1em;
                    border-radius: 5px;
                    border: 1px solid #ccc;
                    margin-top: 20px;
                    /* Ajout d'un espace au-dessus du bouton de tri */
                }

                .title-and-sort {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 20px;
                }

                .sort-container {
                    display: flex;
                    justify-content: flex-end;
                    margin-bottom: 20px; /* Ajout d'un espace en dessous du bouton de tri */
                }
            </style>

            <?php
            if (isset($_GET['pIdCateg']) || isset($_GET['idRegroupement'])) {
                $order = isset($_GET['order']) ? $_GET['order'] : '';

                if (isset($_GET['pIdCateg'])) {
                    $idCateg = $_GET['pIdCateg'];

                    // Récupérer la catégorie actuelle
                    $categorie = $conn->prepare("SELECT idCategorie, nomCategorie FROM CATEGORIE WHERE idCategorie = :idCategorie");
                    $categorie->execute(['idCategorie' => $idCateg]);
                    $categ = $categorie->fetch();

                    if ($categ) {
                        echo "<div class='title-and-sort'>";
                        echo "<h1>Produits de la catégorie : " . htmlspecialchars($categ['nomCategorie']) . "</h1>";
                        echo "</div>";

                        // Afficher les sous-catégories
                        $sousCategorie = $conn->prepare("SELECT idCategorie, nomCategorie FROM CATEGORIE WHERE idParent = :idCategorie");
                        $sousCategorie->execute(['idCategorie' => $idCateg]);
                        $SC = $sousCategorie->fetchAll();

                        if (!empty($SC)) {
                            echo "<p style='text-align: center;'>Sous-catégorie</p>";
                            echo "<div style='text-align: center; margin-bottom: 20px;'>";
                            foreach ($SC as $sc) {
                                echo "<a class='subcategory-button' href='consulter_categorie.php?pIdCateg=" . htmlspecialchars($sc['idCategorie']) . "'>" . htmlspecialchars($sc['nomCategorie']) . "</a>";
                            }
                            echo "</div>";
                        }

                        // Récupérer les produits de la catégorie principale
                        $produitsPrincipauxQuery = "
                            SELECT P.*, V.idVariete, V.prixVariete 
                            FROM PRODUIT P
                            JOIN VARIETE V ON P.idProduit = V.idProduit
                            WHERE P.idCategorie = :idCategorie
                            AND V.idVariete = (SELECT MIN(V2.idVariete) FROM VARIETE V2 WHERE V2.idProduit = P.idProduit)
                        ";
                        if ($order && $order !== 'none') {
                            $produitsPrincipauxQuery .= " ORDER BY V.prixVariete $order";
                        }
                        $produitsPrincipaux = $conn->prepare($produitsPrincipauxQuery);
                        $produitsPrincipaux->execute(['idCategorie' => $idCateg]);
                        $prodPrincipaux = $produitsPrincipaux->fetchAll();

                        // Récupérer les produits des sous-catégories
                        $produitsSousCategQuery = "
                            SELECT P.*, V.idVariete, V.prixVariete 
                            FROM PRODUIT P
                            JOIN CATEGORIE C ON P.idCategorie = C.idCategorie
                            JOIN VARIETE V ON P.idProduit = V.idProduit
                            WHERE C.idParent = :idCategorie
                            AND V.idVariete = (SELECT MIN(V2.idVariete) FROM VARIETE V2 WHERE V2.idProduit = P.idProduit)
                        ";
                        if ($order && $order !== 'none') {
                            $produitsSousCategQuery .= " ORDER BY V.prixVariete $order";
                        }
                        $produitsSousCateg = $conn->prepare($produitsSousCategQuery);
                        $produitsSousCateg->execute(['idCategorie' => $idCateg]);
                        $prodSousCateg = $produitsSousCateg->fetchAll();

                        // Fusionner les résultats
                        $tousProduits = array_merge($prodPrincipaux, $prodSousCateg);
                    } else {
                        echo "<h1 style='text-align: center;'>Catégorie non trouvée.</h1>";
                    }
                } elseif (isset($_GET['idRegroupement'])) {
                    $idRegroupement = $_GET['idRegroupement'];

                    // Récupérer le nom du regroupement
                    $regroupement = $conn->prepare("SELECT nomRegroupement FROM REGROUPEMENT WHERE idRegroupement = :idRegroupement");
                    $regroupement->execute(['idRegroupement' => $idRegroupement]);
                    $regroup = $regroupement->fetch();

                    if ($regroup) {
                        echo "<div class='title-and-sort'>";
                        echo "<h1>Produits du regroupement : " . htmlspecialchars($regroup['nomRegroupement']) . "</h1>";
                        echo "</div>";

                        // Récupérer les produits du regroupement
                        $produitsRegroupementQuery = "
                            SELECT P.*, V.idVariete, V.prixVariete 
                            FROM PRODUIT P
                            JOIN VARIETE V ON P.idProduit = V.idProduit
                            JOIN REGROUPER R ON V.idVariete = R.idVariete
                            WHERE R.idRegroupement = :idRegroupement
                            AND V.idVariete = (SELECT MIN(V2.idVariete) FROM VARIETE V2 WHERE V2.idProduit = P.idProduit)
                        ";
                        if ($order && $order !== 'none') {
                            $produitsRegroupementQuery .= " ORDER BY V.prixVariete $order";
                        }
                        $produitsRegroupement = $conn->prepare($produitsRegroupementQuery);
                        $produitsRegroupement->execute(['idRegroupement' => $idRegroupement]);
                        $tousProduits = $produitsRegroupement->fetchAll();
                    } else {
                        echo "<h1 style='text-align: center;'>Regroupement non trouvé.</h1>";
                    }
                }

                echo "<div class='sort-container'>";
                echo "<form method='GET'>";
                if (isset($_GET['pIdCateg'])) {
                    echo "<input type='hidden' name='pIdCateg' value='" . htmlspecialchars($_GET['pIdCateg']) . "'>";
                } elseif (isset($_GET['idRegroupement'])) {
                    echo "<input type='hidden' name='idRegroupement' value='" . htmlspecialchars($_GET['idRegroupement']) . "'>";
                }
                echo "<select name='order' id='order' class='sort-dropdown' onchange='this.form.submit()'>";
                echo "<option value='' disabled" . ($order == '' ? ' selected' : '') . ">Trier par :</option>";
                echo "<option value='none'" . ($order == 'none' ? ' selected' : '') . ">Aucun</option>";
                echo "<option value='ASC'" . ($order == 'ASC' ? ' selected' : '') . ">Prix croissant</option>";
                echo "<option value='DESC'" . ($order == 'DESC' ? ' selected' : '') . ">Prix décroissant</option>";
                echo "</select>";
                echo "</form>";
                echo "</div>";

                if (!empty($tousProduits)) {
                    echo "<div class='row'>";
                    foreach ($tousProduits as $produit) {
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
                }
            }
            ?>
        </main>
    </div>
</div>
<hr style="border: 0; height: 2px; background: linear-gradient(to right, #ffffff, #000000, #ffffff); margin: 20px 0;"><br><br><br>

<script>
    // Fonction pour ajouter un produit au panier
    function ajouterAuPanier(idProduit) {
        // Envoi d'une requête POST pour ajouter le produit au panier
        fetch('ajouter_au_panier.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `idProduit=${idProduit}`
            })
            .then(response => response.json()) // Conversion de la réponse en JSON
            .then(data => {
                if (data.success) {
                    // Affichage d'une alerte de succès si le produit a été ajouté avec succès
                    Swal.fire({
                        icon: 'success',
                        title: 'Produit ajouté au panier',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // Si l'utilisateur doit être connecté pour ajouter un produit au panier
                    if (data.message === 'Vous devez être connecté pour ajouter un produit au panier.') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Non connecté',
                            text: data.message,
                            showCancelButton: true,
                            confirmButtonText: 'Se connecter',
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirection vers la page de connexion si l'utilisateur confirme
                                window.location.href = 'connexion.php';
                            }
                        });
                    } else {
                        // Affichage d'une alerte d'erreur pour tout autre message d'erreur
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: data.message,
                        });
                    }
                }
            });
    }
</script>

<?php include_once('include/footer.php'); ?>