<!--
Déconnexion de l'administrateur.

Date de dernière modification :
- Lundi 6 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->

<?php
    session_start() ;
    session_unset() ;
    session_destroy() ;
    header("location: connexion.php") ;
    exit() ;
?>