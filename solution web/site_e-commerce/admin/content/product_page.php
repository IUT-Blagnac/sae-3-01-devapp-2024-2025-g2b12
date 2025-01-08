<!--
Page d'ajout / d'édition d'un produit.

Date de dernière modification :
- Mardi 7 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->

<!-- titre de la page -->
<?php
    if (isset($_GET['mode']))
    {
        if ($_GET['mode'] == 'ajout')       { print "<h1>Ajouter un produit</h1>" ; }
        elseif ($_GET['mode'] == 'edition') { print "<h1>Produit ###</h1>" ; }
    }
?>

<!-- formulaire d'ajout / d'édition d'un produit -->
<div class='forms-container'>
    <div class='container'>
        <form action='./processing/product_addition_process.php' method='POST' enctype='multipart/form-data'>
            <div class='info-container' <?php print (isset($_SESSION['productInputErrors'])) ? "style=display: flex;" : "style='display: none;'" ; ?>>
                <div class='info-box input-errors'>
                    <?php
                        if (isset($_SESSION['productInputErrors']))
                        {
                            $inputErrors = $_SESSION['productInputErrors'] ;
                            $count = count($inputErrors) ;
                            $index = 0 ;
                            print "<ul>" ;
                            foreach ($inputErrors as $inputError)
                            {
                                print "<li>".$inputError."</li>" ;
                            }
                            print "</ul>" ;
                        }
                    ?>
                </div>
            </div>
            <div class='form-container'>
                <div id='product-description-form' class='left-form'>
                    <div class='form-header'>
                        <h1>Détail du produit</h1>
                    </div>
                    <div class='info-container'>
                        <div class='info-box'>
                            Les champs marqués d’un <span class='required-field'>*</span> sont obligatoires.
                        </div>
                        <small></small>
                    </div>
                    <div class='form-content'>
                        <div class='input-field-container'>
                            <?php
                                $idQuery = $conn->prepare("SELECT MAX(idProduit) AS ID_MAX FROM PRODUIT") ;
                                $idQuery->execute() ;
                                $productId = $idQuery->fetch()['ID_MAX'] + 1 ;
                            ?>
                            <label for='product-id'>Numéro (ID) <span class='required-field'>*</span></label>
                            <input type='text' id='product-id' name='product-id' value='<?php print $productId ; ?>' readonly autocomplete="off">
                            <small></small>
                        </div>
                        <div class='input-field-container'>
                            <label for='product-name'>Nom <span class='required-field'>*</span></label>
                            <input type='text' id='product-name' name='product-name' maxlength='50' autocomplete="off"
                                    value='<?php print (isset($_SESSION['inputs'])) ? $_SESSION['inputs']['product-name'] : "" ; ?>'>
                            <small id='name-character-counter'></small>
                        </div>
                        <div class='input-field-container'>
                            <label class='adjusted-vertical-alignment' for='product-description'>Description</label>
                            <textarea id='product-description' name='product-description' rows='6' maxlength='500'><?php print (isset($_SESSION['inputs'])) ? $_SESSION['inputs']['product-description'] : "" ; ?></textarea>
                            <small id='description-character-counter' class='adjusted-vertical-alignment'></small>
                        </div>
                        <div class='input-field-container'>
                            <label class='adjusted-vertical-alignment' for='product-category-id'>
                                Catégorie <span class='required-field'>*</span>
                            </label>
                            <div id='open-category-selection-menu-button' class='form-button'>
                                Choisir une catégorie
                            </div>
                            <input type='hidden' id='product-category-id' name='product-category-id'
                                    parentCategoryId='<?php
                                        if (isset($_SESSION['inputs']))
                                        {
                                            $parentCategoryIdQuery = $conn->prepare(
                                            "SELECT C1.idCategorie
                                                FROM CATEGORIE C1 INNER JOIN CATEGORIE C2
                                                    ON C1.idCategorie = C2.idParent
                                                WHERE C2.idCategorie = ?"
                                            ) ;
                                            $parentCategoryIdQuery->execute([$_SESSION['inputs']['product-category-id']]) ;
                                            $queryResult = $parentCategoryIdQuery->fetch() ;
                                            print $queryResult ? $queryResult['idCategorie'] : '' ;
                                        }
                                    ?>'
                                    value='<?php print (isset($_SESSION['inputs'])) ? $_SESSION['inputs']['product-category-id'] : "" ; ?>'>
                            <small></small>
                        </div>
                    </div>
                </div>
                <div id='product-image-form' class='right-form'>
                    <div class='form-header'>
                        <h1>Image par défaut du produit</h1>
                    </div>
                    <div class='form-content'>
                        <div class='image-preview-container'>
                            <div id='image-preview' class='image-preview'>
                                <span>Aucune image chargée</span>
                            </div>
                            <div class='image-preview-menu'>
                                <button type='button' id='clear-image-button' class='form-button centred-content flexible disabled'>Effacer l'image</button>
                                <label for='product-image' class='form-button centred-content flexible'>Charger une image</label>
                                <input type='file' id='product-image' name='product-image' accept='image/*' class='hidden'/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class='container-menu right-aligned-content'>
                <div class='container-menu-button form-menu-button cancel-button'
                        onclick="window.location.href='produits.php';">
                    <div class='container-menu-button-name-container'>
                        <span class='container-menu-button-name ns'>Annuler</span>
                    </div>
                </div>
                <input class='container-menu-button form-menu-button'
                        type='submit'
                        name='add-product'
                        value='Ajouter'/>
            </div>
        </form>
    </div>
    <div class='container'>
        <div class='product-variants-form'>
            <div class='form-header'>
                <h1>Variantes</h1>
                <table class='product-variants-table'>
                    <tr>
                        <th class='variant-id-column'>Numéro (ID)</th>
                        <th class='variant-characteristic-column'>Caractéristique</th>
                        <th class='variant-price-column'>Prix</th>
                        <th class='variant-groups-column'>Image</th>
                        <th class='options-column'></th>
                    </tr>
                    <tr class='product-variant-addition-form-row'>
                        <form action='./processing/variant_addition_process.php' method='POST' enctype='multipart/form-data'>
                            <td>
                                <?php
                                    $idQuery = $conn->prepare(
                                        "SELECT MAX(idProduit) AS ID_MAX
                                        FROM VARIETE
                                        WHERE idProduit = ?"
                                    ) ;
                                    $idQuery->execute([$productId]) ;
                                    $queryResult = $idQuery->fetch() ;
                                    $variantId = ($queryResult) ? ($productId * 100 + 1) : ($queryResult['ID_MAX'] + 1) ;
                                ?>
                                <input class='free' type='text' id='id' name='id' value='<?php print $variantId ; ?>' readonly autocomplete="off">
                            </td>
                            <td>
                                <input class='free' type='text' id='characteristic' name='characteristic' maxlength='30' autocomplete="off">
                            </td>
                            <td>
                                <div class='td-content'>
                                    <input class='free' type='text' id='price' name='price' maxlength='22' autocomplete="off">€
                                </div>
                            </td>
                            <td>
                                <div class='td-content'>
                                    <label id='variant-image-label' for='variant-image' class='form-button centred-content flexible <?php print isset($_GET['mode']) && $_GET['mode'] == 'ajout' ? " disabled" : "" ; ?>'>Charger une image</label>
                                    <input type='file' id='variant-image' name='variant-image' accept='image/*' class='hidden'/>
                                    <div id='open-variant-image-view-button' class='form-button icon-container <?php print isset($_GET['mode']) && $_GET['mode'] == 'ajout' ? " disabled" : "" ; ?>'>
                                        <img class='ns' src='image/icon/see-icon.png' alt="Voir l'image">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input id='add-variant-button'
                                    class='container-menu-button<?php print isset($_GET['mode']) && $_GET['mode'] == 'ajout' ? " disabled" : "" ; ?>'
                                    type='submit' name='add-variant' value='Ajouter'/>
                            </td>
                        </form>
                    </tr>
                    <tr>
                        <td colspan='5' class='no-result'>Aucune variante définie</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- suppression de la variable de session des saisies utilisateur -->
<?php unset($_SESSION['inputs']) ; ?>

<!-- suppression de la variable de session des erreurs de saisies -->
<?php unset($_SESSION['productInputErrors']) ; ?>

<!-- menu de sélection de la catégorie du produit (pop-up) -->
<?php require_once('./content/product_page/category_selection_menu.php') ; ?>

<!-- pop-up de visualisation de l'image d'une variante de produit -->
<?php require_once('./content/product_page/variant_image_view.php') ; ?>