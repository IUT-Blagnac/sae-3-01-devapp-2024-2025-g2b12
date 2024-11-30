package application.control ;

import application.data.DataLoader ;
import application.thread.CsvReaderTask ;
import application.tools.FileReading ;
import application.view.DataVisualisationPaneViewController ;

import java.util.concurrent.Executors ;
import java.util.concurrent.ScheduledExecutorService ;
import java.util.concurrent.TimeUnit ;
import java.util.List ;
import java.util.Map ;

import javafx.application.Platform ;
import javafx.fxml.FXMLLoader ;
import javafx.scene.Scene ;
import javafx.stage.Stage ;

/**
 * Contrôleur de dialogue de la fenêtre de visualisation des données.
 * 
 * Date de dernière modification :
 * - Mardi 18 novembre 2024 -
 * 
 * @author Nolhan Biblocque
 * @author Victor Jockin
 * @author Mathys Laguilliez
 * - Équipe 2B12 -
 */
public class DataVisualisationPane
{
    // déclaration des constantes
    private static final double WINDOW_WIDTH    = 1000 ;    // largeur de la fenêtre
    private static final double WINDOW_HEIGHT   = 600 ;     // hauteur de la fenêtre

    // déclaration des attributs
    private Stage dvpStage ;
    private DataVisualisationPaneViewController dvpViewController ;
    private ScheduledExecutorService scheduler ;
    private List<String> dataTypeList ;
    private Map<String, Map<String, String>> dataMap ;

    /**
     * Constructeur : charge le formulaire.
     */
    public DataVisualisationPane(Stage _stageParent)
    {
        try
        {
            // récupération des types de données à visualiser
            this.dataTypeList = FileReading.getHeadersFromCSVFile(DataLoader.getDataFile()) ;

            // initialisation d'un nouveau stage pour le formulaire
            this.dvpStage = new Stage() ;

            // centrage de la fenêtre par rapport à la fenêtre précédente
            this.dvpStage.setX(_stageParent.getX() + (_stageParent.getWidth() - WINDOW_WIDTH) / 2) ;
            this.dvpStage.setY(_stageParent.getY() + (_stageParent.getHeight() - WINDOW_HEIGHT) / 2) ;

            // chargement de la vue FXML du formulaire
            FXMLLoader fxmlLoader = new FXMLLoader(DataVisualisationPaneViewController.class.getResource("dataVisualisationPane.fxml")) ;

            // initialisation de la scène
            Scene scene = new Scene(fxmlLoader.load(), WINDOW_WIDTH, WINDOW_HEIGHT) ;
            this.dvpStage.setScene(scene) ;
            this.dvpStage.setTitle("Tableau de bord") ;

            // initialisation du contrôleur
            this.dvpViewController = fxmlLoader.getController() ;
            this.dvpViewController.setStage(this.dvpStage) ;
            this.dvpViewController.setDvpDialogController(this) ;
            this.dvpViewController.initializeViewElements() ;

            // application des styles à la scène
            this.dvpStage.getScene().getStylesheets().add(getClass().getResource("/application/style/dvp.css").toExternalForm()) ;
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
     * Accesseur : donne la liste des types de données visualisées.
     * @return la liste des types de données
     */
    public List<String> getDataTypeList()
    {
        return this.dataTypeList ;
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
     * Met à jour les données visualisées.
     * @param _dataMap les données mises à jour
     */
    public void setDataMap(Map<String, Map<String, String>> _dataMap)
    {
        this.dataMap = _dataMap ;
        Platform.runLater(() -> { this.dvpViewController.update() ; }) ;
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
        if (scheduler != null && !scheduler.isShutdown())
        {
            scheduler.shutdown() ;
        }
    }
}