<!--
Générateur de la table HTML des variantes d'un produit.

Date de dernière modification :
- Mercredi 1er janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<table class='variants-table <?php print ($ptRowIndex%2 == 0) ? "even-table" : "odd-table" ; ?>'>
    <body>
        <?php
            // note : {$productId} est une variable du fichier php appelant

            // récupération des variantes du produit
            $variantsQuery = $conn->prepare("SELECT * FROM VARIETE WHERE idProduit = ?") ;
            $variantsQuery->execute([$productId]) ;

            // construction de la table HTML des variantes du produit
            while ($variant = $variantsQuery->fetch())
            {
                print "<tr class='content'>" ;
                print "<td class='variant-id-column'><div>"
                      ."<div class='header'>N°</div>"
                      ."<div class='content'>".$variant['idVariete']."</div>"
                      ."</div></td>" ;
                print "<td class='variant-feature-column'><div>"
                      ."<div class='header'>Caractéristique</div>"
                      ."<div class='content'>"
                      .((is_null($variant['specVariete'])) ? "–" : $variant['specVariete'])
                      ."</div>"
                      ."</div></td>" ;
                print "<td class='variant-price-column'><div>"
                      ."<div class='header'>Prix</div>"
                      ."<div class='content'>".$variant['prixVariete']." €</div>"
                      ."</div></td>" ;
                print "<td class='variant-groups-column'><div>"
                      ."<div class='header'>Regroupements</div>"
                      ."<div class='content variant-groups-container'>" ;
                $groupsQuery = $conn->prepare(
                    "SELECT RT.nomRegroupement
                     FROM REGROUPER RR INNER JOIN REGROUPEMENT RT
                         ON RR.idRegroupement = RT.idRegroupement
                     WHERE idVariete = ?"
                ) ;
                $groupsQuery->execute([$variant['idVariete']]) ;
                while ($group = $groupsQuery->fetch())
                {
                    $groupColor = generateColor($group['nomRegroupement']) ;
                    print "<a class='link group' style='background-color: $groupColor;'"
                          ."href=''>"
                          .$group['nomRegroupement']
                          ."</a>" ;
                }
                print "</div></div></td>" ;
                print "<td class='variant-stock-column'><div>"
                      ."<div class='header'>Stock</div>"
                      ."<div class='content'>".$variant['qteStockVariete']."</div>"
                      ."</div></td>" ;
                print "</tr>" ;
            }
        ?>
    </body>
</table>