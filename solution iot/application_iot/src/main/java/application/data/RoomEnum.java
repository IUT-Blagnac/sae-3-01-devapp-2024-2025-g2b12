package application.data ;

/**
 * Classe d'énumération des salles existantes.
 * 
 * Date de dernière modification :
 * - Dimanche 8 décembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public enum RoomEnum
{
    // déclaration des salles existantes
    B110,
    B111,
    B201,
    B202,
    B203,
    B001,
    B002,
    B003,
    B101,
    B102,
    B103,
    B105,
    B106,
    B108,
    B109,
    B112,
    B113,
    B212,
    B217,
    B219,
    B234,
    C004,
    C006,
    D001,
    E003,
    E004,
    E001,
    E006,
    E007,
    E101,
    E102,
    E103,
    E104,
    E105,
    E106,
    E100,
    E208,
    E209,
    E206,
    E207,
    E210,
    Salle_conseil,
    A007,
    Local_velo,
    Foyer_personnels,
    Foyer_etudiants_1,
    Foyer_etudiants_2,
    hall_1,
    hall_2,
    amphi1,
    C101,
    C102,
    C002 ;

    /**
     * Donne le nom de la salle formaté pour affichage.
     * @return  le nom de la salle formaté
     */
    public String getNameForDisplay()
    {
        return this.name().replace("_", " ").toUpperCase() ;
    }

    /**
     * Donne le nom de la salle formaté pour création d'un topic MQTT.
     * @return  le nom de la salle formaté
     */
    public String getNameForTopic()
    {
        return this.name().replace("_", "-") ;
    }
}