package application.view;

import application.thread.CsvReaderTask;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.control.Label;
import javafx.scene.control.ScrollPane;
import javafx.scene.layout.FlowPane;
import javafx.stage.Stage;

import java.io.IOException;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

public class VisualiserDonneesController {

    @FXML
    private Label infoCsv;
    @FXML
    private ScrollPane scroll;
    @FXML
    private FlowPane flow;

    private ScheduledExecutorService scheduler;

    public void ajoutLabel(List<String> contents) {
        flow.getChildren().clear();
        for (String content : contents) {
            Label label = new Label(content);
            flow.getChildren().add(label);
        }
        scroll.setContent(flow);
    }

    public void doAfficher(Stage stage) throws IOException {
        FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("consult.fxml"));
        Scene afficherScene = new Scene(fxmlLoader.load(), 1200, 720);
        stage.setScene(afficherScene);
        stage.show();

        VisualiserDonneesController controller = fxmlLoader.getController();
        controller.startCsvReaderThread();
    }

    private void startCsvReaderThread() {
        scheduler = Executors.newScheduledThreadPool(1);
        scheduler.scheduleAtFixedRate(new CsvReaderTask(this, ';'), 0, 5, TimeUnit.SECONDS);
    }

    public void updateInfoCsv(String content) {
        infoCsv.setText(content);
    }

    public void stopCsvReaderThread() {
        if (scheduler != null && !scheduler.isShutdown()) {
            scheduler.shutdown();
        }
    }
}