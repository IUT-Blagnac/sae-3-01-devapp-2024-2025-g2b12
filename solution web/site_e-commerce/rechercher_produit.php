<!-- partie html & head -->
<?php require_once('./include/head.php'); ?>

<!-- partie body -->
<?php require_once('./include/header.php'); ?>

<?php require_once('./include/Connect.inc.php'); ?>

<!-- Conteneur principal -->
<div class="container-fluid flex-grow-1">
    <div class="row">
        <!-- partie contenu principal -->
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4" style="max-width: 800px; margin: 0 auto;">
            <div class="search-popup is-visible">
                <div class="search-popup-container">

                    <form role="search" method="post" class="search-form">
                        <input type="search" id="search-form" class="search-field" placeholder="Type and press enter"
                            value="" name="s">
                        <button type="submit" name="Submit" class="search-submit"><svg class="search">
                                <use xlink:href="#search"></use>
                            </svg></button>
                    </form>

                    <h5 class="cat-list-title">Browse Categories</h5>

                    <ul class="cat-list">
                        <li class="cat-list-item">
                            <a href="#" title="Mobile Phones">Mobile Phones</a>
                        </li>
                        <li class="cat-list-item">
                            <a href="#" title="Smart Watches">Smart Watches</a>
                        </li>
                        <li class="cat-list-item">
                            <a href="#" title="Headphones">Headphones</a>
                        </li>
                        <li class="cat-list-item">
                            <a href="#" title="Accessories">Accessories</a>
                        </li>
                        <li class="cat-list-item">
                            <a href="#" title="Monitors">Monitors</a>
                        </li>
                        <li class="cat-list-item">
                            <a href="#" title="Speakers">Speakers</a>
                        </li>
                        <li class="cat-list-item">
                            <a href="#" title="Memory Cards">Memory Cards</a>
                        </li>
                    </ul>

                    <?php
                        if(htmlentities(isset($_POST["s"])) && htmlentities(isset($_POST["Submit"]))) {
                            $pdostat = $conn->prepare("SELECT * FROM PRODUIT WHERE nomProduit LIKE :nomP");
                            $pdostat->execute([':nomP' => htmlentities($_POST['s'])]);

                            while($produits = $pdostat->fetch(PDO::FETCH_ASSOC)) {
                                echo "<h1>".$produits["idProduit"]."/".$produits["nomProduit"]."</h1>";
                            }
                        }
                    ?>

                </div>
            </div>
        </main>
    </div>
</div>