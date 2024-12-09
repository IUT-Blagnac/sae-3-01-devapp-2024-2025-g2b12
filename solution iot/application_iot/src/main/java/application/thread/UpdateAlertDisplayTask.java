package application.thread ;

import java.util.HashMap;
import java.util.Map ;

import application.control.DataVisualisationPane ;
import application.data.DataTypeUtilities ;
import application.styles.FontLoader ;
import application.view.DataVisualisationPaneViewController ;
import javafx.application.Platform ;
import javafx.geometry.Insets ;
import javafx.geometry.Pos ;
import javafx.scene.control.Label ;
import javafx.scene.image.Image ;
import javafx.scene.image.ImageView ;
import javafx.scene.layout.HBox ;
import javafx.scene.layout.VBox ;
import javafx.scene.text.Font ;

/**
 * Thread chargé de la mise à jour de l'affichage des alertes.
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
public class UpdateAlertDisplayTask implements Runnable
{
    // déclaration des constantes
    private static final int MAX_DISPLAYABLE_ALERTS = 10 ;

    // déclaration des attributs
    private DataVisualisationPane dvpDialogController ;
    private VBox alertListVBox ;

    /**
     * Constructeur par défaut : 2 paramètres.
     * @param dvpDialogController   le contrôleur de dialogue de la vue
     * @param alertListVBox         le conteneur d'alertes
     */
    public UpdateAlertDisplayTask(DataVisualisationPane dvpDialogController, VBox alertListVBox)
    {
        // initialisation des paramètres
        this.dvpDialogController    = dvpDialogController ;
        this.alertListVBox          = alertListVBox ;
    }

    @Override
    public void run()
    {
        Map<String, Map<String, String>> alertMap = this.dvpDialogController.getAlertMap() ;

        VBox alertListContainer = new VBox() ;
        alertListContainer.setSpacing(20) ;

        Map<String, VBox> existingAlerts = new HashMap<>() ;

        int displayedAlertNumber = 0 ;
        for (Map.Entry<String, Map<String, String>> m : alertMap.entrySet())
        {
            String alertKey = m.getKey() ;

            VBox alertContainer = existingAlerts.get(alertKey) ;
            if (alertContainer == null)
            {
                // Sinon, on crée une nouvelle alerte
                alertContainer = createAlertContainer(m) ;
                existingAlerts.put(alertKey, alertContainer) ;
            }
            else
            {
                this.updateAlertContainer(alertContainer, m) ;
            }

            alertListContainer.getChildren().add(alertContainer) ;
            displayedAlertNumber++ ;

            if (displayedAlertNumber == MAX_DISPLAYABLE_ALERTS) { break ; }
        }

        Platform.runLater(() -> {
            this.alertListVBox.getChildren().clear() ;
            this.alertListVBox.getChildren().add(alertListContainer) ;
        });
    }

    private VBox createAlertContainer(Map.Entry<String, Map<String, String>> m)
    {
        Font atFont     = FontLoader.getAlertTitleFont() ;
        Font asFont     = FontLoader.getAlertSubtitleFont() ;
        Font sdhFont    = FontLoader.getSingleDataHeaderFont() ;
        Font sdFont     = FontLoader.getSingleDataFont() ;
        Font sduFont    = FontLoader.getSingleDataUnitFont() ;

        ImageView alertIcon = new ImageView(new Image(DataVisualisationPaneViewController.class.getResourceAsStream("/application/image/dvp/alert-icon.png"))) ;
        alertIcon.setFitHeight(36) ;
        alertIcon.setPreserveRatio(true) ;

        Label alertTitle = new Label(DataTypeUtilities.getAlertTitle(m.getValue().get("dataType"))) ;
        alertTitle.setFont(atFont) ;

        Label alertSubtitle = new Label("Salle " + m.getKey()) ;
        alertSubtitle.setFont(asFont) ;

        VBox alertMainInfo = new VBox() ;
        alertMainInfo.getChildren().add(alertTitle) ;
        alertMainInfo.getChildren().add(alertSubtitle) ;

        HBox alertHeader = new HBox() ;
        alertHeader.getChildren().add(alertIcon) ;
        alertHeader.getChildren().add(alertMainInfo) ;
        alertHeader.setAlignment(Pos.CENTER_LEFT) ;
        alertHeader.setSpacing(10) ;

        Label thresholdHeader = new Label("Seuil") ;
        thresholdHeader.setPrefHeight(30) ;
        thresholdHeader.setAlignment(Pos.BOTTOM_RIGHT) ;
        thresholdHeader.setFont(sdhFont) ;

        Label threshold = new Label(m.getValue().get("threshold")) ;
        threshold.setFont(sdFont) ;

        Label thresholdUnit = new Label(DataTypeUtilities.getUnit(m.getValue().get("dataType"))) ;
        thresholdUnit.setFont(sduFont) ;
        thresholdUnit.setPadding(new Insets(0, 0, 2, 0)) ;

        HBox thresholdValue = new HBox() ;
        thresholdValue.getChildren().add(threshold) ;
        thresholdValue.getChildren().add(thresholdUnit) ;
        thresholdValue.setAlignment(Pos.BOTTOM_LEFT) ;
        thresholdValue.setSpacing(5) ;

        VBox thresholdContainer = new VBox() ;
        thresholdContainer.getChildren().add(thresholdHeader) ;
        thresholdContainer.getChildren().add(thresholdValue) ;

        Label measuredHeader = new Label("Relevé") ;
        measuredHeader.setPrefHeight(30) ;
        measuredHeader.setAlignment(Pos.BOTTOM_RIGHT) ;
        measuredHeader.setFont(sdhFont) ;

        Label measured = new Label(m.getValue().get("measuredValue")) ;
        measured.setFont(sdFont) ;

        Label measuredUnit = new Label(DataTypeUtilities.getUnit(m.getValue().get("dataType"))) ;
        measuredUnit.setFont(sduFont) ;
        measuredUnit.setPadding(new Insets(0, 0, 2, 0)) ;

        HBox measuredValue = new HBox() ;
        measuredValue.getChildren().add(measured) ;
        measuredValue.getChildren().add(measuredUnit) ;
        measuredValue.setAlignment(Pos.BOTTOM_LEFT) ;
        measuredValue.setSpacing(5) ;

        VBox measuredContainer = new VBox() ;
        measuredContainer.getChildren().add(measuredHeader) ;
        measuredContainer.getChildren().add(measuredValue) ;

        HBox alertContent = new HBox() ;
        alertContent.getChildren().add(thresholdContainer) ;
        alertContent.getChildren().add(measuredContainer) ;
        alertContent.setSpacing(20) ;

        thresholdContainer.prefWidthProperty().bind(alertContent.widthProperty().multiply(1 / 2.0)) ;
        measuredContainer.prefWidthProperty().bind(alertContent.widthProperty().multiply(1 / 2.0)) ;

        VBox alertContainer = new VBox() ;
        alertContainer.getChildren().add(alertHeader) ;
        alertContainer.getChildren().add(alertContent) ;
        alertContainer.setSpacing(10) ;
        alertContainer.getStyleClass().add("alert-container") ;

        VBox.setMargin(alertHeader, new Insets(20, 20, 0, 20)) ;
        VBox.setMargin(alertContent, new Insets(0, 20, 20, 20)) ;

        return alertContainer ;
    }

    private void updateAlertContainer(
        VBox alertContainer,
        Map.Entry<String, Map<String, String>> m
    ) {
        Label threshold = (Label) ((HBox) ((VBox) alertContainer.getChildren().get(1)).getChildren().get(0)).getChildren().get(1) ;
        threshold.setText(m.getValue().get("threshold")) ;

        Label measured = (Label) ((HBox) ((VBox) alertContainer.getChildren().get(1)).getChildren().get(1)).getChildren().get(1) ;
        measured.setText(m.getValue().get("measuredValue")) ;
    }
}