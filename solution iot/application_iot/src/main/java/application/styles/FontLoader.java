package application.styles ;

import javafx.scene.text.Font ;

/**
 * Classe d'accès aux typographiques (fonts) de l'application.
 * 
 * Date de dernière modification :
 * - Samdei 30 novembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class FontLoader
{
    // déclaration des constantes
    private static final String FONT_DIRECTORY_PATH = "/application/font/Poppins/" ;

    /**
     * Donne la typographie (font) d'en-tête de fenêtre.
     * @return  la typographie (objet de type Font)
     */
    public static Font getWindowHeaderFont()
    {
        String fontPath = FONT_DIRECTORY_PATH + "Poppins-Bold.ttf" ;
        double fontSize = 28 ;
        return Font.loadFont(FontLoader.class.getResourceAsStream(fontPath), fontSize) ;
    }

    /**
     * Donne la typographie (font) d'en-tête de conteneur.
     * @return  la typographie (objet de type Font)
     */
    public static Font getContainerHeaderFont()
    {
        String fontPath = FONT_DIRECTORY_PATH + "Poppins-Bold.ttf" ;
        double fontSize = 18 ;
        return Font.loadFont(FontLoader.class.getResourceAsStream(fontPath), fontSize) ;
    }

    /**
     * Donne la typographie (font) d'en-tête de tableau.
     * @return  la typographie (objet de type Font)
     */
    public static Font getTableHeaderFont()
    {
        String fontPath = FONT_DIRECTORY_PATH + "Poppins-SemiBold.ttf" ;
        double fontSize = 14 ;
        return Font.loadFont(FontLoader.class.getResourceAsStream(fontPath), fontSize) ;
    }

    /**
     * Donne la typographie (font) de contenu.
     * @return  la typographie (objet de type Font)
     */
    public static Font getContentFont()
    {
        String fontPath = FONT_DIRECTORY_PATH + "Poppins-Light.ttf" ;
        double fontSize = 14 ;
        return Font.loadFont(FontLoader.class.getResourceAsStream(fontPath), fontSize) ;
    }

    /**
     * Donne la typographie (font) d'en-tête de donnée unique.
     * @return  la typographie (objet de type Font)
     */
    public static Font getSingleDataHeaderFont()
    {
        String fontPath = FONT_DIRECTORY_PATH + "Poppins-SemiBold.ttf" ;
        double fontSize = 14 ;
        return Font.loadFont(FontLoader.class.getResourceAsStream(fontPath), fontSize) ;
    }

    /**
     * Donne la typographie (font) de donnée unique.
     * @return  la typographie (objet de type Font)
     */
    public static Font getSingleDataFont()
    {
        String fontPath = FONT_DIRECTORY_PATH + "Poppins-Light.ttf" ;
        double fontSize = 20 ;
        return Font.loadFont(FontLoader.class.getResourceAsStream(fontPath), fontSize) ;
    }

    /**
     * Donne la typographie (font) de titre de graphique.
     * @return  la typographie (objet de type Font)
     */
    public static Font getGraphTitleFont()
    {
        String fontPath = FONT_DIRECTORY_PATH + "Poppins-Light.ttf" ;
        double fontSize = 16 ;
        return Font.loadFont(FontLoader.class.getResourceAsStream(fontPath), fontSize) ;
    }

    /**
     * Donne la typographie (font) de marque ("tick") de graphique.
     * @return  la typographie (objet de type Font)
     */
    public static Font getGraphTickFont()
    {
        String fontPath = FONT_DIRECTORY_PATH + "Poppins-Light.ttf" ;
        double fontSize = 14 ;
        return Font.loadFont(FontLoader.class.getResourceAsStream(fontPath), fontSize) ;
    }
}