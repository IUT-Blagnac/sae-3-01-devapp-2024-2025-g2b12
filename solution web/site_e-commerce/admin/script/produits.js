/**
 * Script relatif à la page de gestion des produits.
 * 
 * Date de dernière modification :
 * - Dimanche 5 janvier 2025 -
 * 
 * Auteur : Victor Jockin
 * - Équipe 2B12 -
 */

document.addEventListener('DOMContentLoaded', function ()
{
    // récupération de la page courante
    const currentUrl    = window.location.href
    const currentPage   = currentUrl.substring(currentUrl.lastIndexOf('/') + 1) ;

    if (currentPage == "produits.php")
    {
        // page de visualisation des produits
        // ----------------------------------

        // récupération des éléments HTML
        const productsTable     = document.getElementById('productsTable') ;
        const productRows       = productsTable.querySelectorAll('tr[productId]') ;
        const showAllButton     = document.getElementById('showAllButton') ;
        const hideAllButton     = document.getElementById('hideAllButton') ;
        const showMoreIcons     = document.querySelectorAll('.show-more-icon') ;

        // déclaration des variables globales
        let openVariantTablesCount = 0 ;    // nombre de listes de variantes de produits ouvertes

        /**
         * Affiche la liste des variantes pour le produit
         * de numéro {pProductId}.
         */
        function displayVariantsTable(pProductId)
        {
            // récupération des éléments HTML
            const productRow    = productsTable.querySelector(`tr[productId="${pProductId}"]`) ;
            const vtRow         = productRow.nextElementSibling ; // Variants Table Row
            const showMoreIcon  = productRow.querySelector('.show-more-icon') ;

            // traitement de l'affichage de la table des variantes de produit
            vtRow.style.display = (   vtRow.style.display == 'none'
                                || vtRow.style.display == ''    ) ? 'table-row' : 'none' ;
            
            // traitement de l'apparence du bouton "Voir plus" / "Voir moins"
            if (vtRow.style.display === 'table-row')
            {
                showMoreIcon.src = 'image/icon/show-less-icon.png' ;
                showMoreIcon.alt = 'Voir moins' ;
                openVariantTablesCount += 1 ;
                hideAllButton.classList.remove('disabled') ;
                if (openVariantTablesCount == productRows.length)
                {
                    showAllButton.classList.add('disabled') ;
                }
            }
            else
            {
                showMoreIcon.src = 'image/icon/show-more-icon.png' ;
                showMoreIcon.alt = 'Voir plus' ;
                openVariantTablesCount -= 1 ;
                showAllButton.classList.remove('disabled') ;
                if (openVariantTablesCount == 0)
                {
                    hideAllButton.classList.add('disabled') ;
                }
            }
        }

        /**
         * Affiche toutes les listes des variantes de produits.
         */
        function showAllVariantTables()
        {
            let i = 0 ;
            while (i < productRows.length)
            {            
                const row       = productRows[i] ;
                const productId = row.getAttribute('productId') ;
                if (row.nextElementSibling.style.display != 'table-row')
                {
                    displayVariantsTable(productId) ;
                }
                i++ ;
            }
        }

        /**
         * Cache toutes les listes des variantes de produits.
         */
        function hideAllVariantTables()
        {
            let i = 0 ;
            while (i < productRows.length)
            {
                const row       = productRows[i] ;
                const productId = row.getAttribute('productId') ;
                if (row.nextElementSibling.style.display == 'table-row')
                {
                    displayVariantsTable(productId) ;
                }
                i++ ;
            }
        }

        // initialisation des écouteurs d'évènements
        document.getElementById('showAllButton').addEventListener('click', showAllVariantTables) ;
        document.getElementById('hideAllButton').addEventListener('click', hideAllVariantTables) ;
        showMoreIcons.forEach(icon => { icon.addEventListener('click', function() { displayVariantsTable(icon.getAttribute('product-id')) ; }) ; }) ;
    }
    else if (currentPage.startsWith("produits.php?mode"))
    {
        // page d'ajout / d'édition d'un produit
        // =====================================

        /**
         * Met à jour le compteur de caractères d'un champ de saisie.
         * @param {HTMLElement} field       un champ de saisie
         * @param {HTMLElement} counter     le compteur de caractères du champ de saisie
         */
        function updateCharacterCounter(field, counter)
        {
            const maxLength     = field.maxLength ;
            const currentLength = field.value.length ;
            counter.textContent = `${maxLength - currentLength}/${maxLength}` ;
        }


        // champs de saisie du nom et de la description du produit
        // -------------------------------------------------------

        const nameField     = document.getElementById('product-name') ;
        const nameCounter   = document.getElementById('name-character-counter') ;
        const descField     = document.getElementById('product-description') ;
        const descCounter   = document.getElementById('description-character-counter') ;

        // initialisation des écouteurs d'évènements
        nameField.addEventListener('input', function () { updateCharacterCounter(nameField, nameCounter) ; }) ;
        descField.addEventListener('input', function () { updateCharacterCounter(descField, descCounter) ; }) ;

        // initialisaion des compteurs au chargement
        updateCharacterCounter(nameField, nameCounter) ;
        updateCharacterCounter(descField, descCounter) ;


        // menu de sélection de l'image
        // ----------------------------

        const productImage      = document.getElementById('product-image') ;
        const clearImageButton  = document.getElementById('clear-image-button') ;

        /**
         * Affiche l'aperçu de l'image du produit.
         */
        function showImagePreview()
        {
            var file = event.target.files[0] ;
            if (file)
            {
                var reader = new FileReader() ;
                reader.onload = function(e)
                {
                    var imagePreview = document.getElementById('image-preview') ;
                    imagePreview.innerHTML = '' ;
                    imagePreview.style.backgroundImage      = `url('${e.target.result}')` ;
                    imagePreview.style.backgroundSize       = 'cover' ;
                    imagePreview.style.backgroundPosition   = 'center' ;
                    imagePreview.style.backgroundRepeat     = 'no-repeat' ;
                } ;
                reader.readAsDataURL(file) ;
                clearImageButton.classList.remove('disabled') ;
            }
        }

        // affichage de l'image du produit dans l'aperçu
        productImage.addEventListener('change', function(event) { showImagePreview() ; }) ;

        // nettoyage de l'image du produit
        clearImageButton.addEventListener('click', function() {
            var imagePreview = document.getElementById('image-preview') ;
            imagePreview.innerHTML = '<span>Aucune image chargée</span>' ;
            imagePreview.style.backgroundImage = '' ;
            document.getElementById('product-image').value = '' ;
            clearImageButton.classList.add('disabled') ;
        }) ;
    }
}) ;