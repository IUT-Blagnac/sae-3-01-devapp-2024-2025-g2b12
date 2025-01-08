/**
 * Script relatif au pop-up de visualisation de l'image d'une variante de produit.
 * 
 * Date de dernière modification :
 * - Mardi 7 janvier 2025 -
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
        const openVivButton         = document.getElementById('open-variant-image-view-button') ;
        const vivCloseButton        = document.getElementById('variant-image-view-close-button') ;
        const viv                   = document.getElementById('variant-image-view') ;
        const vivContent            = document.getElementById('variant-image-view-content') ;
        const variantImageInput     = document.getElementById('variant-image') ;
        const variantImageContainer = document.getElementById('variant-image-view-container') ;
        
        /**
         * Ferme le pop-up avec animation.
         */
        function closePopUp()
        {
            vivContent.classList.remove('in') ;
            vivContent.classList.add('out') ;
            viv.classList.remove('in') ;
            viv.classList.add('out') ;
            setTimeout(function () {
                viv.style.display = 'none' ;
                vivContent.classList.remove('out') ;
                vivContent.classList.add('in') ;
                viv.classList.remove('out') ;
                viv.classList.add('in') ;
            }, 300) ;
        }

        // visualisation de l'image de la variante de produit
        variantImageInput.addEventListener('change', function (event) {
            const file = event.target.files[0] ;
            if (file) {
                const reader = new FileReader() ;
                reader.onload = function (e)
                {
                    variantImageContainer.style.backgroundImage = `url(${e.target.result})` ;
                } ;
                reader.readAsDataURL(file) ;
            }
        }) ;

        // bouton d'ouverture
        openVivButton.addEventListener('click', function () { viv.style.display = 'flex' ; }) ;

        // bouton de fermeture
        vivCloseButton.addEventListener('click', function () { closePopUp() ; }) ;
        window.addEventListener('click', (event) => { if (event.target == viv) { closePopUp() ; } }) ;
    }
}) ;