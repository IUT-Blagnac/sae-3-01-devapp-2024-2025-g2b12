package application.view ;

import javafx.fxml.FXML;
import javafx.scene.control.Label;

public class HelloController {
    @FXML
    private Label CreerFichier;
    @FXML
    private Label ChargerFichier;
    @FXML
    private Label AfficherFichier;
    @FXML
    private Label TestMQTT;


    @FXML
    protected void onCreerButtonClick() {
        CreerFichier.setText("VOUS ETES OU");
    }

    @FXML
    protected void onChargerButtonClick() {
        ChargerFichier.setText("TOUS");
    }

    @FXML
    protected void onAfficherButtonClick() {
        AfficherFichier.setText("LA VIE DMA MERE ON VA CHEZ QUICK WSH !!!!!");
    }

    @FXML
    protected void onTestMQTTButtonClick() {
        TestMQTT.setText("Clique moi, je t\'empoisonne");
    }
}