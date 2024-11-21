package application.view;

import java.io.IOException;

import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.stage.Stage;

public class VisualiserDonneesController {

    public void doAfficher(Stage stage) throws IOException{
        FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("consult.fxml"));
        Scene afficherScene = new Scene(fxmlLoader.load(), 600, 400);
        stage.setScene(afficherScene);
        stage.show();
    }
}
