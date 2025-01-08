<!--
Page de gestion des produits.

Date de dernière modification :
- Vendredi 3 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->

<!-- espace administrateur sécurisé -->
<?php require_once('./include/session.php') ; ?>

<!-- connexion à la base de données -->
<?php require_once('../include/Connect.inc.php') ; ?>

<!-- tête HTML -->
<?php
    // initialisation du titre et de l'icône de la page
    $pageTitle  = 'Produits' ;
    $pageIcon   = 'image/icon/products-favicon.png' ;

    // inclusion de la tête HTML
    require_once('./include/head.php') ;
?>

<!-- corps de page -->
<body>
    <!-- menu latéral -->
    <?php require_once('./include/side_menu.php') ; ?>

    <!-- contenu de la page -->
    <div class='page-container'>
        <!-- en-tête HTML -->
        <?php require_once('./include/header.php') ; ?>

        <!-- contenu principal -->
        <main>
            <div class='content'>
                <?php
                    if (!isset($_GET['mode']))
                    {
                        require_once('./content/products_page.php') ;
                    }
                    else
                    {
                        require_once('./content/product_page.php') ;
                    }
                ?>
            </div>

            <!-- pied de page HTML -->
            <?php require_once('./include/footer.php') ; ?>
        </main>
    </div>
</body>