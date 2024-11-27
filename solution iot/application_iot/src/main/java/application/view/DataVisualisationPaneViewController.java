package application.view ;

import application.controller.DataVisualisationPane ;

import javafx.fxml.FXML ;
import javafx.scene.layout.VBox ;
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

    // récupération des éléments de la vue FXML
    @FXML private VBox vboxBlocsDeDonnees ;

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

    /**
     * Met à jour / Rafraîchit l'affichage des données.
     */
    public void update()
    {
        System.out.println(this.dvpDialogController.getMapData()) ;
        this.vboxBlocsDeDonnees.getChildren().clear() ;
    }
}