package application.view ;

import application.control.DataVisualisationPane ;
import application.data.DataTypeUtilities ;
import application.model.DataRow ;
import application.styles.FontLoader ;
import application.tools.DataFileReading ;
import application.tools.GraphGenerator ;

import java.util.HashMap ;
import java.util.List ;
import java.util.Map ;

import javafx.beans.property.SimpleStringProperty ;
import javafx.collections.FXCollections ;
import javafx.collections.ObservableList ;
import javafx.fxml.FXML ;
import javafx.geometry.Insets ;
import javafx.geometry.Pos ;
import javafx.scene.Node ;
import javafx.scene.chart.BarChart ;
import javafx.scene.chart.LineChart ;
import javafx.scene.chart.XYChart;
import javafx.scene.control.Button ;
import javafx.scene.control.ComboBox;
import javafx.scene.control.Label ;
import javafx.scene.control.TableCell ;
import javafx.scene.control.TableColumn ;
import javafx.scene.control.TableView ;
import javafx.scene.image.Image ;
import javafx.scene.image.ImageView ;
import javafx.scene.layout.HBox ;
import javafx.scene.layout.VBox ;
import javafx.scene.text.Font ;
import javafx.stage.Stage ;
import javafx.stage.WindowEvent ;

/**
 * Contrôleur de vue de la fenêtre de visualisation des données.
 * 
 * Date de dernière modification :
 * - Vendredi 6 décembre 2024 -
 * 
 * @author Nolhan Biblocque
 * @author Victor Jockin
 * @author Mathys Laguilliez
 * - Équipe 2B12 -
 */
public class DataVisualisationPaneViewController
{
    // déclaration des attributs
    private Stage stage ;
    private DataVisualisationPane dvpDialogController ;
    private ObservableList<DataRow> dataTableViewOList ;

    private String displayedRoom        = null ;        // la salle affichée dans la vision détaillée
    private String displayedDataType    = null ;        // le type de données visualisé pour la salle affichée dans la vision détaillée
    private Button selectedHeaderButton = null ;        // le bouton correspondant au type de données pour toutes les salles affiché dans la vision détaillée

    // éléments graphiques de la vue FXML (ordonnés par ordre d'apparition)
    @FXML private HBox root ;
    @FXML private VBox mainContentVBox ;
    @FXML private VBox containersVBox ;
    @FXML private HBox littleContainersVBox ;
    @FXML private HBox bigContainersVBox ;
    @FXML private VBox dataListContainerVBox ;
    @FXML private VBox dataDetailContainerVBox ;
    @FXML private VBox alertListContainerVBox ;
    @FXML private VBox dataTypeListVBox ;
    @FXML private ComboBox<String> dataTypeListComboBox ;
    @FXML private VBox graphTitleVBox ;
    @FXML private Label graphTitleLabel ;
    @FXML private VBox graphVBox ;
    @FXML private VBox alertListVBox ;
    @FXML private TableView<DataRow> dataTableView ;

    public void setStage(Stage _stage)
    {
        this.stage = _stage ;
    }

    public void setDvpDialogController(DataVisualisationPane _dvpDialogController)
    {
        this.dvpDialogController = _dvpDialogController ;
    }

