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

                    // Regrouper les produits par nom
                    $produitsParNom = [];
                    foreach ($tousProduits as $produit) {
                        $produitsParNom[$produit['nomProduit']][] = $produit;
                    }

                    if (!empty($produitsParNom)) {
                        echo "<div class='row'>";
                        foreach ($produitsParNom as $nomProduit => $variantes) {
                            echo "<div class='col-md-12'>";
                            echo "<h3 class='product-title'>" . htmlspecialchars($nomProduit) . "</h3>";
                            echo "<div class='row'>";
                            foreach ($variantes as $produit) {
                                $imagePath = "image/produits/prod" . htmlspecialchars($produit['idProduit']) . ".png";
                                echo "<div class='col-md-4'>";
                                echo "<a href='detail_produit.php?idProduit=" . htmlspecialchars($produit['idProduit']) . "'>";
                                echo "<div class='card mb-4 shadow-sm' id='product-" . htmlspecialchars($produit['idProduit']) . "'>";
                                echo "<div class='card-img-top' style='height: 200px; background-image: url(\"$imagePath\"); background-size: cover; background-position: center;'></div>";
                                echo "<div class='card-body'>";
                                echo "<h5 class='card-title'>" . htmlspecialchars($produit['nomProduit']) . "</h5>";
                                echo "<p class='card-text'>" . htmlspecialchars($produit['prixProduit']) . " €</p>";
                                echo "<p class='card-text'>" . htmlspecialchars($produit['specProduit']) . "</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</a>";
                                echo "</div>";
                            }
                            echo "</div>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<h1 style='text-align: center;'>Catégorie non trouvée.</h1>";
                }
            }
            ?>
        </main>
    </div>
</div>

<script>
    function ajouterAuPanier(idProduit) {
        fetch('ajouter_au_panier.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `idProduit=${idProduit}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Produit ajouté au panier',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
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
                            window.location.href = 'connexion.php';
                        }
                    });
                } else {
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