<!-- Partie HTML et HEAD -->
<?php require_once('./include/head.php'); ?>

<!-- Partie BODY -->
<?php require_once('./include/header.php'); ?>

<!-- Partie MENU -->
<?php require_once('./include/menu.php'); ?>

<div class="product-showcase">
  <h1>Produits en Vitrine</h1>

  <!-- Tableau des produits -->
  <div class="product-store">
    <?php
      include("include/Connect.inc.php");

      // // R�cup�rer les produits avec un prix inf�rieur � 50
      // $produits = $conn->query("SELECT * FROM PRODUIT WHERE prixProduit < 50");

      // // Boucle pour afficher chaque produit dans une carte stylis�e
      // while ($produit = $produits->fetch()) {
      //   echo "
      //     <div class='product-card'>
      //       <div class='card-detail'>
      //         <span>Produit ID: {$produit['idProduit']}</span>
      //         <span>Nom: {$produit['nomProduit']}</span>
      //         <span>Prix: {$produit['prixProduit']}�</span>
      //       </div>
      //       <div class='cart-concern'>
      //         <svg> <!-- Ton ic�ne de panier ici --> </svg>
      //         <span>Ajouter au Panier</span>
      //       </div>
      //     </div>
      //   ";
      // }
      echo "</BR>" ;
      echo "<center><B>Produits Innovants</B></br>" ;
      echo "<center>Découvrez notre sélection de gadgets d'espionnage</br>" ;
      $categorie = $conn->prepare("SELECT * FROM CATEGORIE WHERE idParent is null");
      $categorie->execute();
      $categ = $categorie->fetchAll();
      if (!empty($categ)) {
        echo "<div class='row'>";
        foreach($categ as $categorie) {
            echo "<div class='col-md-4'>";
            //echo "<a href = 'consulter_categorie.php?pIdCateg=".$categorie['idCategorie']."' style='text-decoration: none, color:inherit;'>";
            echo "<a href='consulter_categorie.php?pIdCateg=" . $categorie['idCategorie']. "' style='text-decoration: none; color: inherit;'>";
            echo "<div class='card mb-4 shadow-sm style='cursor:pointer;'>";
            echo "<div class='card-img-top' style='height: 100px; background-color: #f0f0f0;'></div>"; // Placeholder pour l'image
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>".$categorie['nomCategorie']."</h5>";
            echo "</div>";
            echo "</div>";
            echo "</a>";
            echo "</div>";
        }
        echo "</div>";
      }
    ?>
  </div>
</div>

<?php include_once('include/footer.php'); ?>

