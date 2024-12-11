package application.view ;

import application.ApplicationMainFrame ;
import application.model.Configuration ;
import application.styles.FontLoader ;
import javafx.fxml.FXML ;
import javafx.scene.control.Button ;
import javafx.scene.control.Label ;
import javafx.scene.text.Font ;
import javafx.stage.Stage ;
import javafx.stage.WindowEvent ;

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
    // déclaration des constantes
    private static final String NO_CONFIGURATION_LABEL_TEXT         = "Aucune configuration définie." ;
    private static final String NO_CONFIGURATION_BUTTON_TEXT        = "Définir une configuration" ;

    // déclaration des attributs
    private Stage stage ;
    private ApplicationMainFrame amfDialogController ;

    // éléments graphiques de la vue FXML (ordonnés par ordre d'apparition)
    @FXML private Label configLabel ;
    @FXML private Button configButton ;
    @FXML private Button openDashboardButton ;

    /**
     * Définit le stage de la vue.
     * @param _stage    un stage
     */
    public void setStage(Stage _stage)
    {
        this.stage = _stage ;
    }

    /**
     * Définit le contrôleur de dialogue de la vue.
     * @param _cffDialogController  un contrôleur de dialogue
     */
    public void setAmfDialogController(ApplicationMainFrame _amfDialogController)
    {
        this.amfDialogController = _amfDialogController ;
    }

    /**
     * Initialise la vue.
     */
    public void initializeView()
    {
        // préchargement des fonts (pour utilisation dans la feuille de style cff.css)
        Font lightFont      = FontLoader.getLightFont() ;
        Font semiBoldFont   = FontLoader.getSemiBoldFont() ;
        Font boldFont       = FontLoader.getBoldFont() ;

        // initialisation de définition d'une configuration
        this.configLabel.setText(NO_CONFIGURATION_LABEL_TEXT) ;
        this.configButton.setText(NO_CONFIGURATION_BUTTON_TEXT) ;

        this.openDashboardButton.setDisable(false) ; // true

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

    /**
     * Met à jour l'état des boutons du menu.
     */
    public void updateButtonStatus()
    {
        if (Configuration.isDefined())
        {
            this.configLabel.setText("Configuration "+Configuration.getInstance().getName()+" chargée.") ;
            this.configButton.setText("Modifier la configuration") ;
            this.openDashboardButton.setDisable(false) ;
        }
        else
        {
            this.configLabel.setText(NO_CONFIGURATION_LABEL_TEXT) ;
            this.configButton.setText(NO_CONFIGURATION_BUTTON_TEXT) ;
            this.openDashboardButton.setDisable(true) ;
        }
    }
}