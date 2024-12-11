package application.view ;

import application.ApplicationMainFrame ;
import application.model.Configuration;
import javafx.fxml.FXML ;
import javafx.stage.Stage ;
import javafx.stage.WindowEvent;

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
     * Initialise la vue.
     */
    public void initializeView()
    {
        this.stage.setOnCloseRequest(e -> this.closeWindow(e)) ;
    }

    /**
     * Affiche la fenêtre.
     */
    public void displayDialog()
    {
        this.stage.show() ;
    }

    /**
     * Gère la fermeture de la fenêtre.
     * @param e un évènement de fenêtre
     */
    private void closeWindow(WindowEvent e)
    {
        this.doClose() ;
		e.consume() ;
	}

    @FXML
    protected void doConfiguration()
    {
        this.amfDialogController.parametrerConfiguration() ;
    }

    @FXML
    protected void doVisualiserDonnees()
    {
        if (Configuration.isDefined()) { this.amfDialogController.visualiserDonnees() ; }
    }

    @FXML
    protected void doTesterConnexionMQTT()
    {
        System.out.println("- En cours de développement -") ;
    }

    @FXML
    protected void doClose()
    {
        this.stage.close() ;
    }
}