<!--
Page de gestion des produits.

Date de dernière modification :
- Mercredi 1er janvier 2025 -

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
            <h1>Produits</h1>
            <div class='container'>
                <!-- liste des produits -->
                <?php include('./utility/build_products_table.php') ; ?>
            </div>
        </main>

        <!-- pied de page HTML -->
        <?php require_once('./include/footer.php') ; ?>
    </div>
</body>

<!-- traitement de l'affichage des listes des variantes de produit -->
<script>
    /**
     * Affiche la liste des variantes pour le produit
     * de numéro {pProductId}.
     */
    function displayVariantsTable(pProductId)
    {
        // récupération des éléments HTML
        let productsTable   = document.getElementById('productsTable') ;
        const productRow    = productsTable.querySelector(`tr[productId="${pProductId}"]`) ;
        const vtRow         = productRow.nextElementSibling ; // Variants Table Row
        const showMoreIcon  = productRow.querySelector('.show-more-icon') ;

        // traitement de l'affichage de la table des variantes de produit
        vtRow.style.display = (   vtRow.style.display == 'none'
                               || vtRow.style.display == ''     ) ? 'table-row' : 'none' ;
        
        // traitement de l'apparence du bouton "Voir plus" / "Voir moins"
        if (vtRow.style.display === 'table-row')
        {
            showMoreIcon.src = 'image/icon/show-less-icon.png' ;
            showMoreIcon.alt = 'Voir moins';
        }
        else
        {
            showMoreIcon.src = 'image/icon/show-more-icon.png' ;
            showMoreIcon.alt = 'Voir plus';
        }
    }
</script>