package application.control ;

import application.data.DataLoader ;
import application.data.enums.Room ;
import application.data.enums.RoomDataType ;
import application.data.enums.SolarPanelDataType;
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
    // --------------------------

    // fréquence de lecture des données (en secondes)
    private static final int MIN_READING_FREQUENCY      = 5 ;       // fréquence minimale
    private static final int MAX_READING_FREQUENCY      = 1000 ;    // fréquence maximale
    private static final int DEFAULT_READING_FREQUENCY  = 10 ;      // fréquence par défaut

    // déclaration des attributs
    // -------------------------

    // attributs relatifs au contrôleur de dialogue
    private Stage cffStage ;
    private ConfigurationFileFormViewController cffViewController ;

    // attributs relatifs aux salles (capteurs AM107)
    private List<Room> roomList ;
    private List<RoomDataType> roomDataTypeList ;

    // attributs relatifs aux panneaux solaires (capteurs SOLAREDGE)
    private List<SolarPanelDataType> solarPanelDataTypeList ;

    /**
     * Constructeur : charge le formulaire.
     */
    public ConfigurationFileForm(Stage _stageParent)
    {
        try
        {
            // initialisation des attributs
            this.roomList               = DataLoader.getRoomList() ;
            this.roomDataTypeList       = DataLoader.getRoomDataTypeList() ;
            this.solarPanelDataTypeList = DataLoader.getSolarPanelDataTypeList() ;

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
     * Accesseur : donne la fréquence de lecture des données minimale (en secondes).
     * @return  la fréquence de lecture minimale
     */
    public int getMinReadingFrequency() { return MIN_READING_FREQUENCY ; }

    /**
     * Accesseur : donne la fréquence de lecture des données maximale (en secondes).
     * @return  la fréquence de lecture maximale
     */
    public int getMaxReadingFrequency() { return MAX_READING_FREQUENCY ; }

    /**
     * Accesseur : donne la fréquence de lecture des données par défaut (en secondes).
     * @return  la fréquence de lecture par défaut
     */
    public int getDefaultReadingFrequency() { return DEFAULT_READING_FREQUENCY ; }

    /**
     * Accesseur : donne la liste des salles existantes.
     * @return  la liste des salles existantes
     */
    public List<Room> getRoomList() { return this.roomList ; }

    /**
     * Accesseur : donne la liste des types de données des salles.
     * @return  la liste des types de données des salles
     */
    public List<RoomDataType> getRoomDataTypeList() { return this.roomDataTypeList ; }

    /**
     * Accesseur : donne la liste des types de données des panneaux solaires.
     * @return  la liste des types de données des panneaux solaires
     */
    public List<SolarPanelDataType> getSolarPanelDataTypeList() { return this.solarPanelDataTypeList ; }

    /**
     * Effectue le dialogue de paramétrage d'un fichier de configuration.
     */
    public void doConfigurationFileFormDialog() { this.cffViewController.displayDialog() ; }

    /**
     * Indique si la fréquence de lecture donnée est valide.
     * @param pFrequency    la fréquence de lecture à traiter
     * @return  true si la fréquence est valide, false sinon
     */
    public boolean isReadingFrequencyValid(int pFrequency)
    {
        if (    pFrequency < this.getMinReadingFrequency()
            ||  pFrequency > this.getMaxReadingFrequency()
        ) {
            return false ;
        }
        return true ;
    }
}