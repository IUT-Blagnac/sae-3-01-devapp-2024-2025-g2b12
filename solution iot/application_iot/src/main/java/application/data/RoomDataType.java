package application.data ;

/**
 * Classe d'énumération des types de données des salles.
 * 
 * Date de dernière modification :
 * - Dimanche 8 décembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public enum RoomDataType
{
    // déclaration des types de données des salles.
    TEMPERATURE,
    HUMIDITY,
    ACTIVITY,
    CO2,
    TVOC,
    ILLUMINATION,
    INFRARED,
    INFRARED_AND_VISIBLE,
    PRESSURE ;

    /**
     * Donne l'intitulé du type de données formaté pour affichage.
     * @return  l'intitulé du type de donnés formaté
     */
    public String getNameForDisplay()
    {
        return DataTypeUtilities.getFullTitle(this.name().toLowerCase()) ;
    }

    /**
     * Donne l'unité du type de données.
     * @return  l'unité du type de données
     */
    public String getUnit()
    {
        return DataTypeUtilities.getUnit(this.name().toLowerCase()) ;
    }

    /**
     * Donne le nom de la salle formaté pour création d'un topic MQTT.
     * @return  le nom de la salle formaté
     */
    public String getDefaultThreshold()
    {
        return DataTypeUtilities.getDefaultThreshold(this.name().toLowerCase()) ;
    }
}