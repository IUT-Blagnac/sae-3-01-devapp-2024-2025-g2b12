package application.view ;

import application.controller.DataVisualisationPane ;
import application.model.DataRow ;

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
    public void initialize()
    {
        System.out.println("- initialisation -") ;
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
        // composants JavaFX utilisés
        TableColumn<DataRow, String> column ;

        // nettoyage de la TableView
        this.dataTableView.getItems().clear() ;
        this.dataTableView.getColumns().clear() ;

        Map<String, Map<String, String>> dataMap = this.dvpDialogController.getDataMap() ;
        
        this.dataTableViewOList = FXCollections.observableArrayList() ;

        // remplissage de la TableView
        int i = 0 ;
        for (Map.Entry<String, Map<String, String>> m : dataMap.entrySet())
        {
            if (i == 0)
            {
                column = new TableColumn<>("Salle") ;
                column.setCellValueFactory(
                    data -> new SimpleStringProperty(data.getValue().getName())
                ) ;
                this.dataTableView.getColumns().add(column) ;

                for (String header : m.getValue().keySet())
                {
                    column = new TableColumn<>(header) ;
                    column.setCellValueFactory(
                        data -> new SimpleStringProperty(data.getValue().getData().getOrDefault(header, "N/A"))
                    ) ;
                    this.dataTableView.getColumns().add(column) ;
                }
            }
            else
            {
                DataRow dataRow = new DataRow(m.getKey(), m.getValue()) ;
                this.dataTableViewOList.add(dataRow) ;
            }
            i++ ;
        }
        System.out.println(this.dataTableViewOList) ;
        this.dataTableView.setItems(this.dataTableViewOList) ;
    }
}