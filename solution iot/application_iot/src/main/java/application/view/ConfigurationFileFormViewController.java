package application.view ;

import application.control.ConfigurationFileForm ;
import application.styles.FontLoader ;
import javafx.fxml.FXML ;
import javafx.geometry.Pos;
import javafx.scene.text.Font ;
import javafx.stage.Stage ;
import javafx.stage.WindowEvent ;

/**
 * Contrôleur de vue du formulaire de paramétrage
 * d'un fichier de configuration.
 * 
 * Date de dernière modification :
 * - Mardi 18 novembre 2024 -
 * 
 * @author Victor Jockin (Équipe G2B12)
 */
public class ConfigurationFileFormViewController
{
    // déclaration des attributs
    private Stage stage ;
    private ConfigurationFileForm cffDialogController ;

    public void setStage(Stage _stage)
    {
        this.stage = _stage ;
    }

    public void setCffDialogController(ConfigurationFileForm _cffDialogController)
    {
        this.cffDialogController = _cffDialogController ;
    }

    /**
     * Initialise la vue.
     */
    public void initializeView()
    {
        // préchargement des fonts (pour utilisation dans la feuille de style cff.css)
        Font boldFont       = FontLoader.getBoldFont() ;
        Font semiBoldFont   = FontLoader.getSemiBoldFont() ;

        this.stage.setOnCloseRequest(e -> this.closeWindow(e)) ;
    }

    /**
     * Affiche la fenêtre.
     */
    public void displayDialog()
    {
        this.stage.showAndWait() ;
    }

    /**
     * Gère la fermeture de la fenêtre.
     * @param e un évènement de fenêtre
     * @return null
     */
    private Object closeWindow(WindowEvent e)
    {
        this.doClose() ;
		e.consume() ;
		return null ;
	}

    /**
     * Ferme la fenêtre.
     */
    @FXML
    private void doClose()
    {
        this.stage.close() ;
    }
}