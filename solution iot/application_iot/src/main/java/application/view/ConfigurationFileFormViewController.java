package application.view ;

import application.control.ConfigurationFileForm ;
import application.data.enums.Room ;
import application.data.enums.RoomDataType ;
import application.data.enums.SensorType ;
import application.styles.FontLoader ;

import java.util.ArrayList ;
import java.util.List ;

import javafx.fxml.FXML ;
import javafx.geometry.Pos ;
import javafx.scene.control.Button ;
import javafx.scene.control.Label ;
import javafx.scene.control.TextField ;
import javafx.scene.layout.ColumnConstraints ;
import javafx.scene.layout.GridPane ;
import javafx.scene.layout.HBox ;
import javafx.scene.layout.Priority ;
import javafx.scene.layout.RowConstraints ;
import javafx.scene.layout.VBox ;
import javafx.scene.paint.Color ;
import javafx.scene.text.Font ;
import javafx.scene.text.TextAlignment ;
import javafx.stage.Stage ;
import javafx.stage.WindowEvent ;

/**
 * Contrôleur de vue du formulaire de paramétrage d'un fichier de configuration.
 * 
 * Date de dernière modification :
 * - Dimanche 8 décembre 2024 -
 * 
 * @author Victor Jockin (Équipe G2B12)
 */
public class ConfigurationFileFormViewController
{
    // déclaration des constantes
    private static final int NUMBER_OF_COLUMNS = 5 ;    // nombre de colonnes dans le menu de sélection des salles

    // déclaration des attributs
    // -------------------------

    // attributs relatifs au contrôleur de vue
    private Stage stage ;
    private ConfigurationFileForm cffDialogController ;

    // attributs relatifs à la configuration à créer
    private SensorType selectedSensorType               = SensorType.AM107 ;
    private List<Room> selectedRoomList                 = new ArrayList<>() ;
    private List<RoomDataType> selectedRoomDataTypeList = new ArrayList<>() ;

    // éléments graphiques de la vue FXML (ordonnés par ordre d'apparition)
    // --------------------------------------------------------------------

    // boutons de sélection des capteurs à consulter
    @FXML private Button roomSensorsButton ;
    @FXML private Button solarPanelSensorsButton ;

    // grille des boutons de sélection des salles
    @FXML private GridPane roomListGridPane ;

    // conteneur de la liste des types de données
    @FXML private VBox roomDataTypeListVBox ;
    @FXML private TextField frequencyTextField ;

    // boutons du menu inférieur ( =<^.^>= )
    @FXML private Button closeButton ;
    @FXML private Button resetButton ;
    @FXML private Button saveButton ;

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

        // initialisation des menus
        this.initSensorTypeSelectionMenu() ;
        this.initRoomSelectionMenu() ;
        this.initRoomDataTypeSelectionMenu() ;
        this.initAdvancedSettingsMenu() ;

        // mise à jour de l'état des boutons du menu inférieur
        this.updateLowerMenuButtonStatus() ;

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

    /**
     * Réinitialisation de la configuration (avec valeurs par défauts).
     */
    @FXML
    private void doReset()
    {
        // réinitialisation de la configuration
        this.selectedSensorType = SensorType.AM107 ;
        this.selectedRoomList.clear() ;
        this.selectedRoomDataTypeList.clear() ;

        // réinitialisation des composants graphiques
        this.initializeView() ;

        // mise à jour de l'état des boutons du menu inférieur
        this.updateLowerMenuButtonStatus() ;
    }

    /**
     * Enregistre la configuration.
     */
    @FXML
    private void doSave()
    {
        System.out.println("-  En cours de développement  -") ;
    }

    /**
     * Initialise le menu de sélection du type de capteurs.
     */
    private void initSensorTypeSelectionMenu()
    {
        // sélection des capteurs AM107 par défaut
        this.solarPanelSensorsButton.getStyleClass().remove("selected") ;
        this.roomSensorsButton.getStyleClass().add("selected") ;

        // écouteurs d'évèneemnts sur le bouton "switch" de sélection du type de capteurs
        this.roomSensorsButton.getStyleClass().add("left") ;
        this.roomSensorsButton.setOnAction(event -> {
            if (this.selectedSensorType != SensorType.AM107)
            {
                this.selectedSensorType = SensorType.AM107 ;
                this.solarPanelSensorsButton.getStyleClass().remove("selected") ;
                this.roomSensorsButton.getStyleClass().add("selected") ;
            }
        }) ;
        this.solarPanelSensorsButton.getStyleClass().add("right") ;
        this.solarPanelSensorsButton.setOnAction(event -> {
            if (this.selectedSensorType != SensorType.SOLAREDGE)
            {
                this.selectedSensorType = SensorType.SOLAREDGE ;
                this.roomSensorsButton.getStyleClass().remove("selected") ;
                this.solarPanelSensorsButton.getStyleClass().add("selected") ;
            }
        }) ;
    }

