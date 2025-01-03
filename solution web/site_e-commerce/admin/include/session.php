<!--
Espace administrateur sécurisé (session).

Date de dernière modification :
- Lundi 30 décembre 2024 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<?php
    // ouverture de la session
    session_start() ;
    // vérification de l'existence de la session
    if (!isset($_SESSION['admin_id']))
    {
        // redirection vers le formulaire de connexion administrateur
        header("location: connexion.php") ;
        exit() ;
    }
?>