package application.thread;

import application.view.VisualiserDonneesController;
import com.opencsv.CSVReader;
import com.opencsv.exceptions.CsvException;
import javafx.application.Platform;

import java.io.FileReader;
import java.io.IOException;
import java.util.List;

public class CsvReaderTask implements Runnable {
    private final VisualiserDonneesController controller;

    public CsvReaderTask(VisualiserDonneesController controller) {
        this.controller = controller;
    }

    @Override
    public void run() {
        while (true) {
            StringBuilder sb = new StringBuilder();
            try (CSVReader reader = new CSVReader(new FileReader("src/main/resources/application/donnee/Info.csv"))) {
                List<String[]> allRows = reader.readAll();
                for (String[] row : allRows) {
                    sb.append(String.join(", ", row)).append("\n");
                }
            } catch (IOException | CsvException e) {
                e.printStackTrace();
            }

            final String csvContent = sb.toString();
            Platform.runLater(() -> controller.updateInfoCsv(csvContent));

            try {
                Thread.sleep(5000); // Attendre 5 secondes avant de relire le fichier
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
    }
}