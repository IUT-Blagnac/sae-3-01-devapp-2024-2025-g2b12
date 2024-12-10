package application.config ;

import application.model.Configuration ;

import java.io.File ;
import java.io.FileWriter ;
import java.io.IOException ;
import java.util.Map.Entry ;

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
        // récupération du fichier de configuration (si existant)
        File configFile = new File(CONFIGURATION_FILE_PATH) ;

        try (FileWriter w = new FileWriter(configFile))
        {
            // écriture du fichier de configuration
            // ------------------------------------

            // section MQTT : paramètres de connexion au broker
            w.write("[MQTT]\n") ;
            w.write("broker=mqtt.iut-blagnac.fr\n") ;
            w.write("port=1883\n") ;
            w.write("topic="+pConfiguration.getTopicPrefix()+"\n") ;
            w.write("\n") ;

            // section SUBJECTS : liste des sujets à observer
            w.write("[SUBJECTS]\n") ;
            for (int i = 0 ; i < pConfiguration.getSubjectList().size() ; i++)
            {
                w.write("subject"+(i+1)+"="+pConfiguration.getSubjectList().get(i)+"\n") ;
            }
            w.write("\n") ;

            // section DATA_TYPE : liste des types de données à récupérer
            w.write("[DATA_TYPE]\n") ;
            for (int i = 0 ; i < pConfiguration.getDataTypeList().size() ; i++)
            {
                w.write("dataType"+(i+1)+"="+pConfiguration.getDataTypeList().get(i)+"\n") ;
            }
            w.write("\n") ;

            // section THRESHOLD : liste des seuils d'alertes par type de données
            w.write("[THRESHOLD]\n") ;
            for (Entry<String, Double> m : pConfiguration.getThresholdMap().entrySet())
            {
                w.write(m.getKey()+"="+m.getValue()+"\n") ;
            }
            w.write("\n") ;

            // section PARAMS : autres paramètres
            w.write("[PARAMS]\n") ;
            w.write("frequency="+pConfiguration.getReadingFrequency()) ;
        }
        catch (IOException ioe)
        {
            System.out.println(ioe) ;
        }
    }
}