package application.data.enums ;

/**
 * Classe d'énumération des types de données des panneaux solaires.
 * 
 * Date de dernière modification :
 * - Lundi 9 décembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public enum SolarPanelDataType
{
    // déclaration des types de données des panneaux solaires
    LAST_UPDATE_TIME,
    LAST_DAY_DATA,
    LAST_MONTH_DATA,
    LAST_YEAR_DATA,
    LIFE_TIME_DATA,
    CURRENT_POWER,
    MEASURED_BY ;

    /**
     * Donne l'intitulé du type de données formaté pour affichage.
     * @return  l'intitulé du type de donnés formaté
     */
    public String getNameForDisplay()
    {
        String name ;
        switch (this)
        {
            case LAST_UPDATE_TIME : name = "Heure de dernière mise à jour" ; break ;
            case LIFE_TIME_DATA :   name = "Données cumulées" ; break ;
            case LAST_YEAR_DATA :   name = "Données de la dernière année" ; break ;
            case LAST_MONTH_DATA :  name = "Données du dernier mois" ; break ;
            case LAST_DAY_DATA :    name = "Données du dernier jour" ; break ;
            case CURRENT_POWER :    name = "Puissance actuelle" ; break ;
            case MEASURED_BY :      name = "Source" ; break ;
            default :               name = "-" ; break ;
        }
        return name ;
    }

    public String getDescription()
    {
        return "En cours de développement..." ;
    }
}