<!--
Générateur de la table HTML des produits.

Date de dernière modification :
- Mercredi 1er janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<table id='productsTable'>
    <head>
        <tr class='header-row'>
            <th class='product-id-column'>N°</th>
            <th class='product-name-column'>Nom</th>
            <th class='product-category-column'>Catégorie</th>
            <th class='product-variants-column'>Variantes</th>
            <th></th>
        </tr>
    </head>
    <body>
        <!-- construction de la liste (table) des produits -->
        <?php
            // fonctions utilitaires
            require_once('./utility/functions.php') ;

            // récupération des produits
            $bptQuery = $conn->prepare(
                "SELECT P.idProduit, P.nomProduit, C.nomCategorie,
                        COUNT(V.idVariete) AS NBRE_VARIANTES
                 FROM PRODUIT P
                 INNER JOIN CATEGORIE C
                     ON P.idCategorie = C.idCategorie
                 INNER JOIN VARIETE V
                     ON P.idProduit = V.idProduit
                 GROUP BY P.idProduit"
            ) ;
            $bptQuery->execute() ;

            // construction de la table HTML des produits
            $ptRowIndex = 0 ;
            while ($product = $bptQuery->fetch())
            {
                $productId = $product['idProduit'] ;
                print "<tr productId=".$productId." class='content" ;
                if ($ptRowIndex % 2 == 0)   { print " even-row" ; }
                else                        { print " odd-row" ; }
                print "'>" ;
                print "<td class='centred-text'>".$productId."</td>" ;
                print "<td>".$product['nomProduit']."</td>" ;
                $categoryColor = generateColor($product['nomCategorie']) ;
                print "<td><a class='link category' style='background-color: $categoryColor;'"
                      ."href=''>"
                      .$product['nomCategorie']
                      ."</a></td>" ;
                print "<td class='centred-text'>"
                        .$product['NBRE_VARIANTES']
                        ."<img class='show-more-icon'
                                src='image/icon/show-more-icon.png'
                                alt='Voir plus'
                                onclick='displayVariantsTable(".$productId.")'>"
                        ."</td>" ;
                ?>
                <td>
                    <div class='options-container'>
                        <div class='clickable-icon-container'>
                            <a class='icon-container' href=''>
                                <img class='edit-icon'
                                    src='image/icon/edit-icon.png'
                                    alt='Éditer'>
                            </a>
                        </div>
                        <div class='clickable-icon-container'>
                            <a class='icon-container' href=''>
                                <img class='delete-icon'
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
                include('./utility/build_variants_table.php') ;
                print "</td>" ;
                print "</tr>" ;
                $ptRowIndex++ ;
            }
        ?>
    </body>
</table>