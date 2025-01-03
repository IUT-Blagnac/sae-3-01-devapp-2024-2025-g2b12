<!--
Fonctions utilitaires.

Date de dernière modification :
- Jeudi 2 janvier 2025 -

Auteur : Victor Jockin
- Équipe 2B12 -
-->
<?php
    /**
     * Génère une couleur à partir d'une chaîne de caractères.
     */
    function generateColor($string)
    {
        // calcul du hachage de la chaîne afin de générer une couleur
        $color = sprintf("#%06x", crc32($string) & 0xFFFFFF) ;

        // extraction des valeurs RGB
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x") ;

        // calcul de la luminosité de la couleur
        $luminosity = 0.299 * $r + 0.587 * $g + 0.114 * $b ;

        // assombrissement de la couleur si trop claire
        if ($luminosity > 150)
        {
            $r = max(0, $r - 50) ;
            $g = max(0, $g - 50) ;
            $b = max(0, $b - 50) ;
        }

        return sprintf("#%02x%02x%02x", $r, $g, $b) ;
    }
?>