package application.data ;

import application.data.enums.Room ;
import application.data.enums.RoomDataType ;
import application.data.enums.SolarPanelDataType ;

import java.io.File ;
import java.util.Arrays ;
import java.util.List ;

/**
 * Classe d'accès aux données.
 * 
 * Date de dernière modification :
 * - Samedi 7 décembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class DataLoader
{
    /**
     * Donne le fichier des données de toutes les salles.
     * @return le fichier de données s'il existe, null sinon
     */
    public static File getAllRoomDataFile()
    {
        File allRoomDataFile = new File("data/data.csv") ;
        if (allRoomDataFile.exists())
        {
            return allRoomDataFile ;
        }
        return null ;
    }

    /**
     * Donne le fichier d'alertes.
     * @return le fichier d'alertes s'il existe, null sinon
     */
    public static File getAlertFile()
    {
        File alertFile = new File("data/alert.csv") ;
        if (alertFile.exists())
        {
            return alertFile ;
        }
        return null ;
    }

    /**
     * Donne le fichier des données d'une salle.
     * @param pRoomName le nom d'une salle
     * @return le fichier d'alertes s'il existe, null sinon
     */
    public static File getRoomDataFile(String pRoomName)
    {
        File roomDataFile = new File("data/"+pRoomName+".csv") ;
        if (roomDataFile.exists())
        {
            return roomDataFile ;
        }
        return null ;
    }

    /**
     * Donne la liste des salles existantes.
     * @return  la liste des salles
     */
    public static List<Room> getRoomList()
    {
        return Arrays.asList(Room.values()) ;
    }

    /**
     * Donne la liste des types de données des salles.
     * @return  la liste des types de données
     */
    public static List<RoomDataType> getRoomDataTypeList()
    {
        return Arrays.asList(RoomDataType.values()) ;
    }

    /**
     * Donne la liste des types de données des panneaux solaires.
     * @return  la liste des types de données
     */
    public static List<SolarPanelDataType> getSolarPanelDataTypeList()
    {
        return Arrays.asList(SolarPanelDataType.values()) ;
    }
}