<!--
Page de gestion des commandes.

Date de dernière modification :
- Jeudi 9 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->

<!-- espace administrateur sécurisé -->
<?php require_once('./include/session.php') ; ?>

<!-- tête HTML -->
<?php
    // initialisation du titre et de l'icône de la page
    $pageTitle  = 'Commandes' ;
    $pageIcon   = 'image/icon/back-office-favicon.png' ;

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
                <p>- En cours de développement -</p>
            </div>

            <!-- pied de page HTML -->
            <?php require_once('./include/footer.php') ; ?>
        </main>
    </div>
</body>