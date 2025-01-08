<!--
Traitement de l'ajout d'un produit.

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
        // récupération des saisies utilisateur
        $inputs = [ 'product-id'            => trim(htmlentities($_POST['product-id'])),
                    'product-name'          => trim(htmlentities($_POST['product-name'])),
                    'product-description'   => trim(htmlentities($_POST['product-description'])),
                    'product-category-id'   => trim(htmlentities($_POST['product-category-id']))    ] ;

        // vérification de la validité des saisies utilisateur
        $inputErrors = [] ;
        if (!isset($inputs['product-id']) || empty($inputs['product-id']))
        {
            $inputErrors[] = "Numéro de produit (ID) non renseigné." ;
        }
        if (!isset($inputs['product-name']) || empty($inputs['product-name']))
        {
            $inputErrors[] = "Nom de produit non renseigné." ;
        }
        if (!isset($inputs['product-category-id']) || empty($inputs['product-category-id']))
        {
            $inputErrors[] = "Catégorie du produit non renseignée." ;
        }

        // récupération éventuelle de l'image du produit
        if (!empty($_FILES['product-image']['name']))
        {
            if ($_FILES['product-image']['error'] != 0)
            {
                $inputErrors[] = "Erreur de téléchargement de l'image." ;
            }
            else
            {
                $isValid = true ;
                $file = pathinfo($_FILES['product-image']['name']) ;
                $fileExtension = $file['extension'] ;
                $authorisedExtensions = array("png", "jpg", "jpeg") ;
                if (!in_array($fileExtension, $authorisedExtensions))
                {
                    $inputErrors[] = "Extension de l'image du produit invalide." ;
                    $isValid = false ;
                }
                if (!(5000000 > $_FILES['product-image']['size']) && $isValid)
                {
                    $inputErrors[] = "Image du produit trop volumineuse." ;
                    $isValid = false ;
                }
            }
        }

        // redirection vers la page d'ajout d'un produit en cas d'erreurs de saisie
        if (!empty($inputErrors))
        {
            $_SESSION['inputs']             = $inputs ;
            $_SESSION['productInputErrors'] = $inputErrors ;
            header('location: ../produits.php?mode=ajout') ;
            exit() ;
        }

        // insertion du produit dans la base de données
        $productAdditionQuery = $conn->prepare(
            "INSERT INTO PRODUIT (idProduit, nomProduit, descProduit, idCategorie)
            VALUES (?, ?, ?, ?)"
        ) ;
        $productAdditionQuery->execute([
            $_POST['product-id'],
            $_POST['product-name'],
            $_POST['product-description'],
            $_POST['product-category-id']
        ]) ;

        // enregistrement de l'image sur le serveur
        move_uploaded_file($_FILES['product-image']['tmp_name'], "../../image/produits/prod".$inputs['product-id']."-default.".$fileExtension) ;
    }

    // redirection vers la page de gestion des produis
    unset($_SESSION['inputs']) ;
    unset($_SESSION['productInputErrors']) ;
    header('location: ../produits.php') ;
    exit() ;
?>