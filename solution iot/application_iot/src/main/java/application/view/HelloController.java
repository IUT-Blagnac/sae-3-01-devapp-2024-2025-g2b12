package application.view ;

import java.io.IOException;

import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.stage.Stage;

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
    protected void onAfficherButtonClick() throws IOException {
        Stage stage = (Stage) AfficherFichier.getScene().getWindow();
        VisualiserDonneesController VDC = new VisualiserDonneesController();
        VDC.doAfficher(stage);
    }

    @FXML
    protected void onTestMQTTButtonClick() {
        TestMQTT.setText("Clique moi, je t\'empoisonne");
    }
}