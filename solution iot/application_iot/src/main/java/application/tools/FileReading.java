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

/**
 * Classe utilitaire fournissant des méthodes de lecture de fichier.
 * 
 * Date de dernière modification :
 * - Jeudi 20 novembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class FileReading
{
    /**
     * Donne la liste des en-têtes d'un fichier CSV.
     * @param pFileName le nom du fichier CSV
     * @return la liste des en-têtes
     */
    public static List<String> getHeadersFromCSVFile(File pCSVFile)
    {
        // déclaration de la liste des en-têtes
        List<String> headersList = new ArrayList<>() ;
        try
        {
            // ouverture du fichier CSV
            FileReader fileReader   = new FileReader(pCSVFile) ;
            CSVParser csvParser     = new CSVParserBuilder().withSeparator(';').build() ;
            CSVReader csvReader     = new CSVReaderBuilder(fileReader).withCSVParser(csvParser).build() ;

            // remplissage de la liste des en-têtes
            headersList.addAll(Arrays.asList(csvReader.readNext())) ;
        }    
        catch (Exception e)
        {
            System.out.println(e) ;
        }
        return headersList ;
    }
}