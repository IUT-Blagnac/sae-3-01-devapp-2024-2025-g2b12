<!--
Table HTML des catégories.

Date de dernière modification :
- Jeudi 9 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<table id='productsTable'>
    <head>
        <tr class='header-row'>
            <th class='category-id-column'>N°</th>
            <th class='category-name-column'>Nom</th>
            <th class='category-products-column'>Produits</th>
            <th class='category-subcategories-column'>Sous-catégories</th>
            <th></th>
        </tr>
    </head>
    <body>
        <!-- construction de la liste (table) des catégories -->
        <?php
            // récupération des catégories
            $categoriesQuery = $conn->prepare(
               "SELECT C1.idCategorie, C1.nomCategorie,
                (
                    SELECT COUNT(P.idProduit)
                    FROM PRODUIT P
                    INNER JOIN CATEGORIE C2
                        ON P.idCategorie = C2.idCategorie
                    WHERE C2.idParent = C1.idCategorie
                ) AS PRODUCT_COUNT,
                (
                    SELECT COUNT(C2.idCategorie)
                    FROM CATEGORIE C2
                    WHERE C2.idParent = C1.idCategorie
                ) AS CATEGORY_COUNT
                FROM CATEGORIE C1
                WHERE C1.idParent IS NULL"         
            ) ;
            $categoriesQuery->execute() ;

            // construction de la table HTML des catégories
            $ptRowIndex = 0 ;
            while ($category = $categoriesQuery->fetch())
            {
                $categoryId = $category['idCategorie'] ;
                print "<tr categoryId=".$categoryId." class='content" ;
                if ($ptRowIndex % 2 == 0)   { print " even-row" ; }
                else                        { print " odd-row" ; }
                print "'>" ;
                print "<td class='centred-text'>".$categoryId."</td>" ;
                print "<td>".$category['nomCategorie']."</td>" ;
                print "<td class='centred-text'>".$category['PRODUCT_COUNT']."</td>" ;
                print "<td class='centred-text'>".$category['CATEGORY_COUNT'] ;
                if ($category['CATEGORY_COUNT'] > 0)
                {
                    print "<img class='show-more-icon'
                            src='image/icon/show-more-icon.png'
                            alt='Voir plus'
                            category-id='".$categoryId."'>" ;
                }
                print "</td>" ;
                ?>
                <td>
                    <div class='options-container'>
                        <div class='clickable-icon-container'>
                            <a class='icon-container' href=''>
                                <img class='edit-icon  ns'
                                    src='image/icon/edit-icon.png'
                                    alt='Éditer'>
                            </a>
                        </div>
                        <div class='clickable-icon-container'>
                            <a class='icon-container' href=''>
                                <img class='delete-icon ns'
                                    src='image/icon/delete-icon.png'
                                    alt='Supprimer'>
                            </a>
                        </div>
                    </div>
                </td>
                <?php
                print "</tr>" ;
                print "<tr class='variants-table-container'>" ;
                print "<td class='variants-table-cell' colspan='5'>" ;
                //include('./content/products_page/product_variants_table.php') ;
                print "</td>" ;
                print "</tr>" ;
                $ptRowIndex++ ;
            }
        ?>
    </body>
</table>