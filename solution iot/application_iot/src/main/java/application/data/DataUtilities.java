package application.data ;

/**
 * Classe utilitaire fournissant des méthodes relatives aux données.
 * 
 * Date de dernière modification :
 * - Samedi 30 novembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class DataUtilities
{
    /**
     * Donne l'affichage d'un type de données.
     * @param pDataType   un type de données
     * @return  l'affichage du type de données
     */
    public static String getDisplayDataType(String pDataType)
    {
        String displayDataType = pDataType ;
        switch (pDataType)
        {
            case "deviceName" : displayDataType = "Nom d'appareil" ; break ;
            case "devEUI" : displayDataType = "Identifiant devEUI" ; break ;
            case "room" : displayDataType = "Salle" ; break ;
            case "floor" : displayDataType = "Étage" ; break ;
            case "Building" : displayDataType = "Bâtiment" ; break ;
            case "temperature" : displayDataType = "T (°C)" ; break ;
            case "humidity" : displayDataType = "HR (%)" ; break ;
            case "activity" : displayDataType = "Act." ; break ;
            case "co2" : displayDataType = "CO2 (ppm)" ; break ;
            case "tvoc" : displayDataType = "TVOC (µg/m3)" ; break ;
            case "illumination" : displayDataType = "ECL (lx)" ; break ;
            case "infrared" : displayDataType = "IR" ; break ;
            case "infrared_and_visible" : displayDataType = "IR + V" ; break ;
            case "pressure" : displayDataType = "P (Pa)" ; break ;
            default : displayDataType = "N.D." ;
        }
        return displayDataType ;
    }
}