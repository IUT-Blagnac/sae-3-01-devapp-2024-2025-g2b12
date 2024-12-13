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
$sql = "SELECT p.nomProduit, p.prixProduit, e.qteEnregistree 
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


<?php require_once("./include/menu.php");?>


<!-- Conteneur principal -->
<div class="container-fluid flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="row w-100">
            <!-- partie contenu principal -->
            <main class="container">
                <h2 class="text-center">Mon Panier</h2>
                <?php
                if (!empty($produits)) {
                    echo "<div class='col-md-8 mx-auto'>";
                    echo "<div class='card mb-4 shadow-sm'>";
                    echo "<div class='card-body'>";
                    foreach ($produits as $prod) {
                        echo "<h5 class='card-title'>" . htmlspecialchars($prod['nomProduit']) . "</h5>";
                        echo "<p class='card-text'>" . htmlspecialchars($prod['prixProduit']) . " €</p>";
                        echo "<p class='card-text'>Quantité: " . htmlspecialchars($prod['qteEnregistree']) . "</p>";
                        echo "<hr>";
                    }
                    echo "<div class='text-right'><strong>Prix Total: " . htmlspecialchars($total) . " €</strong></div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<p class='text-center'>Votre panier est vide.</p>";
                }
                ?>
            </main>
        </div>
    </div>

<?php include_once('include/footer.php'); ?>