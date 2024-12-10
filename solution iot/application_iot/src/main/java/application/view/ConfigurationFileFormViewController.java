package application.view ;

import application.control.ConfigurationFileForm ;
import application.data.enums.Room ;
import application.data.enums.RoomDataType ;
import application.data.enums.Sensor ;
import application.data.enums.SolarPanelDataType ;
import application.styles.FontLoader ;

import java.util.ArrayList ;
import java.util.Arrays ;
import java.util.HashMap;
import java.util.List ;
import java.util.Map ;
import java.util.stream.Collectors ;

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
    private String enteredConfigurationName                         = "Nouvelle Configuration" ;
    private Sensor selectedSensorType                               = Sensor.AM107 ;
    private List<Room> selectedRoomList                             = new ArrayList<>() ;
    private Map<RoomDataType, Double> selectedRoomDataTypeMap       = new HashMap<>() ;
    private List<SolarPanelDataType> selectedSolarPanelDataTypeList = new ArrayList<>() ;
    private int enteredReadingFrequency ;

    // éléments graphiques de la vue FXML (ordonnés par ordre d'apparition)
    // --------------------------------------------------------------------

    // menu des paramètres généraux
    @FXML private TextField configNameTextField ;

    // menu de sélection du type de capteurs
    @FXML private Button roomSensorsButton ;
    @FXML private Button solarPanelSensorsButton ;

    // menu de sélection des salles (AM107 uniquement)
    @FXML private VBox roomSelectionMenu ;
    @FXML private GridPane roomListGridPane ;

    // menu de sélection des types de données relatifs aux salles (AM107 uniquement)
    @FXML private VBox roomDataTypeSelectionMenu ;
    @FXML private VBox roomDataTypeListVBox ;

    // menu de sélection des types de données relatifs aux panneaux solaires (SOLAREDGE uniquement)
    @FXML private VBox solarPanelDataTypeSelectionMenu ;
    @FXML private VBox solarPanelDataTypeListVBox ;

    // menu des paramètres avancés
    @FXML private TextField frequencyTextField ;

    // menu inférieur ( =<^.^>= )
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
        this.initSolarPanelDataTypeSelectionMenu() ;
        this.initAdvancedSettingsMenu() ;

        // affichage du menu de sélection des salles
        this.roomSelectionMenu.setManaged(true) ;
        this.roomSelectionMenu.setVisible(true) ;

        // affichage du menu de sélection des types de données relatifs aux salles
        this.roomDataTypeSelectionMenu.setManaged(true) ;
        this.roomDataTypeSelectionMenu.setVisible(true) ;

        // dissimulation du menu de sélection des types de données relatifs aux panneaux solaires
        this.solarPanelDataTypeSelectionMenu.setVisible(false) ;
        this.solarPanelDataTypeSelectionMenu.setManaged(false) ;

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
        switch (this.selectedSensorType)
        {
            case AM107 :
                // réinitialisation de la configuration
                this.selectedRoomList.clear() ;
                this.selectedRoomDataTypeMap.clear() ;
                // réinitialisation des menus
                this.initRoomSelectionMenu() ;
                this.initRoomDataTypeSelectionMenu() ;
                break ;

            case SOLAREDGE :
                // réinitialisation de la configuration
                this.selectedSolarPanelDataTypeList.clear() ;
                // réinitialisation des menus
                this.initSolarPanelDataTypeSelectionMenu() ;
                break ;

            default :
                break ;
        }

        // réinitialisation du menu des paramètres avancés
        this.initAdvancedSettingsMenu() ;

        // mise à jour de l'état des boutons du menu inférieur
        this.updateLowerMenuButtonStatus() ;
    }

    /**
     * Enregistre la configuration.
     */
    @FXML
    private void doSave()
    {
        // vérification de la validité des champs de saisie
        this.validateConfigurationNameInput() ;
        this.validateFrequencyInput() ;

        // récupération des paramètres de la configuration
        // -----------------------------------------------

        // nom de la configuration
        String name = this.enteredConfigurationName ;

        // topic MQTT
        String topicPrefix = this.selectedSensorType.getNameForTopic() ;

        // listes des sujets à observer est des données à récupérer
        List<String> subjectList            = new ArrayList<>() ;
        List<String> dataTypeList           = new ArrayList<>() ;
        Map<String, Double> thresholdMap    = new HashMap<>() ;
        switch (this.selectedSensorType)
        {
            case AM107 :
                for (Room room : this.selectedRoomList) { subjectList.add(room.getNameForTopic()) ; }
                for (Map.Entry<RoomDataType, Double> m : this.selectedRoomDataTypeMap.entrySet())
                {
                    dataTypeList.add(m.getKey().getNameForDataReading()) ;
                    thresholdMap.put(m.getKey().getNameForDataReading(), m.getValue()) ;
                }
                break ;

            case SOLAREDGE :
                subjectList = Arrays.asList("overview") ;
                for (SolarPanelDataType dataType : this.selectedSolarPanelDataTypeList) { dataTypeList.add(dataType.getNameForDataReading()) ; }
                break ;

            default :
                break ;
        }

        // fréquence de lecture des données
        int readingFrequency = this.enteredReadingFrequency ;

        // enregistrement de la configuration
        this.cffDialogController.enregistrerConfiguration(
            name,
            topicPrefix,
            subjectList,
            dataTypeList,
            thresholdMap,
            readingFrequency
        ) ;
    }

    /**
     * Initialise le menu de sélection du type de capteurs.
     */
    private void initSensorTypeSelectionMenu()
    {
        // pré-remplissage du champ de saisie du nom de la configuration
        this.configNameTextField.setText(this.enteredConfigurationName) ;

        // écouteur d'évènements sur le champ de saisie du nom de la configuration
        this.configNameTextField.focusedProperty().addListener(
            (observable, oldValue, newValue) -> {
                if (!newValue) { this.validateConfigurationNameInput() ; }
            }
        ) ;

        // sélection des capteurs AM107 par défaut
        this.roomSensorsButton.getStyleClass().add("selected") ;

        // écouteurs d'évèneemnts sur le bouton "switch" de sélection du type de capteurs
        this.roomSensorsButton.setOnAction(event -> {
            if (this.selectedSensorType != Sensor.AM107)
            {
                this.selectedSensorType = Sensor.AM107 ;
                this.solarPanelSensorsButton.getStyleClass().remove("selected") ;
                this.roomSensorsButton.getStyleClass().add("selected") ;

                this.roomSelectionMenu.setManaged(true) ;
                this.roomSelectionMenu.setVisible(true) ;

                this.roomDataTypeSelectionMenu.setManaged(true) ;
                this.roomDataTypeSelectionMenu.setVisible(true) ;

                this.solarPanelDataTypeSelectionMenu.setVisible(false) ;
                this.solarPanelDataTypeSelectionMenu.setManaged(false) ;

                this.updateLowerMenuButtonStatus() ;
            }
        }) ;
        this.solarPanelSensorsButton.setOnAction(event -> {
            if (this.selectedSensorType != Sensor.SOLAREDGE)
            {
                this.selectedSensorType = Sensor.SOLAREDGE ;
                this.roomSensorsButton.getStyleClass().remove("selected") ;
                this.solarPanelSensorsButton.getStyleClass().add("selected") ;

                this.roomSelectionMenu.setVisible(false) ;
                this.roomSelectionMenu.setManaged(false) ;

                this.roomDataTypeSelectionMenu.setVisible(false) ;
                this.roomDataTypeSelectionMenu.setManaged(false) ;

                this.solarPanelDataTypeSelectionMenu.setManaged(true) ;
                this.solarPanelDataTypeSelectionMenu.setVisible(true) ;

                this.updateLowerMenuButtonStatus() ;
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
            button.setMinWidth(220) ;
            button.setMinHeight(35) ;

            Label thresholdLabel = new Label("Seuil d'alerte ("+roomDataType.getMinThreshold()+"-"+roomDataType.getMaxThreshold()+") :") ;
            thresholdLabel.setFont(FontLoader.getTableHeaderFont()) ;
            thresholdLabel.setTextFill(Color.web("#fff")) ;
            thresholdLabel.setTextAlignment(TextAlignment.RIGHT) ;
            thresholdLabel.setMinWidth(200) ;
            thresholdLabel.setMinHeight(35) ;

            TextField thresholdTextField = new TextField(roomDataType.getDefaultThreshold()) ;
            thresholdTextField.setPromptText("Entrez un seuil") ;
            thresholdTextField.setFont(FontLoader.getContentFont()) ;
            thresholdTextField.setPrefWidth(80) ;
            thresholdTextField.setMinHeight(35) ;
            thresholdTextField.setDisable(true) ;

            Label unitLabel = new Label(roomDataType.getUnit()) ;
            unitLabel.setFont(FontLoader.getContentFont()) ;
            unitLabel.setTextFill(Color.web("#fff")) ;
            unitLabel.setMinWidth(50) ;
            unitLabel.setMinHeight(35) ;

            HBox thresholdContainer = new HBox() ;
            thresholdContainer.setAlignment(Pos.CENTER_LEFT) ;
            thresholdContainer.setSpacing(10) ;
            thresholdContainer.getChildren().add(thresholdLabel) ;
            thresholdContainer.getChildren().add(thresholdTextField) ;
            thresholdContainer.getChildren().add(unitLabel) ;

            HBox.setHgrow(thresholdContainer, Priority.ALWAYS) ;

            HBox dataTypeContainer = new HBox() ;
            dataTypeContainer.setSpacing(40) ;
            dataTypeContainer.getChildren().add(button) ;
            dataTypeContainer.getChildren().add(thresholdContainer) ;

            this.roomDataTypeListVBox.getChildren().add(dataTypeContainer) ;

            button.setOnAction(event -> {
                if (this.selectedRoomDataTypeMap.containsKey(roomDataType))
                {
                    this.selectedRoomDataTypeMap.remove(roomDataType) ;
                    button.getStyleClass().remove("selected") ;
                    thresholdTextField.setDisable(true) ;
                }
                else
                {
                    this.selectedRoomDataTypeMap.put(roomDataType, Double.parseDouble(thresholdTextField.getText())) ;
                    button.getStyleClass().add("selected") ;
                    thresholdTextField.setDisable(false) ;
                }
                this.updateLowerMenuButtonStatus() ;
            }) ;

            // écouteur d'évènements sur le champ de saisie du seuil d'alerte
            thresholdTextField.focusedProperty().addListener(
                (observable, oldValue, newValue) -> {
                    if (!newValue) { this.validateThresholdInput(roomDataType, thresholdTextField) ; }
                }
            ) ;
        }
    }

    /**
     * Initialise le menu de sélection des types de données des panneaux solaires.
     */
    private void initSolarPanelDataTypeSelectionMenu()
    {
        this.solarPanelDataTypeListVBox.getChildren().clear() ;

        for (SolarPanelDataType dataType : this.cffDialogController.getSolarPanelDataTypeList())
        {
            Button button = new Button(dataType.getNameForDisplay()) ;
            button.setFont(FontLoader.getLittleButtonFont()) ;
            button.setMinWidth(220) ;
            button.setMinHeight(35) ;

            Label descLabel = new Label(dataType.getDescription()) ;
            descLabel.setFont(FontLoader.getContentFont()) ;
            descLabel.setTextFill(Color.web("#fff")) ;
            descLabel.setMinWidth(360) ;
            descLabel.setMinHeight(35) ;

            HBox dataTypeContainer = new HBox() ;
            dataTypeContainer.setSpacing(40) ;
            dataTypeContainer.getChildren().add(button) ;
            dataTypeContainer.getChildren().add(descLabel) ;

            this.solarPanelDataTypeListVBox.getChildren().add(dataTypeContainer) ;

            button.setOnAction(event -> {
                if (this.selectedSolarPanelDataTypeList.contains(dataType))
                {
                    this.selectedSolarPanelDataTypeList.remove(dataType) ;
                    button.getStyleClass().remove("selected") ;
                }
                else
                {
                    this.selectedSolarPanelDataTypeList.add(dataType) ;
                    button.getStyleClass().add("selected") ;
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
        // écouteur d'évènements sur le champ de saisie de la fréquence de lecture des données
        this.frequencyTextField.setText(String.valueOf(this.cffDialogController.getDefaultReadingFrequency())) ;
        this.frequencyTextField.focusedProperty().addListener(
            (observable, oldValue, newValue) -> {
                if (!newValue) { this.validateFrequencyInput() ; }
            }
        ) ;
    }

    /**
     * Met à jour l'état des boutons du menu inférieur.
     */
    private void updateLowerMenuButtonStatus()
    {
        if (    this.selectedSensorType == Sensor.AM107
            && (this.selectedRoomList.size() == 0 || this.selectedRoomDataTypeMap.size() == 0)
            ||  this.selectedSensorType == Sensor.SOLAREDGE
            && (this.selectedSolarPanelDataTypeList.size() == 0)
        ) {
            this.saveButton.setDisable(true) ;
        }
        else
        {
            this.saveButton.setDisable(false) ;
        }
    }

    /**
     * Valide ou non la saisie du nom de la configuration.
     */
    private void validateConfigurationNameInput()
    {
        String name = this.configNameTextField.getText() ;
        if (this.cffDialogController.isConfigurationNameValid(name))
        {
            this.enteredConfigurationName = name ;
        }
        this.configNameTextField.setText(this.enteredConfigurationName) ;
    }

    /**
     * Valide ou non la saisie d'un seuil d'alerte.
     */
    private void validateThresholdInput(RoomDataType pRoomDataType, TextField pTextField)
    {
        try
        {
            double threshold = Double.parseDouble(pTextField.getText()) ;
            if (this.cffDialogController.isThresholdValid(pRoomDataType, threshold))
            {
                this.selectedRoomDataTypeMap.put(pRoomDataType, threshold) ;
            }
            pTextField.setText(String.valueOf(this.selectedRoomDataTypeMap.get(pRoomDataType))) ;
        }
        catch (NumberFormatException nfe)
        {
            pTextField.setText(String.valueOf(this.selectedRoomDataTypeMap.get(pRoomDataType))) ;
        }
    }

    /**
     * Valide ou non la saisie de la fréquence de lecture des données.
     */
    private void validateFrequencyInput()
    {
        try
        {
            int frequency = (int) Double.parseDouble(this.frequencyTextField.getText()) ;
            if (this.cffDialogController.isReadingFrequencyValid(frequency))
            {
                this.enteredReadingFrequency = frequency ;
            }
            this.frequencyTextField.setText(String.valueOf(this.enteredReadingFrequency)) ;
        }
        catch (NumberFormatException nfe)
        {
            this.frequencyTextField.setText(String.valueOf(this.enteredReadingFrequency)) ;
        }
    }
}