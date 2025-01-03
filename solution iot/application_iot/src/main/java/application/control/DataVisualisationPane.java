package application.control ;

import application.data.DataCollector ;
import application.model.Configuration ;
import application.thread.CsvReaderTask ;
import application.view.DataVisualisationPaneViewController ;

import java.util.Map ;
import java.util.concurrent.Executors ;
import java.util.concurrent.ScheduledExecutorService ;
import java.util.concurrent.TimeUnit ;

import javafx.application.Platform ;
import javafx.fxml.FXMLLoader ;
import javafx.scene.Scene ;
import javafx.stage.Stage ;

/**
 * Contrôleur de dialogue de la fenêtre de visualisation des données.
 * 
 * Date de dernière modification :
 * - Mardi 10 décembre 2024 -
 * 
 * @author Nolhan Biblocque
 * @author Victor Jockin
 * @author Mathys Laguilliez
 * - Équipe 2B12 -
 */
public class DataVisualisationPane
{
    // déclaration des constantes
    private static final double MIN_WIDTH   = 1000 ;    // largeur minimale de la fenêtre
    private static final double MIN_HEIGHT  = 600 ;     // hauteur minimale de la fenêtre
    private static final double PREF_WIDTH  = 1000 ;    // largeur préférée de la fenêtre
    private static final double PREF_HEIGHT = 600 ;     // hauteur préférée de la fenêtre

    // déclaration des attributs
    // -------------------------

    // attributs relatifs au contrôleur de dialogue
    private Stage dvpStage ;
    private DataVisualisationPaneViewController dvpViewController ;

    // attributs relatifs à la configuration
    private Configuration configuration = Configuration.getInstance() ;

    // attributs relatifs aux données
    private Map<String, Map<String, String>> dataMap ;
    private Map<String, Map<String, String>> alertMap ;

    // attributs utilitaires
    private ScheduledExecutorService scheduler ;

    /**
     * Constructeur : charge le formulaire.
     */
    public DataVisualisationPane(Stage _stageParent)
    {
        try
        {
            // initialisation du stage
            this.dvpStage = _stageParent ;

            // centrage de la fenêtre par rapport à la fenêtre précédente
            this.dvpStage.setX(_stageParent.getX() + (_stageParent.getWidth() - PREF_WIDTH) / 2) ;
            this.dvpStage.setY(_stageParent.getY() + (_stageParent.getHeight() - PREF_HEIGHT) / 2) ;

            // chargement de la vue FXML du formulaire
            FXMLLoader fxmlLoader = new FXMLLoader(DataVisualisationPaneViewController.class.getResource("dataVisualisationPane.fxml")) ;

            // initialisation de la scène
            Scene scene = new Scene(fxmlLoader.load(), PREF_WIDTH, PREF_HEIGHT) ;
            this.dvpStage.setScene(scene) ;
            this.dvpStage.setTitle("Tableau de bord") ;
            this.dvpStage.setMinWidth(MIN_WIDTH) ;
            this.dvpStage.setMinHeight(MIN_HEIGHT) ;
            this.dvpStage.setResizable(true) ;

            // initialisation du contrôleur
            this.dvpViewController = fxmlLoader.getController() ;
            this.dvpViewController.setStage(this.dvpStage) ;
            this.dvpViewController.setDvpDialogController(this) ;
            this.dvpViewController.initializeView() ;

            // application des styles à la scène
            this.dvpStage.getScene().getStylesheets().add(getClass().getResource("/application/style/dvp.css").toExternalForm()) ;

            // démarrage du processus de collecte des données
            DataCollector.startCollectionProcess() ;
        }
        catch (Exception e)
        {
            e.printStackTrace() ;
        }
    }

    /**
     * Effectue le dialogue de visualisation des données.
     */
    public void doDataVisualisationPaneDialog()
    {
        this.dvpViewController.displayDialog() ;
        this.startCsvReaderThread() ;
    }

    /**
     * Accesseur : donne la configuration actuelle.
     * @return la configuration actuelle
     */
    public Configuration getConfiguration() { return this.configuration ; }

    /**
     * Met à jour les données visualisées.
     * @param _dataMap les données mises à jour
     */
    public void setDataMap(Map<String, Map<String, String>> _dataMap)
    {
        this.dataMap = _dataMap ;
        Platform.runLater(() -> { this.dvpViewController.updateDataDisplay() ; }) ;
    }

    /**
     * Accesseur : donne le dictionnaire des données visualisées.
     * @return le dictionnaire des données
     */
    public Map<String, Map<String, String>> getDataMap()
    {
        return this.dataMap ;
    }

    /**
     * Accesseur : donne le nombre de capteurs observés.
     * @return le nombre de capteurs observés
     */
    public int getObservedSensorsNumber()
    {
        return this.configuration.getSubjectList().size() ;
    }

    /**
     * Met à jour les alertes relatives aux données visualisées.
     * @param _alertMap les alertes mises à jour
     */
    public void setAlertMap(Map<String, Map<String, String>> _alertMap)
    {
        this.alertMap = _alertMap ;
        Platform.runLater(() -> { this.dvpViewController.updateAlertDisplay() ; }) ;
    }

    /**
     * Accesseur : donne le dictionnaire des alertes relatives aux données visualisées.
     * @return le dictionnaire des alertes
     */
    public Map<String, Map<String, String>> getAlertMap()
    {
        return this.alertMap ;
    }

    /**
     * Lance le Thread lecteur de données.
     */
    public void startCsvReaderThread()
    {
        scheduler = Executors.newScheduledThreadPool(1) ;
        scheduler.scheduleAtFixedRate(
            new CsvReaderTask(this, ';'),
            0,
            5,
            TimeUnit.SECONDS
        ) ;
    }

    /**
     * Arrête le Thread lecteur de données.
     */
    public void stopCsvReaderThread()
    {
        if (scheduler != null && !scheduler.isShutdown()) { scheduler.shutdownNow() ; }
    }

    /**
     * Ouvre le formulaire d'édition de la configuration.
     */
    public void parametrerConfiguration()
    {
        ConfigurationFileForm configurationFileForm = new ConfigurationFileForm(this.dvpStage) ;
        configurationFileForm.doConfigurationFileFormDialog() ;
    }
}