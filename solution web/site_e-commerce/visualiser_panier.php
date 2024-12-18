<!-- Partie HTML et HEAD -->
<?php require_once('./include/head.php'); ?>

<!-- Partie récupération dans la bd -->
<?php
session_start();
include './include/Connect.inc.php'; // Fichier pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les produits du panier du client
$sql = "SELECT p.idProduit, p.nomProduit, p.prixProduit, p.specProduit, e.qteEnregistree 
        FROM ENREGISTRER e 
        JOIN PRODUIT p ON e.idProduit = p.idProduit 
        WHERE e.idClient = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculer le prix total
$total = 0;
foreach ($produits as $prod) {
    $total += $prod['prixProduit'] * $prod['qteEnregistree'];
}
?>

<!-- Partie BODY -->
<?php require_once('./include/header.php'); ?>
<?php require_once("./include/menu.php"); ?>

<!-- Conteneur principal -->
<div class="container-fluid flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="row w-100">
        <!-- partie contenu principal -->
        <main class="container" style="margin-top:8%; margin-bottom:5%;">
            <h2 class="text-center">Mon Panier</h2>
            <style>
                .btn-custom {
                    padding: 8px 15px;
                    /* Augmenter légèrement le padding pour rendre les boutons plus grands */
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
                    font-size: 0.9em;
                    /* Augmenter légèrement la taille de la police */
                    margin: 0 5px;
                    /* Ajouter une marge pour espacer les boutons */
                }

                .btn-custom-light {
                    background-color: rgb(122, 122, 122);
                    color: #fff;
                }

                .btn-custom-light:hover {
                    background-color: rgb(136, 172, 223, 255);
                    color: #fff;
                    transform: translateY(-1px);
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                }

                .btn-custom-dark {
                    background-color: rgb(39, 39, 39);
                    color: #fff;
                }

                .btn-custom-dark:hover {
                    background-color: rgba(136, 172, 223, 255);
                    color: #fff;
                    transform: translateY(-1px);
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                }

                .btn-danger {
                    background-color: red;
                    color: white;
                }

                .btn-danger:hover {
                    background-color: darkred;
                    transform: translateY(-1px);
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                }

                .product-image-placeholder {
                    width: 100%;
                    height: 200px;
                    background-color: #f0f0f0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #ccc;
                    font-size: 1.5em;
                }

                .product-image {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .product-image-placeholder::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    width: 5px;
                }

                .product-container {
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    height: 100%;
                }

                .button-container {
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                }
            </style>
            <?php
            if (!empty($produits)) {
                echo "<div class='col-md-8 mx-auto'>";
                echo "<div class='card mb-4 shadow-sm'>";
                echo "<div class='card-body'>";
                foreach ($produits as $prod) {
                    $imagePath = "image/produits/prod" . htmlspecialchars($prod['idProduit']) . ".png"; // Chemin de l'image
                    echo "<div class='row mb-3'>";
                    echo "<div class='col-md-4'>";
                    echo "<div class='product-image-placeholder'>";
                    echo "<img src='" . $imagePath . "' alt='Image du produit' class='product-image'>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='col-md-8 product-container'>";
                    echo "<div>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($prod['nomProduit']) . "</h5>";
                    echo "<p class='card-text'>" . htmlspecialchars($prod['prixProduit']) . " €</p>";
                    echo "<p class='card-text'>" . htmlspecialchars($prod['specProduit']) . "</p>";
                    echo "<div class='d-flex align-items-center'>";
                    echo "<p class='card-text mb-0'>Quantité: <span class='quantity'>" . htmlspecialchars($prod['qteEnregistree']) . "</span> &nbsp;&nbsp; </p>";
                    echo "<button class='btn btn-custom btn-custom-dark btn-sm decrease' data-id='" . htmlspecialchars($prod['idProduit']) . "'>-</button>";
                    echo "<button class='btn btn-custom btn-custom-light btn-sm increase' data-id='" . htmlspecialchars($prod['idProduit']) . "'>+</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='button-container'>";
                    echo "<button class='btn btn-custom btn-danger btn-sm delete-button' data-produit-id='" . htmlspecialchars($prod['idProduit']) . "'>Supprimer</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<hr>";
                }
                echo "<div class='text-right'><strong>Prix Total: " . htmlspecialchars($total) . " €</strong></div>";
                echo "<div class='text-center mt-4'>";
                echo "<a href='commander.php' class='btn btn-custom'>Commander</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "<br><hr>";
            } else {
                echo "<p class='text-center'>Votre panier est vide.</p>";
            }
            ?>
        </main>
    </div>
</div>

<script>
    // Ajout d'un écouteur d'événement pour chaque bouton d'augmentation de quantité
    document.querySelectorAll('.increase').forEach(button => {
        button.addEventListener('click', function() {
            updateQuantity(this.dataset.id, 'increase');
        });
    });

    // Ajout d'un écouteur d'événement pour chaque bouton de diminution de quantité
    document.querySelectorAll('.decrease').forEach(button => {
        button.addEventListener('click', function() {
            updateQuantity(this.dataset.id, 'decrease');
        });
    });

    // Fonction pour mettre à jour la quantité d'un produit
    function updateQuantity(produit_id, action) {
        // Envoi d'une requête POST pour mettre à jour la quantité du produit
        fetch('update_quantity.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `produit_id=${produit_id}&action=${action}`
            })
            // Conversion de la réponse en texte
            .then(response => response.text())
            .then(data => {
                // Rechargement de la page après la mise à jour
                location.reload();
            });
    }

    // Ajout d'un écouteur d'événement pour chaque bouton de suppression de produit
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const produit_id = this.getAttribute('data-produit-id');
            // Affichage d'une alerte de confirmation avant la suppression
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envoi d'une requête POST pour supprimer le produit
                    fetch('delete_article.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `produit_id=${produit_id}`
                        })
                        // Conversion de la réponse en texte
                        .then(response => response.text())
                        .then(data => {
                            // Affichage d'une alerte de succès après la suppression
                            Swal.fire(
                                'Supprimé!',
                                'L\'article a été supprimé.',
                                'success'
                            ).then(() => {
                                // Rechargement de la page après la suppression
                                location.reload();
                            });
                        });
                }
            });
        });
    });
</script>

<?php include_once('include/footer.php'); ?>