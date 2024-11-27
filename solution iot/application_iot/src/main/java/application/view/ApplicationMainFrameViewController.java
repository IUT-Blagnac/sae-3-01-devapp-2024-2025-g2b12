package application.view ;

import application.ApplicationMainFrame ;
import javafx.fxml.FXML ;
import javafx.stage.Stage ;

/**
 * Contrôleur de dialogue du menu principal.
 * 
 * Date de dernière modification :
 * - Mardi 18 novembre 2024 -
 * 
 * @author Léo Guinvarc'h
 * @author Victor Jockin
 * @author Mucahit Lekesiz
 * - Équipe 2B12 -
 */
public class ApplicationMainFrameViewController
{
    // déclaration des attributs
    private Stage stage ;
    private ApplicationMainFrame amfDialogController ;

    public void setStage(Stage _stage)
    {
        this.stage = _stage ;
    }

    public void setAmfDialogController(ApplicationMainFrame _amfDialogController)
    {
        this.amfDialogController = _amfDialogController ;
    }

    /**
     * Affiche la fenêtre.
     */
    public void displayDialog()
    {
        this.stage.show() ;
    }

    @FXML
    protected void doConfiguration()
    {
        this.amfDialogController.parametrerConfiguration() ;
    }

    @FXML
    protected void doVisualiserDonnees()
    {
        this.amfDialogController.visualiserDonnees() ;
    }

    @FXML
    protected void doTesterConnexionMQTT()
    {
        System.out.println("- En cours de développement -") ;
    }

    @FXML
    protected void doQuitter()
    {
        System.out.println("- En cours de développement -") ;
    }
}