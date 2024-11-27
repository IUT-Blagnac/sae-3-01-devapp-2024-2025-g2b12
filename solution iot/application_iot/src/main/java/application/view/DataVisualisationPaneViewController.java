package application.view ;

import application.controller.DataVisualisationPane ;

import java.util.List ;

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

    @FXML
    private Label infoCsv;
    @FXML
    private ScrollPane scroll;
    @FXML
    private FlowPane flow;

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
        this.stage.show() ;
    }

    @FXML
    public void ajoutLabel(List<String> contents) {
        flow.getChildren().clear();
        for (String content : contents) {
            Label label = new Label(content);
            flow.getChildren().add(label);
        }
        scroll.setContent(flow);
    }

    public void updateInfoCsv(String content) {
        infoCsv.setText(content);
    }

}