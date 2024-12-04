package application.data ;

import java.io.File ;

/**
 * Classe d'accès aux données.
 * 
 * Date de dernière modification :
 * - Jeudi 20 novembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class DataLoader
{
    /**
     * Donne le fichier de données.
     * @return le fichier de données s'il existe, null sinon
     */
    public static File getDataFile()
    {
        File dataFile = new File("data/data.csv") ;
        if (dataFile.exists())
        {
            return dataFile ;
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
}