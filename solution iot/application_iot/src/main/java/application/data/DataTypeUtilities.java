package application.data ;

import java.util.ArrayList;
import java.util.HashMap ;
import java.util.List ;
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
    private static final Map<String, Map<String, String>> DATA_REPRESENTATION_MAP = new HashMap<>()
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
            put("evolutionGraphTitle", "Évolution de la température en fonction du temps") ;
            put("comparisonGraphTitle", "Températures par salle") ;
            put("alert", "Alerte température") ;
        }}) ;
        put("humidity", new HashMap<>() {{
            put("abbreviation", "HR") ;
            put("full", "Taux d'humidité") ;
            put("unit", "%") ;
            put("evolutionGraphTitle", "Évolution du taux d'humidité en fonction du temps") ;
            put("comparisonGraphTitle", "Taux d'humidité par salle") ;
            put("alert", "Alerte HR") ;
        }}) ;
        put("activity", new HashMap<>() {{
            put("abbreviation", "Act.") ;
            put("full", "Activité") ;
            put("unit", null) ;
            put("evolutionGraphTitle", "Évolution de l'activité en fonction du temps") ;
            put("comparisonGraphTitle", "Activités par salle") ;
            put("alert", "Alerte activité") ;
        }}) ;
        put("co2", new HashMap<>() {{
            put("abbreviation", "CO2") ;
            put("full", "Concentration de CO2") ;
            put("unit", "ppm") ;
            put("evolutionGraphTitle", "Évolution de la concentration de CO2 en fonction du temps") ;
            put("comparisonGraphTitle", "Concentrations de CO2 par salle") ;
            put("alert", "Alerte CO2") ;
        }}) ;
        put("tvoc", new HashMap<>() {{
            put("abbreviation", "TVOC") ;
            put("full", "T.V.O.C.") ;
            put("unit", "µg/m³") ;
            put("evolutionGraphTitle", "Évolution des composés organiques volatils totaux en fonction du temps") ;
            put("comparisonGraphTitle", "Composés organiques volatils totaux par salle") ;
            put("alert", "Alerte T.V.O.C.") ;
        }}) ;
        put("illumination", new HashMap<>() {{
            put("abbreviation", "ECL") ;
            put("full", "Éclairage") ;
            put("unit", "lx") ;
            put("evolutionGraphTitle", "Évolution de l'éclairage en fonction du temps") ;
            put("comparisonGraphTitle", "Éclairages par salle") ;
            put("alert", "Alerte éclairage") ;
        }}) ;
        put("infrared", new HashMap<>() {{
            put("abbreviation", "IR") ;
            put("full", "Infrarouges") ;
            put("unit", null) ;
            put("evolutionGraphTitle", "Évolution des infrarouges en fonction du temps") ;
            put("comparisonGraphTitle", "Infrarouges par salle") ;
            put("alert", "Alerte I.R.") ;
        }}) ;
        put("infrared_and_visible", new HashMap<>() {{
            put("abbreviation", "IR+V") ;
            put("full", "Infrarouges et visibles") ;
            put("unit", null) ;
            put("evolutionGraphTitle", "Évolution des infrarouges et visibles en fonction du temps") ;
            put("comparisonGraphTitle", "infrarouges et visibles par salle") ;
            put("alert", "Alerte I.R. + V.") ;
        }}) ;
        put("pressure", new HashMap<>() {{
            put("abbreviation", "P") ;
            put("full", "Pression atmosphérique") ;
            put("unit", "Pa") ;
            put("evolutionGraphTitle", "Évolution de la pression en fonction du temps") ;
            put("comparisonGraphTitle", "Pressions par salle") ;
            put("alert", "Alerte pression") ;
        }}) ;
    }} ;

    /**
     * Donne l'abréviation d'un type de données.
     * @param pDataType un type de données
     * @return  l'abréviation du type de données
     */
    public static String getAbbreviation(String pDataType)
    {
        return DATA_REPRESENTATION_MAP.get(pDataType).get("abbreviation") ;
    }

    /**
     * Donne l'intitulé complet d'un type de données.
     * @param pDataType un type de données
     * @return  l'intitulé complet du type de données
     */
    public static String getFullTitle(String pDataType)
    {
        return DATA_REPRESENTATION_MAP.get(pDataType).get("full") ;
    }

    /**
     * Donne l'intitulé complet de chaque type de données d'une liste.
     * @param pDataTypeList une liste de types de données
     * @return  les intitulés complets des types de données de la liste
     */
    public static List<String> getAllFullTitles(List<String> pDataTypeList)
    {
        List<String> fullTitleList = new ArrayList<>() ;
        for (int i = 0 ; i < pDataTypeList.size() ; i++)
        {
            fullTitleList.add(DataTypeUtilities.getFullTitle(pDataTypeList.get(i))) ;
        }
        return fullTitleList ;
    }

    /**
     * Donne l'unité d'un type de données.
     * @param pDataType un type de données
     * @return  l'unité du type de données
     */
    public static String getUnit(String pDataType)
    {
        return DATA_REPRESENTATION_MAP.get(pDataType).get("unit") ;
    }

    /**
     * Donne le titre pour un diagramme décrivant l'évolution d'un type de données.
     * @param pDataType un type de données
     * @return  le titre pour un diagramme d'évolution
     */
    public static String getEvolutionGraphTitle(String pDataType)
    {
        return DATA_REPRESENTATION_MAP.get(pDataType).get("evolutionGraphTitle") ;
    }

    /**
     * Donne le titre pour un diagramme de comparaison d'un type de données par salle.
     * @param pDataType un type de données
     * @return  le titre pour un diagramme de comparaison
     */
    public static String getComparisonGraphTitle(String pDataType)
    {
        return DATA_REPRESENTATION_MAP.get(pDataType).get("comparisonGraphTitle") ;
    }

    /**
     * Donne le titre pour une alerte concernant un type de données.
     * @param pDataType un type de données
     * @return  le titre pour une alerte
     */
    public static String getAlertTitle(String pDataType)
    {
        return DATA_REPRESENTATION_MAP.get(pDataType).get("alert") ;
    }

    /**
     * Donne le type de données correspondant à un nom complet.
     * @param pFullTitle un nom complet de type de données
     * @return  le type de données correspondant au nom complet fourni
     */
    public static String getDataTypeByFullTitle(String pFullTitle)
    {
        for (Map.Entry<String, Map<String, String>> m : DATA_REPRESENTATION_MAP.entrySet())
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