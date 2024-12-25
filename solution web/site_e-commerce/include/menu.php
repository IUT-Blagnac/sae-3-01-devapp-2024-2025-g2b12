<?php
require_once('./include/Connect.inc.php');

// Récupérer les regroupements
$regroupements = $conn->query("SELECT idRegroupement, nomRegroupement FROM REGROUPEMENT")->fetchAll(PDO::FETCH_ASSOC);
?>

<header id="header" class="site-header header-scrolled position-fixed text-black bg-light w-100">
  <!-- Première navbar : regroupements -->
  <nav id="regroupements-nav" class="navbar navbar-expand-lg border-bottom">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <img src="image/WOOHP.png" class="logo" width="100px" alt="Logo">
      </a>
      <ul class="navbar-nav text-uppercase d-flex flex-wrap">
      <a class="nav-link me-4 link-dark" href="consulter_produit_combine.php" role="button">Combinez pour économiser</a>
      <a class="nav-link me-4 link-dark" href="consulter_produit_soldes.php" role="button">Soldes</a>
      </ul>
    </div>
  </nav>

  <!-- Deuxième navbar : catégories et boutons utilisateur -->
  <nav id="categories-nav" class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#bdNavbar" aria-controls="bdNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="bdNavbar" aria-labelledby="bdNavbarOffcanvasLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="bdNavbarOffcanvasLabel">Menu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav text-uppercase">
            <!-- Catégories -->
            <li class="nav-item dropdown">
              <!-- <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=10" role="button" data-bs-toggle="dropdown" aria-expanded="false">Combat</a> -->
              <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=10" role="button" aria-expanded="false">Combat</a>
              <ul class="dropdown-menu">
                <li><a href="consulter_categorie.php?pIdCateg=70" class="dropdown-item">Corps à corps</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=80" class="dropdown-item">Longue portée</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=90" class="dropdown-item">Moyenne distance</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=100" class="dropdown-item">Longue distance</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <!-- <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=20" role="button" data-bs-toggle="dropdown" aria-expanded="false">Mobilité</a> -->
              <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=20" role="button" aria-expanded="false">Mobilité</a>
              <ul class="dropdown-menu">
                <li><a href="consulter_categorie.php?pIdCateg=110" class="dropdown-item">Véhicules</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=120" class="dropdown-item">Chaussures</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=30" role="button" aria-expanded="false">Infiltration</a>
              <ul class="dropdown-menu">
                <li><a href="consulter_categorie.php?pIdCateg=130" class="dropdown-item">Camouflages</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=40" role="button" aria-expanded="false">Surveillance</a>
              <ul class="dropdown-menu">
                <li><a href="consulter_categorie.php?pIdCateg=140" class="dropdown-item">Robots</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=150" class="dropdown-item">Caméra</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=160" class="dropdown-item">Micros</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=50" role="button" aria-expanded="false">Evasion</a>
              <ul class="dropdown-menu">
                <li><a href="consulter_categorie.php?pIdCateg=170" class="dropdown-item">Accessoires</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=60" role="button" aria-expanded="false">Médicale</a>
              <ul class="dropdown-menu">
                <li><a href="consulter_categorie.php?pIdCateg=180" class="dropdown-item">Premiers secours</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=190" class="dropdown-item">Récupération Rapide</a></li>
              </ul>
          </ul>
        </div>
      </div>

      <!-- Boutons utilisateur -->
      <div class="user-items d-flex align-items-center">
        <a href="rechercher_produit.php" class="me-3">
          <svg class="search">
            <use xlink:href="#search"></use>
          </svg>
        </a>
        <a href="visualiser_compte.php" class="me-3">
          <svg class="user">
            <use xlink:href="#user"></use>
          </svg>
        </a>
        <a href="visualiser_panier.php" class="me-3">
          <svg class="cart">
            <use xlink:href="#cart"></use>
          </svg>
        </a>
        <a href="about.php" class="me-3">
          <img src="image/aPropos.png" alt="À propos" class="logo" width="20px">
        </a>
      </div>
    </div>
  </nav>
</header>

