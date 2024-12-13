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
                .btn-custom-light {
                    background-color: rgb(122, 122, 122);
                    color: #fff;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
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
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
                    
                }

                .btn-custom-dark:hover {
                    background-color: rgba(136, 172, 223, 255);
                    color: #fff;
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
            </style>
            <?php
            if (!empty($produits)) {
                echo "<div class='col-md-8 mx-auto'>";
                echo "<div class='card mb-4 shadow-sm'>";
                echo "<div class='card-body'>";
                foreach ($produits as $prod) {
                    echo "<div class='row mb-3'>";
                    echo "<div class='col-md-4'>";
                    echo "<div class='product-image-placeholder'>Image</div>"; // Placeholder pour l'image
                    echo "</div>";
                    echo "<div class='col-md-8'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($prod['nomProduit']) . "</h5>";
                    echo "<p class='card-text'>" . htmlspecialchars($prod['prixProduit']) . " €</p>";
                    echo "<p class='card-text'>" . htmlspecialchars($prod['specProduit']) . "</p>";
                    echo "<div class='d-flex align-items-center'>";
                    echo "<p class='card-text mb-0'>Quantité: " . htmlspecialchars($prod['qteEnregistree']) . "  &nbsp;&nbsp; </p>";
                    echo "<form method='post' action='update_quantity.php' class='d-flex align-items-center ml-2'>";
                    echo "<button type='submit' name='action' value='decrease' class='btn btn-custom-dark btn-sm mr-2'>-</button>";
                    echo "<input type='hidden' name='produit_id' value='" . htmlspecialchars($prod['idProduit']) . "'>";
                    echo "<span class='mx-1'> </span>";
                    echo "<button type='submit' name='action' value='increase' class='btn btn-custom-light btn-sm ml-2'>+</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<hr>";
                }
                echo "<div class='text-right'><strong>Prix Total: " . htmlspecialchars($total) . " €</strong></div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "<br><hr>";
            } else {
                echo "<p class='text-center'>Votre panier est vide.</p>";
                echo "<hr>";
            }
            ?>
        </main>
    </div>
</div>

<?php include_once('include/footer.php'); ?>