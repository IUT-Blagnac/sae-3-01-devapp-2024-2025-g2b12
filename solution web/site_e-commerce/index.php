<!-- Partie HTML et HEAD -->
<?php require_once('./include/head.php'); ?>

<!-- Partie BODY -->
<?php require_once('./include/header.php'); ?>

<div class="product-showcase">
  <h1>Produits en Vitrine</h1>

  <!-- Tableau des produits -->
  <div class="product-store">
    <?php
      include("include/Connect.inc.php");
      // Récupérer les produits avec un prix inférieur à 50
      $produits = $conn->query("SELECT * FROM PRODUIT WHERE prixProduit < 50");

      // Boucle pour afficher chaque produit dans une carte stylisée
      while ($produit = $produits->fetch()) {
        echo "
          <div class='product-card'>
            <div class='card-detail'>
              <span>Produit ID: {$produit['idProduit']}</span>
              <span>Nom: {$produit['nomProduit']}</span>
              <span>Prix: {$produit['prixProduit']}€</span>
            </div>
            <div class='cart-concern'>
              <svg> <!-- Ton icône de panier ici --> </svg>
              <span>Ajouter au Panier</span>
            </div>
          </div>
        ";
      }
    ?>
  </div>
</div>

<?php include_once('include/footer.php'); ?>
