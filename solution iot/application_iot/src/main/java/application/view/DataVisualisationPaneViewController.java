package application.view ;

import application.controller.DataVisualisationPane ;
import application.model.DataRow ;

import java.util.List ;
import java.util.Map ;

import javafx.beans.property.SimpleStringProperty ;
import javafx.collections.FXCollections ;
import javafx.collections.ObservableList ;
import javafx.fxml.FXML ;
import javafx.scene.control.TableColumn ;
import javafx.scene.control.TableView ;
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

    // récupération des éléments graphiques de la vue FXML
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
        System.out.println("- initialisation -") ;

        // initialisation de la TableView
        List<String> headersList = this.dvpDialogController.getDataTypeList() ;
        for (int i = 0 ; i < headersList.size() ; i++)
        {
            String header = headersList.get(i) ;
            TableColumn<DataRow, String> tableColumn = new TableColumn<>(header) ;
            if (i == 0)
            {
                tableColumn.setCellValueFactory(
                    data -> new SimpleStringProperty(data.getValue().getName())
                ) ;    
            }
            else
            {
                tableColumn.setCellValueFactory(
                    data -> new SimpleStringProperty(data.getValue().getData().get(header))
                ) ;
            }
            this.dataTableView.getColumns().add(tableColumn) ;
        }
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
    public void update()
    {
        Map<String, Map<String, String>> dataMap = this.dvpDialogController.getDataMap() ;
        this.dataTableViewOList = FXCollections.observableArrayList() ;
        for (Map.Entry<String, Map<String, String>> m : dataMap.entrySet())
        {
            DataRow dataRow = new DataRow(m.getKey(), m.getValue()) ;
            this.dataTableViewOList.add(dataRow) ;
        }
        this.dataTableView.setItems(this.dataTableViewOList) ;
    }
}