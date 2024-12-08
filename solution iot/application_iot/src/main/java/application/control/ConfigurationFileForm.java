package application.control ;

import application.data.DataLoader ;
import application.data.Room ;
import application.data.RoomDataType ;
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
    // déclaration des constantes
    public static final double DEFAULT_READING_FREQUENCY = 10 ; // fréquence de lecture des données par défaut (en secondes)

    // déclaration des attributs
    private Stage cffStage ;
    private ConfigurationFileFormViewController cffViewController ;

    private List<Room> roomList ;
    private List<RoomDataType> roomDataTypeList ;

    /**
     * Constructeur : charge le formulaire.
     */
    public ConfigurationFileForm(Stage _stageParent)
    {
        try
        {
            // initialisation des attributs
            this.roomList           = DataLoader.getRoomList() ;
            this.roomDataTypeList   = DataLoader.getRoomDataTypeList() ;

            // initialisation d'un nouveau stage pour le formulaire
            this.cffStage = new Stage() ;
            this.cffStage.initOwner(_stageParent) ;
            this.cffStage.initModality(Modality.WINDOW_MODAL) ;

            // chargement de la vue FXML du formulaire
            FXMLLoader fxmlLoader = new FXMLLoader(ConfigurationFileFormViewController.class.getResource("configurationFileForm.fxml")) ;

            // initialisation de la scène
            Scene scene = new Scene(fxmlLoader.load(), 700, 600) ;
            this.cffStage.setScene(scene) ;
            this.cffStage.setTitle("Créer une configuration") ;
            this.cffStage.setResizable(false) ;

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
    public List<Room> getRoomList()
    {
        return this.roomList ;
    }

    /**
     * Accesseur : donne la liste des types de données des salles.
     * @return  la liste des types de données des salles
     */
    public List<RoomDataType> getRoomDataTypeList()
    {
        return this.roomDataTypeList ;
    }

    /**
     * Effectue le dialogue de paramétrage d'un fichier de configuration.
     */
    public void doConfigurationFileFormDialog()
    {
        this.cffViewController.displayDialog() ;
    }
}