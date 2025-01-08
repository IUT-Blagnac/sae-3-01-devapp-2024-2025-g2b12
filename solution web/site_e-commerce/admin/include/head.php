<!--
Tête HTML des pages du back-office.

Date de dernière modification :
- Lundi 6 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?= isset($pageTitle) ? $pageTitle : 'Back-office' ; ?></title>
    <link rel='icon' type='image/png' href='<?= isset($pageIcon) ? $pageIcon : 'image/icon/back-office-favicon.png' ; ?>'>

    <!-- récupération de la page courante -->
    <?php $currentPage = explode('?', basename($_SERVER['PHP_SELF']))[0] ; ?>

    <!-- styles CSS -->
    <link rel='stylesheet' type='text/css' href='style/admin_space.css'>
    <link rel='stylesheet' type='text/css' href='style/header.css'>
    <link rel='stylesheet' type='text/css' href='style/footer.css'>
    <?php
        switch ($currentPage)
        {
            case "connexion.php" :
                print "<link rel='stylesheet' type='text/css' href='style/login_form.css'>" ;
            case "produits.php" :
                print "<link rel='stylesheet' type='text/css' href='style/form.css'>" ;
                print "<link rel='stylesheet' type='text/css' href='style/pop-up.css'>" ;
                break ;
            default :
                break ;
        }
    ?>

    <!-- scripts JavaScript -->
    <link rel='preload' href='script/side_menu.js' as='script'>
    <script src='script/side_menu.js'></script>
    <link rel='preload' href='script/alert_utility.js' as='script'>
    <script src='script/alert_utility.js'></script>
    <?php
        switch ($currentPage)
        {
            case "produits.php" :
                print "<link rel='preload' href='script/produits.js' as='script'>" ;
                print "<script src='script/produits.js'></script>" ;
                print "<link rel='preload' href='script/category_selection_menu.js' as='script'>" ;
                print "<script src='script/category_selection_menu.js'></script>" ;
                print "<link rel='preload' href='script/variant_image_view.js' as='script'>" ;
                print "<script src='script/variant_image_view.js'></script>" ;
                break ;
            default :
                break ;
        }
    ?>
</head>