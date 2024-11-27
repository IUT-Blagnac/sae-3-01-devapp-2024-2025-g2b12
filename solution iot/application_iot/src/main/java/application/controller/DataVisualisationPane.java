package application.controller ;

import application.view.DataVisualisationPaneViewController ;
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
    // déclaration des attributs
    private Stage dvpStage ;
    private DataVisualisationPaneViewController dvpViewController ;

    /**
     * Constructeur : charge le formulaire.
     */
    public DataVisualisationPane(Stage _stageParent)
    {
        try
        {
            // initialisation d'un nouveau stage pour le formulaire
            this.dvpStage = _stageParent ;

            // chargement de la vue FXML du formulaire
            FXMLLoader fxmlLoader = new FXMLLoader(DataVisualisationPaneViewController.class.getResource("dataVisualisationPane.fxml")) ;

            // initialisation de la scène
            Scene scene = new Scene(fxmlLoader.load(), 600, 400) ;
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

    public void doDataVisualisationPaneDialog()
    {
        this.dvpViewController.displayDialog() ;
    }
}