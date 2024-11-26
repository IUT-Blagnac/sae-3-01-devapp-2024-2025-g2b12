package application.thread;

import application.view.VisualiserDonneesController;
import com.opencsv.CSVReader;
import com.opencsv.CSVReaderBuilder;
import com.opencsv.CSVParserBuilder;
import com.opencsv.exceptions.CsvValidationException;
import javafx.application.Platform;

import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

public class CsvReaderTask implements Runnable {
    private final VisualiserDonneesController controller;
    private final char delimiter;

    public CsvReaderTask(VisualiserDonneesController controller, char delimiter) {
        this.controller = controller;
        this.delimiter = delimiter;
    }

    @Override
    public void run() {
        Map<String, Map<String, String>> Salle = new HashMap<>();
        try (CSVReader csvReader = new CSVReaderBuilder(new FileReader("src/main/resources/application/donnee/Info.csv"))
                .withCSVParser(new CSVParserBuilder().withSeparator(delimiter).build())
                .build()) {
            String[] values = null;
            String[] header = csvReader.readNext();
            while ((values = csvReader.readNext()) != null && !values[0].equals("")) {
                Salle.put(values[0], new HashMap<String, String>());
                for (int i = 1; i < values.length; i++) {
                    Salle.get(values[0]).put(header[i], values[i]);
                }
            }
            System.out.println(Salle);

        } catch (FileNotFoundException e) {
            throw new RuntimeException(e);
        } catch (IOException e) {
            throw new RuntimeException(e);
        } catch (CsvValidationException e) {
            throw new RuntimeException(e);
        }
    }
}