package application.view ;

import application.controller.DataVisualisationPane ;
import application.thread.CsvReaderTask ;

import java.util.List ;
import java.util.concurrent.Executors ;
import java.util.concurrent.ScheduledExecutorService ;
import java.util.concurrent.TimeUnit ;

import javafx.fxml.FXML ;
import javafx.scene.control.Label ;
import javafx.scene.control.ScrollPane ;
import javafx.scene.layout.FlowPane ;
import javafx.stage.Stage ;

/**
 * Contrôleur de vue de la fenêtre de visualisation des données.
 * 
 * Date de dernière modification :
 * - Mercredi 19 novembre 2024 -
 * 
 * @author Nolhan Biblocque
 * @author Victor Jockin
 * @author Mathys Laguilliez
 * - Équipe 2B12 -
 */
public class DataVisualisationPaneViewController
{
    // déclaration des attributs
    private Stage stage ;
    private DataVisualisationPane dvpDialogController ;

    public void setStage(Stage _stage)
    {
        this.stage = _stage ;
    }

    public void setDvpDialogController(DataVisualisationPane _dvpDialogController)
    {
        this.dvpDialogController = _dvpDialogController ;
    }

    /**
     * Affiche la fenêtre.
     */
    public void displayDialog()
    {
        System.out.println("avant lancement thread") ;
        this.startCsvReaderThread() ;
        System.out.println("après lancement thread") ;
        this.stage.show() ;
    }

    @FXML
    private Label infoCsv;
    @FXML
    private ScrollPane scroll;
    @FXML
    private FlowPane flow;

    private ScheduledExecutorService scheduler;

    public void ajoutLabel(List<String> contents) {
        flow.getChildren().clear();
        for (String content : contents) {
            Label label = new Label(content);
            flow.getChildren().add(label);
        }
        scroll.setContent(flow);
    }

    public void startCsvReaderThread() {
        scheduler = Executors.newScheduledThreadPool(1);
        scheduler.scheduleAtFixedRate(new CsvReaderTask(this, ';'), 0, 5, TimeUnit.SECONDS);
    }

    public void updateInfoCsv(String content) {
        infoCsv.setText(content);
    }

    public void stopCsvReaderThread() {
        if (scheduler != null && !scheduler.isShutdown()) {
            scheduler.shutdown();
        }
    }
}