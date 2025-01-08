/**
 * Script relatif au menu latéral des pages du back-office.
 * 
 * Date de dernière modification :
 * - Vendredi 3 janvier 2025 -
 * 
 * Auteur : Victor Jockin
 * - Équipe 2B12 -
 */

document.addEventListener('DOMContentLoaded', function ()
{
    const currentPageName   = window.location.pathname.split('/').pop().split('.')[0] ;
    const buttonList        = document.querySelectorAll('.side-menu-button') ;
    for (const button of buttonList)
    {
        if (button.getAttribute('page') === currentPageName)
        {
            button.classList.add('active') ;
        }
    }
}) ;