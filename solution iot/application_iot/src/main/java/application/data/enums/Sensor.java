package application.data.enums ;

/**
 * Classe d'énumération des types de capteurs.
 * 
 * Date de dernière modification :
 * - Mardi 10 décembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public enum Sensor
{
    // déclaration des types de capteurs
    AM107,
    SOLAREDGE ;

    /**
     * Donne le nom du type de capteurs formaté pour création d'un topic MQTT.
     * @return  le nom du type de capteurs formaté
     */
    public String getNameForTopic()
    {
        String nameForTopic ;
        switch (this)
        {
            case AM107 :        nameForTopic = "AM107" ; break ;
            case SOLAREDGE :    nameForTopic = "solaredge" ; break ;
            default :           nameForTopic = "" ; break ;
        }
        return nameForTopic ;
    }
}