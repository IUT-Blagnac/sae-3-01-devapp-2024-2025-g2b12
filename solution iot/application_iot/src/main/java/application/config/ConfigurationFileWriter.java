package application.config ;

import application.model.Configuration ;

import java.io.File ;
import java.io.FileWriter ;
import java.io.IOException ;

/**
 * Classe écrivant un fichier de configuration.
 * 
 * Date de dernière modification :
 * - Mardi 10 décembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe G2B12 -
 */
public class ConfigurationFileWriter
{
    // déclaration des constantes
    private static final String CONFIGURATION_FILE_PATH = "resources/configuration.ini" ;

    public static void writeConfigurationFile(Configuration pConfiguration)
    {
        File configFile = new File(CONFIGURATION_FILE_PATH) ;

        try (FileWriter fileWriter = new FileWriter(configFile))
        {
            fileWriter.write("OK") ;
        }
        catch (IOException ioe)
        {
            System.out.println(ioe) ;
        }
    }
}