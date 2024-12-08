package application.view ;

import application.control.ConfigurationFileForm ;
import application.data.RoomEnum ;
import application.styles.FontLoader ;

import javafx.fxml.FXML ;
import javafx.scene.control.Button ;
import javafx.scene.layout.GridPane ;
import javafx.scene.layout.VBox ;
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

    // éléments graphiques de la vue FXML (ordonnés par ordre d'apparition)
    @FXML private VBox roomListVBox ;
    @FXML private GridPane roomListGridPane ;

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

        for (RoomEnum room : this.cffDialogController.getRoomList())
        {
            System.out.println(room.getNameForDisplay()) ;
            System.out.println(room.getNameForTopic()) ;

            Button button = new Button("TEST") ;
            button.setMinWidth(100) ;
            this.roomListGridPane.getChildren().add(button) ;
        }

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