package application.view ;

import application.control.DataVisualisationPane ;
import application.data.DataUtilities ;
import application.model.DataRow ;
import application.styles.FontLoader ;
import application.tools.GraphGenerator ;

import java.util.HashMap ;
import java.util.List ;
import java.util.Map ;

import javafx.beans.property.SimpleStringProperty ;
import javafx.collections.FXCollections ;
import javafx.collections.ObservableList ;
import javafx.fxml.FXML ;
import javafx.scene.chart.BarChart ;
import javafx.scene.control.Button ;
import javafx.scene.control.TableCell ;
import javafx.scene.control.TableColumn ;
import javafx.scene.control.TableView ;
import javafx.scene.layout.Priority ;
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
    @FXML private TableView<DataRow> dataTableView ;
    @FXML private VBox graphContainerVBox ;

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
            button.setOnAction(
                event -> {
                    if (this.selectedHeaderButton != null) { this.selectedHeaderButton.getStyleClass().remove("selected") ; }
                    button.getStyleClass().add("selected") ;
                    this.selectedHeaderButton = button ;
                    this.displayGraphByDataType(header) ;
                }
            ) ;

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
            // rafraichissement des styles lorsqu'une ligne est sélectionnée
            (observable, oldValue, newValue) -> 
            {
                //this.dataTableView.refresh() ;
                System.out.println(newValue.getName()) ;
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
    }

    /**
     * Affiche un graphique à partir d'un type de données.
     * @param pDataType un type de données
     */
    public void displayGraphByDataType(String pDataType)
    {
        Map<String, String> dataMap = new HashMap<>() ;
        for (DataRow dataRow : this.dataTableViewOList)
        {
            dataMap.put(dataRow.getName(), dataRow.getData().get(pDataType)) ;
        }
        BarChart<String, Number> barChart = GraphGenerator.GenerateBarChart(dataMap, pDataType) ;
        VBox.setVgrow(barChart, Priority.ALWAYS) ;
        barChart.setMaxWidth(this.graphContainerVBox.getWidth() - 40) ;
        this.graphContainerVBox.getChildren().clear() ;
        this.graphContainerVBox.getChildren().add(barChart) ;
    }
}