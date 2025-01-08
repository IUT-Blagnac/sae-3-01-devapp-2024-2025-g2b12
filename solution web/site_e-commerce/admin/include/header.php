<!--
En-tête HTML des pages du back-office.

Date de dernière modification :
- Lundi 6 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->

<!-- connexion à la base de données -->
<?php require_once('../include/Connect.inc.php') ; ?>

<!-- fonctions utilitaires PHP -->
<?php require_once('./utility/functions.php') ; ?>

<!-- récupération du profile administrateur -->
<?php
    $adminProfile = $conn->prepare("SELECT * FROM ADMIN WHERE idAdmin = ?") ;
    $adminProfile->execute([$_SESSION['admin_id']]) ;
    $admin = $adminProfile->fetch() ;
?>

<!-- header -->
<header>
    <div class='admin-profile'>
        <div class='admin-profile-icon-container' style='background-color: <?php
            if (!isset($admin['prenomAdmin']) && !isset($admin['nomAdmin'])) { print "#232429" ; }
            else { print generateColor($admin['prenomAdmin']." ".$admin['nomAdmin']) ; }
        ?>'>
            <img class='admin-profile-icon ns' src='image/icon/admin-profile-icon.png' alt='Profile administrateur'>
        </div>
        <div class='admin-info'>
            <div class='admin-name-or-login'>
                <?php
                    if (!isset($admin['prenomAdmin']) && !isset($admin['nomAdmin']))
                    {
                        print $admin['loginAdmin'] ;
                    }
                    else
                    {
                        print $admin['prenomAdmin']." ".$admin['nomAdmin'] ;
                    }
                ?>
            </div>
            <div class='admin-email'>
                <?php print $admin['emailAdmin'] ; ?>
            </div>
        </div>
    </div>
    <div class='header-menu'>
        <div class='header-menu-button' onclick="window.open('../index.php', '_blank') ;">
            <div class='header-menu-button-icon-container'>
                <img class='header-menu-button-icon ns' src='image/icon/go-to-website-icon.png' alt='Accéder au site'>
            </div>
            <div class='header-menu-button-name-container'>
                <span class='header-menu-button-name ns'>Accéder au site</span>
            </div>
        </div>
        <div class='header-menu-button' onclick="window.location.href='deconnexion.php';">
            <div class='header-menu-button-icon-container'>
                <img class='header-menu-button-icon ns' src='image/icon/disconnect-icon.png' alt='Se déconnecter'>
            </div>
            <div class='header-menu-button-name-container'>
                <span class='header-menu-button-name ns'>Se déconnecter</span>
            </div>
        </div>
    </div>
</header>