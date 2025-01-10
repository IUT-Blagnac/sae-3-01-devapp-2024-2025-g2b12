<!--
Page de visualisation des catégories.

Date de dernière modification :
- Jeudi 9 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<h1>Catégories</h1>
<!-- conteneur de la liste des catégories -->
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
                    $categoryCountQuery = $conn->prepare(
                       "SELECT COUNT(idCategorie) AS CATEGORY_COUNT
                        FROM CATEGORIE
                        WHERE idParent IS NULL"
                    ) ;
                    $categoryCountQuery->execute() ;
                    $categoryCount = $categoryCountQuery->fetch()['CATEGORY_COUNT'] ;
                    if      ($categoryCount == 0)   { print "Aucune catégorie enregistrée" ; }
                    elseif  ($categoryCount == 1)   { print $categoryCount." catégorie enregistrée" ; }
                    else                            { print $categoryCount." catégories enregistrées" ; }
                ?>
            </div>
            <div class='container-menu-button'
                onclick="window.location.href='categories.php?mode=ajout';">
                <div class='container-menu-button-icon-container'>
                    <img class='container-menu-button-icon ns'
                            src='image/icon/add-icon.png'
                            alt='Ajouter une catégorie'>
                </div>
                <div class='container-menu-button-name-container'>
                    <span class='container-menu-button-name ns'>
                        Ajouter une catégorie
                    </span>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <!-- liste des catégories -->
    <?php include('./content/categories_page/categories_table.php') ; ?>
</div>