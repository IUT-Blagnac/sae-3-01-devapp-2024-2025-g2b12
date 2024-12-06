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
        put("deviceName", new HashMap<>() {{
            put("abbreviation", "App.") ;
            put("full", "Nom d'appareil") ;
        }}) ;
        put("devEUI", new HashMap<>() {{
            put("abbreviation", "DevEUI") ;
            put("full", "Identifiant devEUI") ;
        }}) ;
        put("room", new HashMap<>() {{
            put("abbreviation", "Sal.") ;
            put("full", "Salle") ;
        }}) ;
        put("floor", new HashMap<>() {{
            put("abbreviation", "Ét.") ;
            put("full", "Étage") ;
        }}) ;
        put("Building", new HashMap<>() {{
            put("abbreviation", "Bât.") ;
            put("full", "Bâtiment") ;
        }}) ;
        put("temperature", new HashMap<>() {{
            put("abbreviation", "T") ;
            put("full", "Température") ;
            put("unit", "°C") ;
            put("inEvolutionGraphTitle", "de la température") ;
        }}) ;
        put("humidity", new HashMap<>() {{
            put("abbreviation", "HR") ;
            put("full", "Taux d'humidité") ;
            put("unit", "%") ;
            put("inEvolutionGraphTitle", "du taux d'humidité") ;
        }}) ;
        put("activity", new HashMap<>() {{
            put("abbreviation", "Act.") ;
            put("full", "Activité") ;
            put("unit", null) ;
            put("inEvolutionGraphTitle", "de l'activité") ;
        }}) ;
        put("co2", new HashMap<>() {{
            put("abbreviation", "CO2") ;
            put("full", "Concentration de CO2") ;
            put("unit", "ppm") ;
            put("inEvolutionGraphTitle", "de la concentration de CO2") ;
        }}) ;
        put("tvoc", new HashMap<>() {{
            put("abbreviation", "TVOC") ;
            put("full", "Composés organiques volatils totaux") ;
            put("unit", "µg/m3") ;
            put("inEvolutionGraphTitle", "des composés organiques volatils totaux") ;
        }}) ;
        put("illumination", new HashMap<>() {{
            put("abbreviation", "ECL") ;
            put("full", "Éclairage") ;
            put("unit", "lx") ;
            put("inEvolutionGraphTitle", "de l'éclairage") ;
        }}) ;
        put("infrared", new HashMap<>() {{
            put("abbreviation", "IR") ;
            put("full", "Infrarouges") ;
            put("unit", null) ;
            put("inEvolutionGraphTitle", "des infrarouges") ;
        }}) ;
        put("infrared_and_visible", new HashMap<>() {{
            put("abbreviation", "IR+V") ;
            put("full", "Infrarouges et visibles") ;
            put("unit", null) ;
            put("inEvolutionGraphTitle", "des infrarouges et visibles") ;
        }}) ;
        put("pressure", new HashMap<>() {{
            put("abbreviation", "P") ;
            put("full", "Pression atmosphérique") ;
            put("unit", "Pa") ;
            put("inEvolutionGraphTitle", "de la pression") ;
        }}) ;
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

    /**
     * Donne le titre pour un diagramme décrivant l'évolution d'un type de données.
     * @param pDataType un type de données
     * @return  le titre pour un diagramme d'évolution associé au type de données
     */
    public static String getEvolutionGraphTitle(String pDataType)
    {
        return  "Évolution "
            +   dataRepresentationMap.get(pDataType).get("inEvolutionGraphTitle")
            +   " en fonction du temps" ;
    }

    /**
     * Donne le type de données correspondant à un nom complet.
     * @param pFullTitle un nom complet de type de données
     * @return  le type de données correspondant au nom complet fourni
     */
    public static String getDataTypeByFullTitle(String pFullTitle)
    {
        for (Map.Entry<String, Map<String, String>> m : dataRepresentationMap.entrySet())
        {
            String fullTitle = m.getValue().get("full") ;
            if (fullTitle.equals(pFullTitle))
            {
                return m.getKey() ;
            }
        }
        return null ;
    }
}