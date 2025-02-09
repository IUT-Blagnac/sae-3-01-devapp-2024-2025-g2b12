<!--
Page de visualisation des produits.

Date de dernière modification :
- Vendredi 3 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<h1>Produits</h1>
<!-- conteneur de la liste des produits -->
<div class='container'>
    <!-- menu -->
    <div class='container-menu'>
        <div class='h-container'>
            <div id='showAllButton' class='container-menu-button no-frame'>
                <div class='container-menu-button-icon-container'>
                    <img class='container-menu-button-icon ns'
                        src='image/icon/show-all-black-icon.png'
                        alt='Développer tout'>
                </div>
                <div class='container-menu-button-name-container'>
                    <span class='container-menu-button-name ns'>
                        Développer tout
                    </span>
                </div>
            </div>
            <div id='hideAllButton' class='container-menu-button no-frame disabled'>
                <div class='container-menu-button-icon-container'>
                    <img class='container-menu-button-icon ns'
                        src='image/icon/hide-all-black-icon.png'
                        alt='Fermer tout'>
                </div>
                <div class='container-menu-button-name-container'>
                    <span class='container-menu-button-name ns'>
                        Fermer tout
                    </span>
                </div>
            </div>
        </div>
        <div class='h-container'>
            <div class='no-wrap'>
                <?php
                    $productCountQuery = $conn->prepare("SELECT COUNT(idProduit) AS PRODUCT_COUNT FROM PRODUIT") ;
                    $productCountQuery->execute() ;
                    $productCount = $productCountQuery->fetch()['PRODUCT_COUNT'] ;
                    if      ($productCount == 0)    { print "Aucun produit enregistré" ; }
                    elseif  ($productCount == 1)    { print $productCount." produit enregistré" ; }
                    else                            { print $productCount." produits enregistrés" ; }
                ?>
            </div>
            <div class='container-menu-button'
                onclick="window.location.href='produits.php?mode=ajout';">
                <div class='container-menu-button-icon-container'>
                    <img class='container-menu-button-icon ns'
                            src='image/icon/add-icon.png'
                            alt='Ajouter un produit'>
                </div>
                <div class='container-menu-button-name-container'>
                    <span class='container-menu-button-name ns'>
                        Ajouter un produit
                    </span>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <!-- liste des produits -->
    <?php include('./content/products_page/products_table.php') ; ?>
</div>