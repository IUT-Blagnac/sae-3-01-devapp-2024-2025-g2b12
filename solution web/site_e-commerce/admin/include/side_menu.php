<!--
Menu latéral des pages du back-office.

Date de dernière modification :
- Mardi 31 décembre 2024 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<div class='side-menu-container'>
    <div class='side-menu-header'>
        <span>MENU HEADER</span>
    </div>
    <div class='side-menu-content'>
        <div class='side-menu-button' page='tableau_de_bord' onclick="window.location.href='index.php';">
            <div class='side-menu-button-icon-container'>
                <img class='side-menu-button-icon ns' src='image/icon/dashboard-button.png' alt='Tableau de bord'>
            </div>
            <div class='side-menu-button-name-container'>
                <span class='side-menu-button-name ns'>Tableau de bord</span>
            </div>
        </div>
        <div class='side-menu-button' page='produits' onclick="window.location.href='produits.php';">
            <div class='side-menu-button-icon-container'>
                <img class='side-menu-button-icon ns' src='image/icon/products-button.png' alt='Produits'>
            </div>
            <div class='side-menu-button-name-container'>
                <span class='side-menu-button-name ns'>Produits</span>
            </div>
        </div>
    </div>
</div>
<script>
    const currentPageName   = window.location.pathname.split('/').pop().split('.')[0] ;
    const buttonList        = document.querySelectorAll('.side-menu-button') ;
    for (const button of buttonList)
    {
        if (button.getAttribute('page') === currentPageName)
        {
            button.classList.add('active') ;
        }
    }
</script>