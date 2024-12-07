package application.tools ;

import java.io.File ;
import java.io.FileReader ;
import java.util.ArrayList ;
import java.util.Arrays ;
import java.util.List ;

import com.opencsv.CSVParser ;
import com.opencsv.CSVParserBuilder ;
import com.opencsv.CSVReader ;
import com.opencsv.CSVReaderBuilder ;

import application.data.DataLoader;

/**
 * Classe utilitaire fournissant des méthodes de lecture de fichiers de données.
 * 
 * Date de dernière modification :
 * - Jeudi 20 novembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class DataFileReading
{
    /**
     * Donne la liste des en-têtes d'un fichier de données au format CSV.
     * @param pDataFile le fichier de données au format CSV
     * @return la liste des en-têtes
     */
    public static List<String> getHeaders(File pDataFile)
    {
        // déclaration de la liste des en-têtes
        List<String> headerList = new ArrayList<>() ;
        try
        {
            // ouverture du fichier CSV
            FileReader fileReader   = new FileReader(pDataFile) ;
            CSVParser csvParser     = new CSVParserBuilder().withSeparator(';').build() ;
            CSVReader csvReader     = new CSVReaderBuilder(fileReader).withCSVParser(csvParser).build() ;

            // remplissage de la liste des en-têtes
            headerList.addAll(Arrays.asList(csvReader.readNext())) ;
        }    
        catch (Exception e)
        {
            System.out.println(e) ;
        }
        return headerList ;
    }

    public static List<String> getHistory(String pRoomName, String pDataType)
    {
        // déclaration de la liste de l'historique des valeurs
        List<String> valueHistory = new ArrayList<>() ;
        try
        {
            // ouverture du fichier CSV
            FileReader fileReader   = new FileReader(DataLoader.getRoomDataFile(pRoomName)) ;
            CSVParser csvParser     = new CSVParserBuilder().withSeparator(';').build() ;
            CSVReader csvReader     = new CSVReaderBuilder(fileReader).withCSVParser(csvParser).build() ;

            // récupération de l'indice de la colonne du type de données à lire
            List<String> headerList = Arrays.asList(csvReader.readNext()) ;
            int columnIndex = headerList.indexOf(pDataType) ;

            // construction de l'historique des valeurs
            String[] values ;
            while ((values = csvReader.readNext()) != null && !values[0].equals(""))
            {
                valueHistory.add(values[columnIndex]) ;
            }
        }
        catch (Exception e)
        {
            System.out.println(e) ;
        }
        return valueHistory ;
    }
}