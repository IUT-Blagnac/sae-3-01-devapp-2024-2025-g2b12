package application.thread ;

import application.control.DataVisualisationPane ;
import application.model.DataRow ;

import java.util.HashMap ;
import java.util.Map ;

import javafx.application.Platform ;
import javafx.collections.ObservableList ;
import javafx.scene.control.TableView ;

/**
 * Thread chargé de la mise à jour de l'affichage des données.
 * 
 * Date de dernière modification :
 * - Dimanche 8 décembre 2024 -
 * 
 * @author Nolhan Biblocque
 * @author Léo Guinvarc'h
 * @author Victor Jockin
 * @author Mathys Laguilliez
 * @author Mucahit Lekesiz
 * - Équipe 2B12 -
 */
public class UpdateDataDisplayTask implements Runnable
{
    // déclaration des attributs
    private DataVisualisationPane dvpDialogController ;
    private ObservableList<DataRow> dataTableViewOList ;
    private TableView<DataRow> dataTableView ;

    /**
     * Constructeur par défaut : 3 paramètres.
     * @param dvpDialogController   le contrôleur de dialogue de la vue
     * @param dataTableViewOList    l'ObservableList des salles affichées dans la vue
     * @param dataTableView         la TableView contenant l'ObservableList
     */
    public UpdateDataDisplayTask(
        DataVisualisationPane dvpDialogController,
        ObservableList<DataRow> dataTableViewOList,
        TableView<DataRow> dataTableView
    ) {
        // initialisation des attributs
        this.dvpDialogController = dvpDialogController ;
        this.dataTableViewOList = dataTableViewOList ;
        this.dataTableView = dataTableView ;
    }

    @Override
    public void run()
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

        Platform.runLater(() -> {
            dataTableView.setItems(dataTableViewOList) ;
            dataTableView.refresh() ;
        });
    }
}