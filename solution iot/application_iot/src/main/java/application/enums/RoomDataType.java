package application.enums ;

import application.data.DataTypeUtilities ;

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
    // déclaration des types de données des salles
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
     * Donne l'intitulé du type de données formaté pour lecture des données.
     * @return  l'intitulé du type de donnés formaté
     */
    public String getNameForDataReading()
    {
        return this.toString().toLowerCase() ;
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
     * Donne le seuil d'alerte par défaut du type de données.
     * @return  le seuil d'alerte par défaut
     */
    public String getDefaultThreshold()
    {
        return DataTypeUtilities.getDefaultThreshold(this.name().toLowerCase()) ;
    }

    /**
     * Donne le seuil d'alerte minimal du type de données.
     * @return  le seuil d'alerte minimal
     */
    public double getMinThreshold()
    {
        double minThreshold ;
        switch (this)
        {
            case TEMPERATURE :          minThreshold = -50 ; break ;
            case HUMIDITY :             minThreshold = 0 ; break ;
            case ACTIVITY :             minThreshold = 0 ; break ;
            case CO2 :                  minThreshold = 0 ; break ;
            case TVOC :                 minThreshold = 0 ; break ;
            case ILLUMINATION :         minThreshold = 0 ; break ;
            case INFRARED :             minThreshold = 0 ; break ;
            case INFRARED_AND_VISIBLE : minThreshold = 0 ; break ;
            case PRESSURE :             minThreshold = 0 ; break ;
            default :                   minThreshold = 0 ; break ;
        }
        return minThreshold ;
    }

    /**
     * Donne le seuil d'alerte maximal du type de données.
     * @return  le seuil d'alerte maximal
     */
    public double getMaxThreshold()
    {
        double maxThreshold ;
        switch (this)
        {
            case TEMPERATURE :          maxThreshold = 100 ; break ;
            case HUMIDITY :             maxThreshold = 100 ; break ;
            case ACTIVITY :             maxThreshold = 100 ; break ;
            case CO2 :                  maxThreshold = 5000 ; break ;
            case TVOC :                 maxThreshold = 60000 ; break ;
            case ILLUMINATION :         maxThreshold = 1000 ; break ;
            case INFRARED :             maxThreshold = 100 ; break ;
            case INFRARED_AND_VISIBLE : maxThreshold = 100 ; break ;
            case PRESSURE :             maxThreshold = 20000 ; break ;
            default :                   maxThreshold = 0 ; break ;
        }
        return maxThreshold ;
    }
}