    /**
     * Initialise le menu de sélection des salles.
     */
    private void initRoomSelectionMenu()
    {
        this.roomListGridPane.getChildren().clear() ;
        this.roomListGridPane.getColumnConstraints().clear() ;
        this.roomListGridPane.getRowConstraints().clear() ;

        // ajout des colonnes au GridPane
        for (int i = 0 ; i < NUMBER_OF_COLUMNS ; i++)
        {
            this.roomListGridPane.getColumnConstraints().add(new ColumnConstraints()) ;
        }

        // ajout des lignes au GridPane
        int rowCount = (int) Math.ceil((double) this.cffDialogController.getRoomList().size() / NUMBER_OF_COLUMNS) ;
        for (int j = 0 ; j < rowCount ; j++)
        {
            this.roomListGridPane.getRowConstraints().add(new RowConstraints()) ;
        }

        // remplissage du GridPane (1 bouton pour chaque salle)
        int index = 0 ;
        for (int j = 0 ; j < this.roomListGridPane.getRowCount() ; j++)
        {
            for (int i = 0 ; i < this.roomListGridPane.getColumnCount() ; i++)
            {
                if (index >= this.cffDialogController.getRoomList().size()) { break ; }
        
                Room room = this.cffDialogController.getRoomList().get(index) ;

                Button button = new Button(room.getNameForDisplay()) ;
                button.setFont(FontLoader.getLittleButtonFont()) ;
                button.setMaxWidth(Double.MAX_VALUE) ;
                button.setMaxHeight(Double.MAX_VALUE) ;
                button.setPrefWidth(200) ;
                button.setMinHeight(35) ;
                button.setOnAction(event -> {
                    if (this.selectedRoomList.contains(room))
                    {
                        this.selectedRoomList.remove(room) ;
                        button.getStyleClass().remove("selected") ;
                    }
                    else
                    {
                        this.selectedRoomList.add(room) ;
                        button.getStyleClass().add("selected") ;
                    }
                    this.updateLowerMenuButtonStatus() ;
                }) ;
        
                this.roomListGridPane.add(button, i, j) ;
        
                index++ ;
            }

            if (index >= this.cffDialogController.getRoomList().size()) { break ; }
        }
    }

    /**
     * Initialise le menu de sélection des types de données des salles.
     */
    private void initRoomDataTypeSelectionMenu()
    {
        this.roomDataTypeListVBox.getChildren().clear() ;

        for (RoomDataType roomDataType : this.cffDialogController.getRoomDataTypeList())
        {
            Button button = new Button(roomDataType.getNameForDisplay()) ;
            button.setFont(FontLoader.getLittleButtonFont()) ;
            button.setMaxWidth(Double.MAX_VALUE) ;
            button.setMaxHeight(Double.MAX_VALUE) ;
            button.setMinWidth(220) ;
            button.setMinHeight(35) ;

            Label thresholdLabel = new Label("Seuil d'alerte :") ;
            thresholdLabel.setFont(FontLoader.getTableHeaderFont()) ;
            thresholdLabel.setTextFill(Color.web("#fff")) ;
            thresholdLabel.setTextAlignment(TextAlignment.RIGHT) ;
            thresholdLabel.setMinHeight(35) ;

            TextField thresholdTextField = new TextField(roomDataType.getDefaultThreshold()) ;
            thresholdTextField.setPromptText("Entrez un seuil") ;
            thresholdTextField.setFont(FontLoader.getContentFont()) ;
            thresholdTextField.setPrefWidth(120) ;
            thresholdTextField.setMinHeight(35) ;
            thresholdTextField.setDisable(true) ;

            Label unitLabel = new Label(roomDataType.getUnit()) ;
            unitLabel.setFont(FontLoader.getContentFont()) ;
            unitLabel.setTextFill(Color.web("#fff")) ;
            unitLabel.setMinWidth(50) ;
            unitLabel.setMinHeight(35) ;

            HBox thresholdContainer = new HBox() ;
            thresholdContainer.setAlignment(Pos.CENTER_RIGHT) ;
            thresholdContainer.setSpacing(10) ;
            thresholdContainer.getChildren().add(thresholdLabel) ;
            thresholdContainer.getChildren().add(thresholdTextField) ;
            thresholdContainer.getChildren().add(unitLabel) ;

            HBox.setHgrow(thresholdContainer, Priority.ALWAYS) ;

            HBox dataTypeContainer = new HBox() ;
            dataTypeContainer.setSpacing(50) ;
            dataTypeContainer.getChildren().add(button) ;
            dataTypeContainer.getChildren().add(thresholdContainer) ;

            this.roomDataTypeListVBox.getChildren().add(dataTypeContainer) ;

            button.setOnAction(event -> {
                if (this.selectedRoomDataTypeList.contains(roomDataType))
                {
                    this.selectedRoomDataTypeList.remove(roomDataType) ;
                    button.getStyleClass().remove("selected") ;
                    thresholdTextField.setDisable(true) ;
                }
                else
                {
                    this.selectedRoomDataTypeList.add(roomDataType) ;
                    button.getStyleClass().add("selected") ;
                    thresholdTextField.setDisable(false) ;
                }
                this.updateLowerMenuButtonStatus() ;
            }) ;
        }
    }

    /**
     * Initialise le menu des paramètres avancés.
     */
    private void initAdvancedSettingsMenu()
    {
        this.frequencyTextField.setText(String.valueOf(this.cffDialogController.getDefaultReadingFrequency())) ;
    }

    /**
     * Met à jour l'état des boutons du menu inférieur.
     */
    private void updateLowerMenuButtonStatus()
    {
        if (    this.selectedRoomList.size() == 0
            ||  this.selectedRoomDataTypeList.size() == 0
        ) {
            this.saveButton.setDisable(true) ;
        }
        else
        {
            this.saveButton.setDisable(false) ;
        }
    }
}