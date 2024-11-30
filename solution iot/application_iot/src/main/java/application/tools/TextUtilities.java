package application.tools ;

import javafx.scene.text.Font ;
import javafx.scene.text.Text ;

/**
 * Classe utilitaire fournissant des méthodes relatives
 * à des éléments textuels.
 * 
 * Date de dernière modification :
 * - Samedi 30 novembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class TextUtilities
{
    /**
     * Donne la largeur minimale d'un composant graphique textuel
     * afin que celui-ci puisse afficher la totalité de la chaîne
     * de caractères qu'il contient.
     * @param pString   la chaîne de caractères contenue dans le
     *                  composant graphique
     * @param pFont     la typographie (font) appliquée au texte
     *                  contenu du composant graphique
     * @return  la largeur minimale à définir pour le composant graphique
     */
    public static double getMinWidthFromString(String pString, Font pFont)
    {
        Text text = new Text(pString) ;
        text.setFont(pFont) ;
        return text.getLayoutBounds().getWidth() + 20 ;
    }
}