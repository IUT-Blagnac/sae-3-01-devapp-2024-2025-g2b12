package application.thread ;

import application.control.DataVisualisationPane ;
import application.data.DataLoader ;

import com.opencsv.CSVReader ;
import com.opencsv.CSVReaderBuilder ;
import com.opencsv.exceptions.CsvValidationException ;
import com.opencsv.CSVParserBuilder ;

import java.io.FileNotFoundException ;
import java.io.FileReader ;
import java.io.IOException ;
import java.util.HashMap ;
import java.util.Map ;

/**
 * Thread lecteur de données.
 * 
 * Date de dernière modification :
 * - Mercredi 4 décembre 2024 -
 * 
 * @author Mathys Laguilliez
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class CsvReaderTask implements Runnable
{
    // déclaration des attributs
    private final DataVisualisationPane dvpDialogController ;
    private final char delimiter ;

    /**
     * Constructeur par défaut : 2 paramètres.
     * @param controller    le contrôleur de dialogue à qui envoyer les donnéees lues
     * @param delimiter     le symbole de séparation des valeurs utilisé dans les fichiers CSV lus
     */
    public CsvReaderTask(DataVisualisationPane controller, char delimiter)
    {
        // initialisation des attributs
        this.dvpDialogController = controller ;
        this.delimiter = delimiter ;
    }

    @Override
    public void run()
    {
        this.readDataFile() ;
        this.readAlertFile() ;
    }

    /**
     * Lis le fichier de données.
     */
    private void readDataFile()
    {
        Map<String, Map<String, String>> dataMap = new HashMap<>() ;
        try (CSVReader csvReader = new CSVReaderBuilder(new FileReader(DataLoader.getAllRoomDataFile()))
                .withCSVParser(new CSVParserBuilder().withSeparator(delimiter).build())
                .build()
        ) {
            String[] header = csvReader.readNext() ;
            String[] values = null ;
            while ((values = csvReader.readNext()) != null && !values[0].equals(""))
            {
                dataMap.put(values[0], new HashMap<String, String>()) ;
                for (int i = 1; i < values.length; i++)
                {
                    dataMap.get(values[0]).put(header[i], values[i]) ;
                }
            }
            System.out.println(dataMap) ;
            this.dvpDialogController.setDataMap(dataMap) ;
        }
        catch (FileNotFoundException e) { throw new RuntimeException(e) ; }
        catch (IOException e) { throw new RuntimeException(e) ; }
        catch (CsvValidationException e) { throw new RuntimeException(e) ; }
    }

    /**
     * Lis le fichier d'alertes.
     */
    private void readAlertFile()
    {
        Map<String, Map<String, String>> alertMap = new HashMap<>() ;
        try (CSVReader csvReader = new CSVReaderBuilder(new FileReader(DataLoader.getAlertFile()))
                .withCSVParser(new CSVParserBuilder().withSeparator(delimiter).build())
                .build()
        ) {
            String[] header = csvReader.readNext() ;
            String[] values = null ;
            while ((values = csvReader.readNext()) != null && !values[0].equals(""))
            {
                alertMap.put(values[0], new HashMap<String, String>()) ;
                for (int i = 1; i < values.length; i++)
                {
                    alertMap.get(values[0]).put(header[i], values[i]) ;
                }
            }
            System.out.println(alertMap) ;
            this.dvpDialogController.setAlertMap(alertMap) ;
        }
        catch (FileNotFoundException e) { throw new RuntimeException(e) ; }
        catch (IOException e) { throw new RuntimeException(e) ; }
        catch (CsvValidationException e) { throw new RuntimeException(e) ; }
    }
}