<!-- Partie HTML et HEAD -->
<?php require_once('./include/head.php'); ?>

<!-- Partie BODY -->
<?php require_once('./include/header.php'); ?>

<!-- Partie MENU -->
<?php require_once('./include/menu.php'); ?>

<<<<<<< HEAD
<!-- Section Billboard -->
<section id="billboard" class="position-relative overflow-hidden bg-light-blue" style="padding: 175px 0;">
=======
<!-- Partie HTML et HEAD -->
<?php require_once('./include/head.php'); ?>

<!-- Partie BODY -->
<?php require_once('./include/header.php'); ?>

<!-- Partie MENU -->
<?php require_once('./include/menu.php'); ?>

<!-- Section Billboard -->
<section id="billboard" class="position-relative overflow-hidden bg-light-blue">
>>>>>>> e2c70232b6778d2e987d5d8b422c68f30bc347fa
  <div class="container">
    <div class="row d-flex align-items-center">
      <div class="col-md-6">
        <div class="banner-content">
          <h1 class="display-2 text-uppercase text-dark pb-5">Bienvenue au WOOHP</h1>
<<<<<<< HEAD
          <a href="#categories" class="btn btn-medium btn-dark text-uppercase btn-rounded-none shop-product-btn">Shop Product</a>
=======
          <a href="index.php" class="btn btn-medium btn-dark text-uppercase btn-rounded-none">Shop Product</a>
>>>>>>> e2c70232b6778d2e987d5d8b422c68f30bc347fa
        </div>
      </div>
      <div class="col-md-6">
        <div class="image-holder">
          <img src="image/WOOHP.png" alt="banner">
        </div>
      </div>
    </div>
  </div>
</section>
<<<<<<< HEAD

<div id="categories" class="product-showcase">
  <main class="container" style="margin-top:8%; margin-bottom:5%;">
    <h1 class="title" style="opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease-out, transform 0.6s ease-out;">
      <center>Produits en Vitrine</center>
    </h1>

    <!-- Tableau des produits -->
    <div class="product-store">
      <?php
      include("include/Connect.inc.php");

      echo "</BR>";
      echo "<center><B>Produits Innovants</B></br>";
      echo "<center>Découvrez notre sélection de gadgets d'espionnage</br></br>";
      $categorie = $conn->prepare("SELECT * FROM CATEGORIE WHERE idParent is null");
      $categorie->execute();
      $categ = $categorie->fetchAll();
      if (!empty($categ)) {
        echo "<div class='row'>";
        foreach ($categ as $categorie) {
          $imagePath = "./image/categorie/" . htmlspecialchars($categorie['nomCategorie']) . ".png";
          if (!file_exists($imagePath)) {
            $imagePath = "./image/categorie/default.png"; // Chemin de l'image par défaut si l'image de la catégorie n'existe pas
          }
          echo "<div class='col-md-4 category-card-container'>";
          echo "<a href='consulter_categorie.php?pIdCateg=" . $categorie['idCategorie'] . "' style='text-decoration: none; color: inherit;'>";
          echo "<div class='card mb-4 shadow-sm category-card' style='cursor:pointer; position: relative; overflow: hidden; opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease-out, transform 0.6s ease-out;'>";
          echo "<div class='image-container' style='position: relative;'>";
          echo "<img src='" . $imagePath . "' class='card-img-top' alt='" . htmlspecialchars($categorie['nomCategorie']) . "' style='height: 200px; object-fit: cover;'>";
          echo "</div>";
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>" . htmlspecialchars($categorie['nomCategorie']) . "</h5>";
          echo "</div>";
          echo "</div>";
          echo "</a>";
          echo "</div>";
        }
        echo "</div>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
      }
      ?>
    </div>
  </main>
</div>
<hr style="background: linear-gradient(to right, #ffffff, #000000, #ffffff);"><br><br><br>

<style>



  .shop-product-btn {
    background-color: #000;
    color: #fff;
    border: 2px solid #000;
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
  }

  .shop-product-btn:hover {
    background-color: #fff;
    color: #000;
    border-color: #000;
  }

  .category-card {
    transition: box-shadow 0.3s ease, background-color 0.3s ease, opacity 0.6s ease-out, transform 0.2s ease;
    opacity: 0;
    transform: translateY(20px);
  }

  .category-card .card-body {
    transition: background-color 0.3s ease;
  }

  .category-card:hover .card-body {
    background-color: rgba(136, 172, 223, 0.2);
  }

  .category-card .card-img-top {
    transition: opacity 0.3s ease, filter 0.3s ease;
  }

  .category-card .image-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.5) 100%);
    opacity: 0;
    transition: opacity 0.6s ease;
  }

  .category-card:hover .image-container::after {
    opacity: 1;
  }

  .title {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
  }
</style>

<script>
  // Lors de la selection du bouton "Shop Product", on scroll jusqu'à la section des catégories
  document.querySelector('.shop-product-btn').addEventListener('click', function(e) {
    e.preventDefault();
    document.querySelector('#categories').scrollIntoView({ behavior: 'smooth' });
  });

  // Effet d'apparition dynamique des éléments au scroll
  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.category-card, .title').forEach(element => {
    observer.observe(element);
  });

  // Add zoom and shadow effect on category cards
  document.querySelectorAll('.category-card').forEach(card => {
    card.addEventListener('mouseover', function() {
      card.style.transform = 'scale(1.05)';
      card.style.boxShadow = '0 24px 48px rgba(0, 0, 0, 0.4)'; // Augmentation de l'effet de shadow box
    });
    card.addEventListener('mouseout', function() {
      card.style.transform = 'scale(1)';
      card.style.boxShadow = 'none'; // Retrait de l'effet de shadow box
    });
  });
</script>

=======


<div class="product-showcase">
  <main class="container" style="margin-top:8%; margin-bottom:5%;">
    <h1>
      <center>Produits en Vitrine</center>
    </h1>

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
      echo "</BR>";
      echo "<center><B>Produits Innovants</B></br>";
      echo "<center>Découvrez notre sélection de gadgets d'espionnage</br>";
      $categorie = $conn->prepare("SELECT * FROM CATEGORIE WHERE idParent is null");
      $categorie->execute();
      $categ = $categorie->fetchAll();
      if (!empty($categ)) {
        echo "<div class='row'>";
        foreach ($categ as $categorie) {
          echo "<div class='col-md-4'>";
          //echo "<a href = 'consulter_categorie.php?pIdCateg=".$categorie['idCategorie']."' style='text-decoration: none, color:inherit;'>";
          echo "<a href='consulter_categorie.php?pIdCateg=" . $categorie['idCategorie'] . "' style='text-decoration: none; color: inherit;'>";
          echo "<div class='card mb-4 shadow-sm style='cursor:pointer;'>";
          echo "<div class='card-img-top' style='height: 100px; background-color: #f0f0f0;'></div>"; // Placeholder pour l'image
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>" . $categorie['nomCategorie'] . "</h5>";
          echo "</div>";
          echo "</div>";
          echo "</a>";
          echo "</div>";
        }
        echo "</div>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<hr>";
      }
      ?>
    </div>
  </main>
</div>

>>>>>>> e2c70232b6778d2e987d5d8b422c68f30bc347fa
<?php include_once('include/footer.php'); ?>