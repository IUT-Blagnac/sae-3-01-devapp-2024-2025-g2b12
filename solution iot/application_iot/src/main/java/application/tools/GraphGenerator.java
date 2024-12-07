package application.tools ;

import java.util.List ;
import java.util.Map ;

import application.data.DataTypeUtilities ;
import application.styles.FontLoader ;
import javafx.scene.chart.BarChart ;
import javafx.scene.chart.CategoryAxis ;
import javafx.scene.chart.LineChart ;
import javafx.scene.chart.NumberAxis ;
import javafx.scene.chart.XYChart ;

/**
 * Classe utilitaire fournissant des méthodes de génération de graphiques.
 * 
 * Date de dernière modification :
 * - Samedi 7 décembre 2024 -
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
        xAxis.setTickLabelFont(FontLoader.getGraphTickFont()) ;
        xAxis.setAnimated(false) ;

        // axe des ordonnées
        NumberAxis yAxis = new NumberAxis() ;
        yAxis.setTickLabelFont(FontLoader.getGraphTickFont()) ;
        yAxis.setAnimated(false) ;

        // construction du diagramme en barres
        BarChart<String, Number> barChart = new BarChart<>(xAxis, yAxis) ;
        barChart.setTitle(null) ;
        barChart.setLegendVisible(false) ;

        // série des données
        XYChart.Series<String, Number> dataSeries = new XYChart.Series<>() ;

        // ajout des données au diagramme
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
            }
            catch (NumberFormatException nfe)
            {
                System.err.println(nfe) ;
            }
        }
        barChart.getData().add(dataSeries) ;

        // paramétrage de l'espacement entre les barres
        barChart.setStyle("-fx-bar-gap: 1px;") ;

        return barChart ;
    }

    /**
     * Génère un diagramme d'évolution (diagramme en courbe).
     * @param pDataList     l'historique des données à représenter
     * @param pRoomName     le nom de la salle dont on souhaite représenter l'historique pour un type de données
     * @param pDataType     le type de données à représenter
     * @param pFrequency    la fréquence à laquelle les données ont été relevées
     * @return              le graphique généré
     */
    public static LineChart<String, Number> GenerateLineChart(
        List<String> pDataList,
        String pRoomName,
        String pDataType,
        double pFrequency
    ) {
        // axe des abscisses
        CategoryAxis xAxis = new CategoryAxis() ;
        xAxis.setTickLabelFont(FontLoader.getGraphTickFont()) ;
        xAxis.setAnimated(false) ;

        // axe des ordonnées
        NumberAxis yAxis = new NumberAxis() ;
        yAxis.setTickLabelFont(FontLoader.getGraphTickFont()) ;
        yAxis.setAnimated(false) ;

        // construction du diagramme d'évolution
        LineChart<String, Number> lineChart = new LineChart<>(xAxis, yAxis) ;
        lineChart.setTitle(null) ;
        lineChart.setLegendVisible(false) ;

        // série des données
        XYChart.Series<String, Number> dataSeries = new XYChart.Series<>() ;

        // ajout des données au diagramme
        double time = 0.0 ;
        for (int i = 0 ; i < pDataList.size() ; i++)
        {
            // ajout du point au diagramme
            XYChart.Data<String, Number> data = new XYChart.Data<>(
                String.valueOf(time),
                Double.parseDouble(pDataList.get(i))
            ) ;
            dataSeries.getData().add(data) ;

            // calcul de l'instant {t} suivant
            time += pFrequency ;
        }
        
        lineChart.getData().add(dataSeries) ;

        // paramétrage des styles de la courbe
        dataSeries.getNode().setStyle("-fx-stroke: #444bf8; -fx-stroke-width: 2px;") ;

        return lineChart ;
    }
}