package application.view ;

import java.io.IOException;

import application.ApplicationMainFrame;
import javafx.fxml.FXML ;
import javafx.scene.control.Label ;
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
    private Label CreerFichier;
    @FXML
    private Label ChargerFichier;
    @FXML
    private Label AfficherFichier;
    @FXML
    private Label TestMQTT;

    @FXML
    protected void onCreerButtonClick()
    {
        CreerFichier.setText("VOUS ETES OU") ;
        // ouverture du formulaire de paramétrage d'un fichier de configuration
        this.amfDialogController.parametrerConfiguration() ;
    }

    @FXML
    protected void onChargerButtonClick() {
        ChargerFichier.setText("TOUS");
    }

    @FXML
    protected void onAfficherButtonClick() throws IOException
    {
        Stage stage = (Stage) AfficherFichier.getScene().getWindow();
        VisualiserDonneesController VDC = new VisualiserDonneesController();
        VDC.doAfficher(stage);
    }

    @FXML
    protected void onTestMQTTButtonClick() {
        TestMQTT.setText("Clique moi, je t\'empoisonne");
    }
}