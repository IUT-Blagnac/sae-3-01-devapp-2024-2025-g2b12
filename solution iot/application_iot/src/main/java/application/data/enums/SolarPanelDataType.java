package application.data.enums ;

/**
 * Classe d'énumération des types de données des panneaux solaires.
 * 
 * Date de dernière modification :
 * - Mardi 10 décembre 2024 -
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
            case LAST_UPDATE_TIME : name = "Dernière M.A.J." ; break ;
            case LIFE_TIME_DATA :   name = "Énergie cumulée totale" ; break ;
            case LAST_YEAR_DATA :   name = "Énergie annuelle" ; break ;
            case LAST_MONTH_DATA :  name = "Énergie mensuelle" ; break ;
            case LAST_DAY_DATA :    name = "Énergie quotidienne" ; break ;
            case CURRENT_POWER :    name = "Puissance" ; break ;
            case MEASURED_BY :      name = "Méthode de mesure" ; break ;
            default :               name = "-" ; break ;
        }
        return name ;
    }

    /**
     * Donne l'intitulé du type de données formaté pour lecture des données.
     * @return  l'intitulé du type de donnés formaté
     */
    public String getNameForDataReading()
    {
        return this.toString().toLowerCase() ;
    }

    /**
     * Donne la description du type de données.
     * @return  la description du type de données
     */
    public String getDescription()
    {
        String description ;
        switch (this)
        {
            case LAST_UPDATE_TIME : description = "Date de dernière mise à jour" ; break ;
            case LIFE_TIME_DATA :   description = "Énergie cumulée depuis déploiement" ; break ;
            case LAST_YEAR_DATA :   description = "Énergie produite au cours de la dernière année" ; break ;
            case LAST_MONTH_DATA :  description = "Énergie produite au cours du dernier mois" ; break ;
            case LAST_DAY_DATA :    description = "Énergie produite au cours du dernier jour" ; break ;
            case CURRENT_POWER :    description = "Puissance actuellement relevée" ; break ;
            case MEASURED_BY :      description = "Méthode de mesure des données" ; break ;
            default :               description = "-" ; break ;
        }
        return description ;
    }
}