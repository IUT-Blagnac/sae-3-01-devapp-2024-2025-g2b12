<header id="header" class="site-header header-scrolled position-fixed text-black bg-light">
  <nav id="header-nav" class="navbar navbar-expand-lg px-3 mb-3">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <img src="image//WOOHP.png" class="logo" width="100px">
      </a>
      <button class="navbar-toggler d-flex d-lg-none order-3 p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <svg class="navbar-icon">
          <use xlink:href="#navbar-icon"></use>
        </svg>
      </button>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="bdNavbar" aria-labelledby="bdNavbarOffcanvasLabel">
        <div class="offcanvas-header px-4 pb-0">
          <a class="navbar-brand" href="index.php">
            <img src="image//WOOHP.png" class="logo" width="100px">
          </a>
          <button type="button" class="btn-close btn-close-black" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#bdNavbar"></button>
        </div>
        <div class="offcanvas-body">
          <ul id="navbar" class="navbar-nav text-uppercase justify-content-end align-items-center flex-grow-1 pe-3">
            <li class="nav-item dropdown">
              <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=10" role="button" aria-expanded="false">Combat</a>
              <ul class="dropdown-menu">
                <li><a href="consulter_categorie.php?pIdCateg=70" class="dropdown-item">Corps à corps</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=80" class="dropdown-item">Longue portée</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=90" class="dropdown-item">Moyenne distance</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=100" class="dropdown-item">Longue distance</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
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
              <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=40" role="button" aria-expanded="false">Surveillance et enregistrement</a>
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
              <a class="nav-link me-4 dropdown-toggle link-dark" href="consulter_categorie.php?pIdCateg=60" role="button" aria-expanded="false">Technologie Médicale</a>
              <ul class="dropdown-menu">
                <li><a href="consulter_categorie.php?pIdCateg=180" class="dropdown-item">Premiers secours</a></li>
                <li><a href="consulter_categorie.php?pIdCateg=190" class="dropdown-item">Récupération Rapide</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <div class="user-items ps-5">
                <ul class="d-flex justify-content-end list-unstyled">
                  <li class="search-item pe-3">
                    <a href="rechercher_produit.php" class="search-button">
                      <svg class="search">
                        <use xlink:href="#search"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="pe-3">
                    <a href="visualiser_compte.php">
                      <svg class="user">
                        <use xlink:href="#user"></use>
                      </svg>
                    </a>
                  </li>
                  <li>
                    <a href="visualiser_panier.php">
                      <svg class="cart">
                        <use xlink:href="#cart"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="ps-3">
                    <a href="about.php">
                    <img src="image/aPropos.png" alt="À propos" class="logo" width="20px">
                    </a>
                </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>