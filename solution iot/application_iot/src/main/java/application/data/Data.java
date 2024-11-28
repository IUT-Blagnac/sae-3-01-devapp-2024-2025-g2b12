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
public class Data
{
    /**
     * Donne le fichier de données.
     * @return le fichier de données s'il existe, null s'il n'existe pas
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
}