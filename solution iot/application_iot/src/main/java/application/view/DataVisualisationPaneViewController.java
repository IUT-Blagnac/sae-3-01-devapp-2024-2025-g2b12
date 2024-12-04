package application.view ;

import application.control.DataVisualisationPane ;
import application.data.DataUtilities ;
import application.model.DataRow ;
import application.styles.FontLoader ;
import application.tools.GraphGenerator ;

import java.util.ArrayList;
import java.util.HashMap ;
import java.util.List ;
import java.util.Map ;

import javafx.beans.property.SimpleStringProperty ;
import javafx.collections.FXCollections ;
import javafx.collections.ObservableList ;
import javafx.fxml.FXML ;
import javafx.geometry.Pos;
import javafx.scene.chart.BarChart ;
import javafx.scene.chart.LineChart;
import javafx.scene.control.Button ;
import javafx.scene.control.Label ;
import javafx.scene.control.TableCell ;
import javafx.scene.control.TableColumn ;
import javafx.scene.control.TableView ;
import javafx.scene.image.Image ;
import javafx.scene.image.ImageView;
import javafx.scene.layout.HBox ;
import javafx.scene.layout.VBox ;
import javafx.scene.text.Font ;
import javafx.stage.Stage ;

/**
 * Contrôleur de vue de la fenêtre de visualisation des données.
 * 
 * Date de dernière modification :
 * - Jeudi 20 novembre 2024 -
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
    private Button selectedHeaderButton = null ;

    // récupération des éléments graphiques de la vue FXML
    @FXML private VBox mainContentVBox ;
    @FXML private VBox dataListContainerVBox ;
    @FXML private VBox dataDetailContainerVBox ;
    @FXML private VBox alertListContainerVBox ;
    @FXML private VBox graphContainerVBox ;
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
     * Initialise le contrôleur de vue.
     */
    public void initializeViewElements()
    {
        // préchargement des fonts (pour utilisation dans la feuille de style dvp.css)
        Font whFont = FontLoader.getWindowHeaderFont() ;
        Font chFont = FontLoader.getContainerHeaderFont() ;
        Font thFont = FontLoader.getTableHeaderFont() ;
        Font cFont  = FontLoader.getContentFont() ;

        // paramétrages des tailles des conteneurs
        this.dataListContainerVBox.prefWidthProperty().bind(this.mainContentVBox.widthProperty().multiply(1.9/5.0)) ;
        this.dataDetailContainerVBox.prefWidthProperty().bind(this.mainContentVBox.widthProperty().multiply(1.9/5.0)) ;
        this.alertListContainerVBox.prefWidthProperty().bind(this.mainContentVBox.widthProperty().multiply(1.2/5.0)) ;

        // initialisation de l'ObservableList de la TableView
        this.dataTableViewOList = FXCollections.observableArrayList() ;

        // initialisation de la TableView
        List<String> headersList = this.dvpDialogController.getDataTypeList() ;
        for (int i = 0 ; i < headersList.size() ; i++)
        {
            String header = headersList.get(i) ;

            // initialisation d'un bouton faisant office d'en-tête
            Button button = new Button(DataUtilities.getDisplayDataType(header)) ;
            button.setMinWidth(80) ;
            button.setFont(thFont) ;
            button.getStyleClass().add("table-header") ;
            if (header.compareTo("room") == 0)
            {
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
                tableColumn.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getName().toUpperCase())) ;
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
                // mise à jour de l'en-tête sélectionnée
                if (this.selectedHeaderButton != null)
                {
                    this.selectedHeaderButton.getStyleClass().remove("selected") ;
                    this.selectedHeaderButton = null ;
                }

                // affichage d'un graphique d'évolution pour la salle sélectionnée
                if (newValue != null) { this.displayEvolutionGraph(newValue.getName(), null) ; }
            }
        ) ;
    }

    /**
     * Affiche la fenêtre.
     */
    public void displayDialog()
    {
        this.stage.show() ;
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
            for (String dataType : this.dvpDialogController.getDataTypeList())
            {
                if (DataUtilities.getDisplayDataType(dataType).compareTo(this.selectedHeaderButton.getText()) == 0)
                {
                    this.displayComparisonGraph(dataType) ;
                }
            }
        }
        else if (this.dataTableView.getSelectionModel().getSelectedItem() != null)
        {
            this.displayEvolutionGraph(this.dataTableView.getSelectionModel().getSelectedItem().getName(), null) ;
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
            ImageView alertIcon = new ImageView(new Image(DataVisualisationPaneViewController.class.getResourceAsStream("/application/image/dvp/alert-icon.png"))) ;
            alertIcon.setFitHeight(30) ;
            alertIcon.setPreserveRatio(true) ;

            Label alertName = new Label("Alerte") ;
            alertName.setPrefHeight(40) ;

            HBox alertHeader = new HBox() ;
            alertHeader.getChildren().add(alertIcon) ;
            alertHeader.getChildren().add(alertName) ;
            alertHeader.setAlignment(Pos.CENTER_LEFT) ;
            alertHeader.setSpacing(10) ;

            Label thresholdHeader = new Label("Seuil") ;
            thresholdHeader.setPrefHeight(40) ;
            thresholdHeader.setAlignment(Pos.BOTTOM_RIGHT) ;
            
            Label threshold             = new Label("XX") ;
            Label measuredValueHeader   = new Label("Valeur relevée") ;
            Label measuredValue         = new Label("XX") ;

            VBox alertContent = new VBox() ;
            alertContent.getChildren().add(thresholdHeader) ;
            alertContent.getChildren().add(threshold) ;
            alertContent.getChildren().add(measuredValueHeader) ;
            alertContent.getChildren().add(measuredValue) ;

            VBox alertContainer = new VBox() ;
            alertContainer.getChildren().add(alertHeader) ;
            alertContainer.getChildren().add(alertContent) ;

            this.alertListVBox.getChildren().add(alertContainer) ;
        }

        System.out.println("- Mise à jour de l'affichage des alertes -") ;
        System.out.println(alertMap) ;
    }

    /**
     * Affiche un graphique de comparaison à partir d'un type de données.
     * @param pDataType un type de données
     */
    private void displayComparisonGraph(String pDataType)
    {
        Map<String, String> dataMap = new HashMap<>() ;
        for (DataRow dataRow : this.dataTableViewOList)
        {
            dataMap.put(dataRow.getName(), dataRow.getData().get(pDataType)) ;
        }
        BarChart<String, Number> barChart = GraphGenerator.GenerateBarChart(dataMap, pDataType) ;
        barChart.maxWidthProperty().bind(this.graphContainerVBox.widthProperty()) ;
        this.graphContainerVBox.getChildren().clear() ;
        this.graphContainerVBox.getChildren().add(barChart) ;
    }

    /**
     * Affiche un graphique d'évolution à partir d'une salle et d'un type de données.
     * @param pRoom     une salle
     * @param pDataType un type de données
     */
    private void displayEvolutionGraph(String pRoom, String pDataType)
    {
        List<Number> Data = new ArrayList<>();
        Data.add(12);
        Data.add(43);
        LineChart<String, Number> lineChart = GraphGenerator.GenerateLineChart(Data, pRoom, pDataType);
        System.out.println("- Affichage graphique d'évolution -") ;
        System.out.println(pRoom+" : "+pDataType) ;
        lineChart.maxWidthProperty().bind(this.graphContainerVBox.widthProperty()) ;
        this.graphContainerVBox.getChildren().clear() ;
        this.graphContainerVBox.getChildren().add(lineChart);
    }
}