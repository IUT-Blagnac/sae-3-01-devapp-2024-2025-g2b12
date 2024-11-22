package application.view;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.control.Label;
import javafx.stage.Stage;

import java.io.IOException;
import application.thread.CsvReaderTask;

public class VisualiserDonneesController {

    @FXML
    private Label infoCsv;

    public void doAfficher(Stage stage) throws IOException {
        FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("consult.fxml"));
        Scene afficherScene = new Scene(fxmlLoader.load(), 600, 400);
        stage.setScene(afficherScene);
        stage.show();

        VisualiserDonneesController controller = fxmlLoader.getController();
        controller.startCsvReaderThread();
    }

    private void startCsvReaderThread() {
        Thread csvReaderThread = new Thread(new CsvReaderTask(this));
        csvReaderThread.setDaemon(true);
        csvReaderThread.start();
    }

    public void updateInfoCsv(String content) {
        infoCsv.setText(content);
    }
}