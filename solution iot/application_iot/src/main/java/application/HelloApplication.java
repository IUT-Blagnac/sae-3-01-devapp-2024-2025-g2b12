package application ;

import application.view.VisualiserDonneesController;
import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.stage.Stage;

import java.io.IOException;

public class HelloApplication extends Application {
    @Override
    public void start(Stage stage) throws IOException {
        FXMLLoader fxmlLoader = new FXMLLoader(HelloApplication.class.getResource("view/hello-view.fxml"));
        Scene scene = new Scene(fxmlLoader.load(), 600, 400);
        scene.getStylesheets().add(getClass().getResource("style/style.css").toExternalForm());
        stage.setTitle("SAE");
        stage.setScene(scene);
        stage.show();
    }

    @Override
    public void stop() throws Exception {
        VisualiserDonneesController controller = new VisualiserDonneesController();
        controller.stopCsvReaderThread();
        super.stop();
    }

    public static void main(String[] args) {
        launch(args);
    }
}