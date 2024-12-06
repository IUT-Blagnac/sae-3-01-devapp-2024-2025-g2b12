package application.data ;

import java.util.HashMap ;
import java.util.Map ;

/**
 * Classe utilitaire fournissant des méthodes relatives aux types données.
 * 
 * Date de dernière modification :
 * - Vendredi 6 décembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class DataTypeUtilities
{
    // déclaration des attributs (constantes)
    private static final Map<String, Map<String, String>> dataRepresentationMap = new HashMap<>()
    {{
        put("deviceName",           new HashMap<>() {{ put("abbreviation", "App.") ;    put("full", "Nom d'appareil") ;                         put("unit", null) ; }}) ;
        put("devEUI",               new HashMap<>() {{ put("abbreviation", "DevEUI") ;  put("full", "Identifiant devEUI") ;                     put("unit", null) ; }}) ;
        put("room",                 new HashMap<>() {{ put("abbreviation", "Sal.") ;    put("full", "Salle") ;                                  put("unit", null) ; }}) ;
        put("floor",                new HashMap<>() {{ put("abbreviation", "Ét.") ;     put("full", "Étage") ;                                  put("unit", null) ; }}) ;
        put("Building",             new HashMap<>() {{ put("abbreviation", "Bât.") ;    put("full", "Bâtiment") ;                               put("unit", null) ; }}) ;
        put("temperature",          new HashMap<>() {{ put("abbreviation", "T") ;       put("full", "Température") ;                            put("unit", "°C") ; }}) ;
        put("humidity",             new HashMap<>() {{ put("abbreviation", "HR") ;      put("full", "Taux d'humidité") ;                        put("unit", "%") ; }}) ;
        put("activity",             new HashMap<>() {{ put("abbreviation", "Act.") ;    put("full", "Activité") ;                               put("unit", null) ; }}) ;
        put("co2",                  new HashMap<>() {{ put("abbreviation", "CO2") ;     put("full", "Concentration de CO2") ;                   put("unit", "ppm") ; }}) ;
        put("tvoc",                 new HashMap<>() {{ put("abbreviation", "TVOC") ;    put("full", "Composés organiques volatils totaux") ;    put("unit", "µg/m3") ; }}) ;
        put("illumination",         new HashMap<>() {{ put("abbreviation", "ECL") ;     put("full", "Éclairage") ;                              put("unit", "lx") ; }}) ;
        put("infrared",             new HashMap<>() {{ put("abbreviation", "IR") ;      put("full", "Infrarouges") ;                            put("unit", null) ; }}) ;
        put("infrared_and_visible", new HashMap<>() {{ put("abbreviation", "IR+V") ;    put("full", "Infrarouges et visibles") ;                put("unit", null) ; }}) ;
        put("pressure",             new HashMap<>() {{ put("abbreviation", "P") ;       put("full", "Pression atmosphérique") ;                 put("unit", "Pa") ; }}) ;
    }} ;

    /**
     * Donne l'abréviation d'un type de données.
     * @param pDataType un type de données
     * @return  l'abréviation du type de données
     */
    public static String getAbbreviation(String pDataType)
    {
        return dataRepresentationMap.get(pDataType).get("abbreviation") ;
    }

    /**
     * Donne l'intitulé complet d'un type de données.
     * @param pDataType un type de données
     * @return  l'intitulé complet du type de données
     */
    public static String getFullTitle(String pDataType)
    {
        return dataRepresentationMap.get(pDataType).get("full") ;
    }

    /**
     * Donne l'unité d'un type de données.
     * @param pDataType un type de données
     * @return  l'unité du type de données
     */
    public static String getUnit(String pDataType)
    {
        return dataRepresentationMap.get(pDataType).get("unit") ;
    }
}