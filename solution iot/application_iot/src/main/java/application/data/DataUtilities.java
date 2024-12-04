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
     * Donne l'affichage format court d'un type de données.
     * @param pDataType   un type de données
     * @return  l'affichage format court du type de données
     */
    public static String getShortDisplayDataType(String pDataType)
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

    /**
     * Donne l'affichage format long d'un type de données.
     * @param pDataType   un type de données
     * @return  l'affichage format long du type de données
     */
    public static String getLongDisplayDataType(String pDataType)
    {
        String displayDataType = pDataType ;
        switch (pDataType)
        {
            case "deviceName" : displayDataType = "Nom d'appareil" ; break ;
            case "devEUI" : displayDataType = "Identifiant devEUI" ; break ;
            case "room" : displayDataType = "Salle" ; break ;
            case "floor" : displayDataType = "Étage" ; break ;
            case "Building" : displayDataType = "Bâtiment" ; break ;
            case "temperature" : displayDataType = "Température (°C)" ; break ;
            case "humidity" : displayDataType = "Taux d'humidité (%)" ; break ;
            case "activity" : displayDataType = "Activité" ; break ;
            case "co2" : displayDataType = "Concentration de CO2 (ppm)" ; break ;
            case "tvoc" : displayDataType = "Composés organiques volatils totaux (µg/m3)" ; break ;
            case "illumination" : displayDataType = "Éclairage (lx)" ; break ;
            case "infrared" : displayDataType = "Infrarouges" ; break ;
            case "infrared_and_visible" : displayDataType = "Infrarouges et visibles" ; break ;
            case "pressure" : displayDataType = "Pression atmosphérique (Pa)" ; break ;
            default : displayDataType = "Non défini" ;
        }
        return displayDataType ;
    }
}