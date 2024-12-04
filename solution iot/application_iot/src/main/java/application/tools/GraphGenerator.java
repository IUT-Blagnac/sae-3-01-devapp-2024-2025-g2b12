package application.tools ;

import java.util.List;
import java.util.Map ;

import javafx.application.Platform ;
import javafx.scene.chart.BarChart ;
import javafx.scene.chart.CategoryAxis ;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.NumberAxis ;
import javafx.scene.chart.XYChart ;
import javafx.scene.shape.Line;

/**
 * Classe utilitaire fournissant des méthodes de génération de graphiques.
 * 
 * Date de dernière modification :
 * - Lundi 2 décembre 2024 -
 * 
 * @author Léo Guinvarc'h
 * @author Mucahit Lekesiz
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class GraphGenerator
{
    /**
     * Génère un diagramme en barres.
     * @param pDataMap  le dictionnaire {objet : valeur} des données à représenter
     * @param pDataType le type de données à représenter
     * @return          le graphique généré
     */
    public static BarChart<String, Number> GenerateBarChart(
        Map<String, String> pDataMap,
        String pDataType
    ) {
        // axe des abscisses
        CategoryAxis xAxis = new CategoryAxis() ;
        xAxis.setLabel("Salle") ;
        xAxis.setAnimated(false) ;

        // axe des ordonnées
        NumberAxis yAxis = new NumberAxis() ;
        yAxis.setLabel(pDataType) ;
        yAxis.setAnimated(false) ;

        // construction du diagramme en barres
        BarChart<String, Number> barChart = new BarChart<>(xAxis, yAxis) ;
        barChart.setTitle(pDataType) ;
        barChart.setLegendVisible(false) ;

        // série des données
        XYChart.Series<String, Number> dataSeries = new XYChart.Series<>() ;

        // ajout des données au diagramme en barres
        for (Map.Entry<String, String> entry : pDataMap.entrySet())
        {
            String room = entry.getKey() ;
            String value = entry.getValue() ;
            try
            {
                // ajout de la barre au diagramme
                int valeur = Integer.parseInt(value) ;
                XYChart.Data<String, Number> data = new XYChart.Data<>(room, valeur) ;
                dataSeries.getData().add(data) ;

                // paramétrage de la couleur de la barre
                Platform.runLater(() -> { data.getNode().setStyle("-fx-bar-fill: #000;") ; }) ;
            }
            catch (NumberFormatException nfe)
            {
                System.err.println(nfe) ;
            }
        }
        barChart.getData().add(dataSeries) ;

        barChart.setStyle("-fx-bar-gap: 1px;") ;

        return barChart ;
    }

    public static LineChart<String, Number> GenerateLineChart(List<Number> Data, String Salle, String TypeData){
        CategoryAxis X = new CategoryAxis();
        X.setLabel("Salle");
        X.setAnimated(false);

        NumberAxis Y = new NumberAxis();
        Y.setLabel(TypeData);
        Y.setAnimated(false);

        LineChart<String, Number> LineChart = new LineChart<>(X, Y);
        LineChart.setTitle(TypeData);

        XYChart.Series<String, Number> dataSeries = new XYChart.Series<>();

        for(Number Num : Data){
            XYChart.Data<String, Number> data = new XYChart.Data<>(null, Num);
            dataSeries.getData().add(data);

            Platform.runLater(() -> { data.getNode().setStyle("-fx-bar-fill: #000;") ; }) ;
        }
        
        LineChart.getData().add(dataSeries) ;

        return LineChart;
    }
}