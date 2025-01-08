/**
 * Utilitaire d'alertes.
 * 
 * Date de dernière modification :
 * - Mercredi 8 janvier 2025 -
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
        /**
         * Affiche une alerte demandant la confirmation du rechargement
         * du formulaire.
         */
        function displayFormReloadAlert()
        {
            event.preventDefault() ;
            event.returnValue = '' ;
        }

        window.addEventListener('keydown', (event) => {
            if ((event.ctrlKey && event.key === 'r') || event.key === 'F5')
            {
                displayFormReloadAlert() ;
            }
        });
    }
}) ;