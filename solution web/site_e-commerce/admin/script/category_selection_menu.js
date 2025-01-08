/**
 * Script relatif au menu de sélection de la catégorie du produit.
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

    if (currentPage.startsWith("produits.php?mode"))
    {
        // récupération des éléments HTML
        const openCsmButton     = document.getElementById('open-category-selection-menu-button') ;
        const csmSelectButton   = document.getElementById('category-selection-menu-select-button') ;
        const csmCancelButton   = document.getElementById('category-selection-menu-cancel-button') ;
        const csm               = document.getElementById('category-selection-menu') ;
        const csmContent        = document.getElementById('category-selection-menu-content') ;
        const hiddenInput       = document.getElementById('product-category-id') ;

        /**
         * Initialise l'état du bouton de sélection d'une catégorie.
         */
        function initOpenCsmButton()
        {   
            if (hiddenInput.value && hiddenInput.getAttribute('parentCategoryId'))
            {
                const selectedCategory = document.getElementById(hiddenInput.value) ;

                openCsmButton.innerHTML = '' ;

                const parentCategory = document.createElement('a') ;
                parentCategory.classList.add('link', 'parentCategory', 'ns') ;
                parentCategory.textContent = selectedCategory.getAttribute('parentCategory') ;
    
                const slash = document.createTextNode('\u00A0/\u00A0') ;
    
                const category = document.createElement('a') ;
                category.classList.add('link', 'category', 'ns') ;
                category.style.backgroundColor = window.getComputedStyle(selectedCategory).backgroundColor ;
                category.textContent = selectedCategory.textContent ;
    
                hiddenInput.value = selectedCategory.getAttribute('id') ;
                hiddenInput.setAttribute('parentCategoryId', selectedCategory.getAttribute('parentCategoryId')) ;
    
                openCsmButton.appendChild(parentCategory) ;
                openCsmButton.appendChild(slash) ;
                openCsmButton.appendChild(category) ;    
            }
        }

        /**
         * Ferme le pop-up avec animation.
         */
        function closePopUp()
        {
            csmContent.classList.remove('in') ;
            csmContent.classList.add('out') ;
            csm.classList.remove('in') ;
            csm.classList.add('out') ;
            setTimeout(function () {
                csm.style.display = 'none' ;
                csmContent.classList.remove('out') ;
                csmContent.classList.add('in') ;
                csm.classList.remove('out') ;
                csm.classList.add('in') ;
            }, 300) ;
        }

        // bouton d'ouverture
        openCsmButton.addEventListener('click', function () {
            const selectedCategory = document.getElementById(hiddenInput.value) ;
            if (selectedCategory)
            {
                const previousSelectedCategory = document.querySelector('.category.selected-item') ;
                if (previousSelectedCategory)
                {
                    previousSelectedCategory.classList.remove('selected-item') ;
                    previousSelectedCategory.classList.add('not-selected-item') ;
                }
                selectedCategory.classList.remove('not-selected-item') ;
                selectedCategory.classList.add('selected-item') ;
            }
            csm.style.display = 'flex' ;
        }) ;

        // bouton de validation
        csmSelectButton.addEventListener('click', function () { 
            const selectedCategory = document.querySelector('.category.selected-item') ;
            if (selectedCategory)
            {
                openCsmButton.innerHTML = '' ;

                const parentCategory = document.createElement('a') ;
                parentCategory.classList.add('link', 'parentCategory', 'ns') ;
                parentCategory.textContent = selectedCategory.getAttribute('parentCategory') ;
    
                const slash = document.createTextNode('\u00A0/\u00A0') ;
    
                const category = document.createElement('a') ;
                category.classList.add('link', 'category', 'ns') ;
                category.style.backgroundColor = window.getComputedStyle(selectedCategory).backgroundColor ;
                category.textContent = selectedCategory.textContent ;
    
                hiddenInput.value = selectedCategory.getAttribute('id') ;
                hiddenInput.setAttribute('parentCategoryId', selectedCategory.getAttribute('parentCategoryId')) ;
    
                openCsmButton.appendChild(parentCategory) ;
                openCsmButton.appendChild(slash) ;
                openCsmButton.appendChild(category) ;    

                closePopUp() ;
            }
        }) ;

        // bouton de fermeture
        csmCancelButton.addEventListener('click', function () { closePopUp() ; }) ;
        window.addEventListener('click', (event) => { if (event.target == csm) { closePopUp() ; } }) ;

        // options (catégories)
        document.querySelectorAll('.category').forEach(category => {
            category.addEventListener('click', function () {
                const selectedCategory = document.querySelector('.category.selected-item') ;
                if (selectedCategory)
                {
                    selectedCategory.classList.remove('selected-item') ;
                    this.classList.add('not-selected-item') ;
                }
                this.classList.remove('not-selected-item') ;
                this.classList.add('selected-item') ;
                document.querySelectorAll('.category').forEach(category => {
                    if (!category.classList.contains('selected-item'))
                    {
                        category.classList.add('not-selected-item') ;
                    }
                }) ;
            }) ;
        }) ;

        // initialisation de l'état du bouton de sélection d'une catégorie
        initOpenCsmButton() ;
    }
}) ;