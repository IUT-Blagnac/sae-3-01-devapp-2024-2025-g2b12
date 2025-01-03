<!--
Tableau de bord du back-office.

Date de dernière modification :
- Mardi 31 décembre 2024 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->

<!-- espace administrateur sécurisé -->
<?php require_once('./include/session.php') ; ?>

<!-- tête HTML -->
<?php
    // initialisation du titre et de l'icône de la page
    $pageTitle  = 'Tableau de bord' ;
    $pageIcon   = 'image/icon/dashboard-favicon.png' ;

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
            <p>- En cours de développement -</p>
        </main>

        <!-- pied de page HTML -->
        <?php require_once('./include/footer.php') ; ?>
    </div>
</body>