    /**
     * Initialise la vue.
     */
    public void initializeView()
    {
        this.stage.setOnCloseRequest(e -> this.closeWindow(e)) ;

        // préchargement des fonts (pour utilisation dans la feuille de style dvp.css)
        Font whFont     = FontLoader.getWindowHeaderFont() ;
        Font chFont     = FontLoader.getContainerHeaderFont() ;
        Font thFont     = FontLoader.getTableHeaderFont() ;
        Font cFont      = FontLoader.getContentFont() ;
        Font sdhFont    = FontLoader.getSingleDataHeaderFont() ;
        Font sdFont     = FontLoader.getSingleDataFont() ;
        Font gtFont     = FontLoader.getGraphTitleFont() ;

        // paramétrage des tailles des conteneurs
        // --------------------------------------
        // paramétrage des largeurs des conteneurs
        this.dataListContainerVBox.prefWidthProperty().bind(this.mainContentVBox.widthProperty().multiply(1.9/5.0)) ;
        this.dataDetailContainerVBox.prefWidthProperty().bind(this.mainContentVBox.widthProperty().multiply(1.9/5.0)) ;
        this.alertListContainerVBox.prefWidthProperty().bind(this.mainContentVBox.widthProperty().multiply(1.2/5.0)) ;
        // paramétrage des hauteurs des conteneurs
        this.littleContainersVBox.prefHeightProperty().bind(this.containersVBox.heightProperty().multiply(1.1/5.0)) ;
        this.bigContainersVBox.prefHeightProperty().bind(this.containersVBox.heightProperty().multiply(3.9/5.0)) ;

        // initialisation de l'ObservableList de la TableView
        this.dataTableViewOList = FXCollections.observableArrayList() ;

        // initialisation de la TableView
        List<String> headersList = this.dvpDialogController.getDataTypeList() ;
        for (int i = 0 ; i < headersList.size() ; i++)
        {
            String header = headersList.get(i) ;

            // initialisation d'un bouton faisant office d'en-tête
            Button button = new Button(DataTypeUtilities.getAbbreviation(header)+" ("+DataTypeUtilities.getUnit(header)+")") ;
            button.setId(header) ;
            button.setMinWidth(80) ;
            button.setFont(FontLoader.getTableHeaderFont()) ;
            button.getStyleClass().add("table-header") ;
            if (header.compareTo("room") == 0)
            {
                button.setText(DataTypeUtilities.getFullTitle(header)) ;
                button.getStyleClass().add("not-clickable") ;
            }
            else
            {
                button.setOnAction(
                    event -> {
                        // nettoyage de la sélection de ligne dans la TableView
                        this.dataTableView.getSelectionModel().clearSelection() ;

                        // mise à jour de l'en-tête sélectionnée
                        if (this.selectedHeaderButton != null) { this.selectedHeaderButton.getStyleClass().remove("selected") ; }
                        button.getStyleClass().add("selected") ;
                        this.selectedHeaderButton = button ;
    
                        // affichage d'un graphique de comparaison pour le type de données sélectionné
                        this.displayedRoom = null ;
                        this.displayedDataType = null ;
                        this.displayComparisonGraph(header) ;
                    }
                ) ;
            }

            // intialisation de la colonne
            TableColumn<DataRow, String> tableColumn = new TableColumn<>() ;
            tableColumn.setReorderable(false) ;
            tableColumn.setResizable(false) ;
            tableColumn.setSortable(false) ;
            tableColumn.setPrefWidth(button.getMinWidth()) ;
            tableColumn.setGraphic(button) ;

            if (i == 0)
            {
                tableColumn.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getName())) ;
                tableColumn.setCellFactory(
                    c -> new TableCell<DataRow, String>()
                    {
                        @Override
                        protected void updateItem(String item, boolean empty)
                        {
                            super.updateItem(item, empty) ;
                            setText(item) ;
                            getStyleClass().add("first-table-column") ;
                        }
                    }
                ) ;
            }
            else
            {
                tableColumn.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getData().get(header))) ;
            }
            this.dataTableView.getColumns().add(tableColumn) ;
        }

        // initialisation d'un écouteur d'évènements sur les lignes de la TableView
        this.dataTableView.getSelectionModel().selectedItemProperty().addListener(
            (observable, oldValue, newValue) -> {
                if (newValue != null)
                {
                    // mise à jour de l'en-tête sélectionnée
                    if (this.selectedHeaderButton != null)
                    {
                        this.selectedHeaderButton.getStyleClass().remove("selected") ;
                        this.selectedHeaderButton = null ;
                    }

                    // affichage d'un graphique d'évolution pour la salle sélectionnée
                    this.displayedRoom = newValue.getName() ;
                    if (newValue != null) { this.displayEvolutionGraph(newValue.getName(), this.displayedDataType) ; }
                }
            }
        ) ;

        // initialisation de la liste déroulante des types de données
        // (quand affichage d'un graphique d'évolution)
        this.dataTypeListComboBox.getItems().clear() ;
        this.dataTypeListComboBox.getItems().addAll(
            DataTypeUtilities.getAllFullTitles(
                this.dvpDialogController.getDataTypeList().subList(1, this.dvpDialogController.getDataTypeList().size())
            )
        ) ;
        this.dataTypeListComboBox.setOnAction(event -> {
            if (    this.dataTypeListComboBox.isFocused()
                &&  this.dataTypeListComboBox.getSelectionModel().getSelectedItem() != null
            ) {
                this.displayedDataType = DataTypeUtilities.getDataTypeByFullTitle(
                    this.dataTypeListComboBox.getSelectionModel().getSelectedItem()
                ) ;
                this.displayEvolutionGraph(this.displayedRoom, this.displayedDataType) ;
            }
        }) ;

        // initialisation des styles label titre de graphique
        this.graphTitleLabel.setFont(FontLoader.getGraphTitleFont()) ;
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
     * @return null
     */
    private Object closeWindow(WindowEvent e)
    {
        this.doClose() ;
		e.consume() ;
		return null ;
	}

    /**
     * Met à jour / Rafraîchit l'affichage des données.
     */
    public void updateDataDisplay()
    {
        Map<String, Map<String, String>> dataMap = this.dvpDialogController.getDataMap() ;

        Map<String, DataRow> existingDataRowsMap = new HashMap<>() ;
        for (DataRow dr : this.dataTableViewOList) { existingDataRowsMap.put(dr.getName(), dr) ; }

        // mise à jour de la TableView
        for (Map.Entry<String, Map<String, String>> m : dataMap.entrySet())
        {
            if (existingDataRowsMap.containsKey(m.getKey()))
            {
                DataRow dataRow = existingDataRowsMap.get(m.getKey()) ;
                dataRow.updateData(m.getValue()) ;
            }
            else
            {
                DataRow dataRow = new DataRow(m.getKey(), m.getValue()) ;
                this.dataTableViewOList.add(dataRow) ;
            }
        }
        this.dataTableView.setItems(this.dataTableViewOList) ;
        this.dataTableView.refresh() ;

        // mise à jour du graphique affiché
        if (this.selectedHeaderButton != null)
        {
            this.displayComparisonGraph(this.selectedHeaderButton.getId()) ;
        }
        else if (this.dataTableView.getSelectionModel().getSelectedItem() != null)
        {
            DataRow selectedDataRow = this.dataTableView.getSelectionModel().getSelectedItem() ;
            this.displayEvolutionGraph(selectedDataRow.getName(), this.displayedDataType) ;
        }
    }

    /**
     * Met à jour / Rafraîchit l'affichage des alertes relatives aux données.
     */
    public void updateAlertDisplay()
    {
        Map<String, Map<String, String>> alertMap = this.dvpDialogController.getAlertMap() ;

        this.alertListVBox.getChildren().clear() ;

        for (Map.Entry<String, Map<String, String>> m : alertMap.entrySet())
        {
            // chargement des fonts utilisées
            Font sdhFont    = FontLoader.getSingleDataHeaderFont() ;
            Font sdFont     = FontLoader.getSingleDataFont() ;

            ImageView alertIcon = new ImageView(new Image(DataVisualisationPaneViewController.class.getResourceAsStream("/application/image/dvp/alert-icon.png"))) ;
            alertIcon.setFitHeight(30) ;
            alertIcon.setPreserveRatio(true) ;

            Label alertName = new Label(
                    DataTypeUtilities.getFullTitle(m.getValue().get("dataType"))
                +   " ["+m.getKey()+"]"
            ) ;
            alertName.setPrefHeight(40) ;

            HBox alertHeader = new HBox() ;
            alertHeader.getChildren().add(alertIcon) ;
            alertHeader.getChildren().add(alertName) ;
            alertHeader.setAlignment(Pos.CENTER_LEFT) ;
            alertHeader.setSpacing(10) ;

            Label thresholdHeader = new Label("Seuil") ;
            thresholdHeader.setPrefHeight(30) ;
            thresholdHeader.setAlignment(Pos.BOTTOM_RIGHT) ;
            thresholdHeader.setFont(sdhFont) ;

            Label threshold = new Label(
                    m.getValue().get("threshold")
                +   " "+DataTypeUtilities.getUnit(m.getValue().get("dataType"))
            ) ;
            threshold.setFont(sdFont) ;

            VBox thresholdContainer = new VBox() ;
            thresholdContainer.getChildren().add(thresholdHeader) ;
            thresholdContainer.getChildren().add(threshold) ;

            Label measuredValueHeader = new Label("Relevé") ;
            measuredValueHeader.setPrefHeight(30) ;
            measuredValueHeader.setAlignment(Pos.BOTTOM_RIGHT) ;
            measuredValueHeader.setFont(sdhFont) ;

            Label measuredValue = new Label(
                    m.getValue().get("measuredValue")
                +   " "+DataTypeUtilities.getUnit(m.getValue().get("dataType"))
            ) ;
            measuredValue.setFont(sdFont) ;

            VBox measuredValueContainer = new VBox() ;
            measuredValueContainer.getChildren().add(measuredValueHeader) ;
            measuredValueContainer.getChildren().add(measuredValue) ;

            HBox alertContent = new HBox() ;
            alertContent.setSpacing(20) ;
            alertContent.getChildren().add(thresholdContainer) ;
            alertContent.getChildren().add(measuredValueContainer) ;

            thresholdContainer.prefWidthProperty().bind(alertContent.widthProperty().multiply(1/2.0)) ;
            measuredValueContainer.prefWidthProperty().bind(alertContent.widthProperty().multiply(1/2.0)) ;    

            VBox alertContainer = new VBox() ;
            alertContainer.getChildren().add(alertHeader) ;
            alertContainer.getChildren().add(alertContent) ;
            alertContainer.getStyleClass().add("alert-container") ;

            VBox.setMargin(alertHeader, new Insets(20, 20, 0, 20)) ;
            VBox.setMargin(alertContent, new Insets(0, 20, 20, 20)) ;

            this.alertListVBox.getChildren().add(alertContainer) ;
        }
    }

    /**
     * Ouvre la fenêtre de paramétrage de la configuration.
     */
    @FXML
    private void doConfiguration()
    {
        this.dvpDialogController.parametrerConfiguration() ;
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
     * Affiche un graphique de comparaison à partir d'un type de données.
     * @param pDataType un type de données
     */
    private void displayComparisonGraph(String pDataType)
    {
        this.dataTypeListVBox.setVisible(false) ;
        this.dataTypeListVBox.setManaged(false) ;

        this.graphTitleLabel.setText(DataTypeUtilities.getComparisonGraphTitle(pDataType)) ;

        Map<String, String> dataMap = new HashMap<>() ;
        for (DataRow dataRow : this.dataTableViewOList)
        {
            dataMap.put(dataRow.getName(), dataRow.getData().get(pDataType)) ;
        }
        BarChart<String, Number> barChart = GraphGenerator.GenerateBarChart(dataMap, pDataType) ;
        barChart.maxWidthProperty().bind(this.graphVBox.widthProperty()) ;
        this.graphVBox.getChildren().clear() ;
        this.graphVBox.getChildren().add(barChart) ;

        // initialisation d'un écouteur d'évènements sur les barres du diagramme
        for (XYChart.Series<String, Number> dataSeries : barChart.getData())
        {
            for (XYChart.Data<String, Number> data : dataSeries.getData())
            {
                Node bar = data.getNode() ;
                bar.setOnMouseClicked(event -> {
                    // récupération de la salle et du type de données à représenter
                    this.displayedRoom = data.getXValue() ;
                    this.displayedDataType = pDataType ;

                    // désélection de l'en-tête sélectionnée dans la TableView
                    this.selectedHeaderButton.getStyleClass().remove("selected") ;
                    this.selectedHeaderButton = null ;

                    // auto-sélection du type de données dans la liste déroulante
                    this.dataTypeListComboBox.setValue(DataTypeUtilities.getFullTitle(this.displayedDataType)) ;

                    // auto-sélection de la ligne de la TableView correspondant à la salle à représenter
                    for (DataRow dataRow : this.dataTableViewOList)
                    {
                        if (dataRow.getName().equals(this.displayedRoom))
                        {
                            this.dataTableView.getSelectionModel().select(dataRow) ;
                            break ;
                        }
                    }

                    // affichage du graphique d'évolution
                    this.displayEvolutionGraph(this.displayedRoom, this.displayedDataType) ;
                }) ;
            }
        }
    }

    /**
     * Affiche un graphique d'évolution à partir d'une salle et d'un type de données.
     * @param pRoom     une salle
     * @param pDataType un type de données
     */
    private void displayEvolutionGraph(String pRoom, String pDataType)
    {
        this.dataTypeListVBox.setManaged(true) ;
        this.dataTypeListVBox.setVisible(true) ;

        if (pDataType == null)
        {
            this.displayedDataType = this.dvpDialogController.getDataTypeList().get(1) ;
            this.dataTypeListComboBox.setValue(DataTypeUtilities.getFullTitle(this.displayedDataType)) ;
        }
        else
        {
            this.displayedDataType = pDataType ;
        }

        this.graphTitleLabel.setText(DataTypeUtilities.getEvolutionGraphTitle(this.displayedDataType)) ;

        this.graphVBox.getChildren().clear() ;
        LineChart<String, Number> lineChart = GraphGenerator.GenerateLineChart(
            DataFileReading.getHistory(pRoom, this.displayedDataType),
            pRoom,
            this.displayedDataType, 5.0
        ) ;
        lineChart.maxWidthProperty().bind(this.graphVBox.widthProperty()) ;
        this.graphVBox.getChildren().add(lineChart) ;
    }
}