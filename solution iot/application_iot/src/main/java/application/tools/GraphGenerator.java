package application.tools ;

import java.util.Map ;
import javafx.scene.chart.BarChart ;
import javafx.scene.chart.CategoryAxis ;
import javafx.scene.chart.NumberAxis ;
import javafx.scene.chart.XYChart ;

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
    
        // axe des ordonnées
        NumberAxis yAxis = new NumberAxis() ;
        yAxis.setLabel(pDataType) ;

        // construction du diagramme en barres
        BarChart<String, Number> barChart = new BarChart<>(xAxis, yAxis) ;
        barChart.setTitle(pDataType) ;

        // série des données
        XYChart.Series<String, Number> dataSeries = new XYChart.Series<>() ;

        // ajout des données au diagramme en barres
        for (Map.Entry<String, String> entry : pDataMap.entrySet())
        {
            String room = entry.getKey() ;
            String value = entry.getValue() ;
            try
            {
                int valeur = Integer.parseInt(value) ;
                dataSeries.getData().add(new XYChart.Data<>(room, valeur)) ;
            }
            catch (NumberFormatException nfe)
            {
                System.err.println(nfe) ;
            }
        }
        barChart.getData().add(dataSeries) ;

        return barChart ;
    }
}