<!--
Traitement de l'ajout d'une variante de produit.

Date de dernière modification :
- Mercredi 8 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->

<?php
    // connexion à la base de données
    require_once('../../include/Connect.inc.php') ;

    // ouverture de la session
    session_start() ;

    if (isset($_POST['add-product']))
    {
        
    }

    // redirection vers la page d'ajout / d'édition d'un produit
    unset($_SESSION['inputs']) ;
    unset($_SESSION['productInputErrors']) ;
    header('location: ../produits?.php') ;
    exit() ;
?>