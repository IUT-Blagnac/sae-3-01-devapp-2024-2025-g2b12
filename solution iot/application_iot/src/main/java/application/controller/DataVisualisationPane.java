package application.controller ;

import application.thread.CsvReaderTask ;
import application.view.DataVisualisationPaneViewController ;

import java.util.concurrent.Executors ;
import java.util.concurrent.ScheduledExecutorService ;
import java.util.concurrent.TimeUnit ;
import java.util.Map ;

import javafx.application.Platform;
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
    private Map<String, Map<String, String>> mapData ;
    private ScheduledExecutorService scheduler ;

    /**
     * Constructeur : charge le formulaire.
     */
    public DataVisualisationPane(Stage _stageParent)
    {
        try
        {
            // initialisation d'un nouveau stage pour le formulaire
            this.dvpStage = _stageParent ;

            // centrage de la fenêtre par rapport à la fenêtre précédente
            this.dvpStage.setX(_stageParent.getX() + (_stageParent.getWidth() - WINDOW_WIDTH) / 2) ;
            this.dvpStage.setY(_stageParent.getY() + (_stageParent.getHeight() - WINDOW_HEIGHT) / 2) ;

            // chargement de la vue FXML du formulaire
            FXMLLoader fxmlLoader = new FXMLLoader(DataVisualisationPaneViewController.class.getResource("dataVisualisationPane.fxml")) ;

            // initialisation de la scène
            Scene scene = new Scene(fxmlLoader.load(), 1000, 600) ;
            this.dvpStage.setScene(scene) ;
            this.dvpStage.setTitle("Visualisation") ;

            // initialisation du contrôleur
            this.dvpViewController = fxmlLoader.getController() ;
            this.dvpViewController.setStage(this.dvpStage) ;
            this.dvpViewController.setDvpDialogController(this) ;
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
     * Met à jour les données visualisées.
     * @param _mapData  Les données mises à jour.
     */
    public void setMapData(Map<String, Map<String, String>> _mapData)
    {
        this.mapData = _mapData ;
        Platform.runLater(() -> { this.dvpViewController.update() ; }) ;
    }

    /**
     * Accesseur : donne le dictionnaire des données visualisées.
     * @return  Le dictionnaire des données.
     */
    public Map<String, Map<String, String>> getMapData()
    {
        return this.mapData ;
    }

    /**
     * Lance le Thread lecteur de données.
     */
    public void startCsvReaderThread() {
        scheduler = Executors.newScheduledThreadPool(1);
        scheduler.scheduleAtFixedRate(new CsvReaderTask(this, ';'), 0, 5, TimeUnit.SECONDS);
    }

    /**
     * Arrête le Thread lecteur de données.
     */
    public void stopCsvReaderThread() {
        if (scheduler != null && !scheduler.isShutdown()) {
            scheduler.shutdown();
        }
    }
}