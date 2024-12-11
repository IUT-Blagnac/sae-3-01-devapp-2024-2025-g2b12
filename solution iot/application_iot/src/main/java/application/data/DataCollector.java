package application.data ;

/**
 * Classe chargée du processus de collecte des données.
 * 
 * Date de dernière modification :
 * - Mardi 10 décembre 2024 -
 * 
 * @author Nolhan Biblocque
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class DataCollector
{
    // déclaration des constantes
    private static final String PYTHON_PROGRAM_PATH = "resources\\mqtt.py" ;

    // déclaration des attributs
    private static Process singleCollectionProcess ;    // unique instance du processus de collecte de données

    /**
     * Démarre le processus de collecte des données.
     * @return true si le processus a été lancé, false sinon
     */
    public static synchronized boolean startCollectionProcess()
    {
        if (singleCollectionProcess == null)
        {
            try
            {
                // récupération du système d'exploitation de la machine
                String os = System.getProperty("os.name").toLowerCase() ;
    
                // sélection de la commande adaptée au système d'exploitation
                String cmd = os.indexOf("mac") >= 0 ? "python3" : "python" ;
    
                // construction et démarrage du processus
                ProcessBuilder processBuilder = new ProcessBuilder(cmd, PYTHON_PROGRAM_PATH) ;
                singleCollectionProcess = processBuilder.start() ;
                System.out.println("Python process started successfully.") ; // message console
            }
            catch (Exception e)
            {
                System.out.println("Failed to start the python process.") ; // message console
                e.printStackTrace() ;
            }
            return true ;
        }
        return false ;
    }

    /**
     * Arrête le processus de collecte des données.
     */
    public static synchronized void stopCollectionProcess()
    {
        if (singleCollectionProcess != null)
        {
            singleCollectionProcess.destroy() ;
            singleCollectionProcess = null ;
        }
    }
}