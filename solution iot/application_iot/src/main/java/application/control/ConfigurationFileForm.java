package application.control ;

import application.data.DataLoader ;
import application.data.RoomEnum ;
import application.view.ConfigurationFileFormViewController ;

import java.util.List ;

import javafx.fxml.FXMLLoader ;
import javafx.scene.Scene ;
import javafx.stage.Modality ;
import javafx.stage.Stage ;

/**
 * Contrôleur de dialogue du formulaire de paramétrage
 * d'un fichier de configuration.
 * 
 * Date de dernière modification :
 * - Mardi 18 novembre 2024 -
 * 
 * @author Victor Jockin (Équipe G2B12)
 */
public class ConfigurationFileForm
{
    // déclaration des attributs
    private Stage cffStage ;
    private ConfigurationFileFormViewController cffViewController ;
    private List<RoomEnum> roomList ;

    /**
     * Constructeur : charge le formulaire.
     */
    public ConfigurationFileForm(Stage _stageParent)
    {
        try
        {
            // initialisation des attributs
            this.roomList = DataLoader.getRoomList() ;

            // initialisation d'un nouveau stage pour le formulaire
            this.cffStage = new Stage() ;
            this.cffStage.initOwner(_stageParent) ;
            this.cffStage.initModality(Modality.WINDOW_MODAL) ;

            // chargement de la vue FXML du formulaire
            FXMLLoader fxmlLoader = new FXMLLoader(ConfigurationFileFormViewController.class.getResource("configurationFileForm.fxml")) ;

            // initialisation de la scène
            Scene scene = new Scene(fxmlLoader.load(), 600, 400) ;
            this.cffStage.setScene(scene) ;
            this.cffStage.setTitle("Créer une configuration") ;

            // initialisation du contrôleur
            this.cffViewController = fxmlLoader.getController() ;
            this.cffViewController.setStage(this.cffStage) ;
            this.cffViewController.setCffDialogController(this) ;
            this.cffViewController.initializeView() ;

            // application des styles à la scène
            this.cffStage.getScene().getStylesheets().add(getClass().getResource("/application/style/cff.css").toExternalForm()) ;
        }
        catch (Exception e)
        {
            e.printStackTrace() ;
        }
    }

    /**
     * Accesseur : donne la liste des salles existantes.
     * @return  la liste des salles existantes
     */
    public List<RoomEnum> getRoomList()
    {
        return this.roomList ;
    }

    /**
     * Effectue le dialogue de paramétrage d'un fichier de configuration.
     */
    public void doConfigurationFileFormDialog()
    {
        this.cffViewController.displayDialog() ;
    }
}