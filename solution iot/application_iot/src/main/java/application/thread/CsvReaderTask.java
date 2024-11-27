package application.thread;

import application.controller.DataVisualisationPane;

import com.opencsv.CSVReader;
import com.opencsv.CSVReaderBuilder;
import com.opencsv.exceptions.CsvValidationException;
import com.opencsv.CSVParserBuilder;

import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

public class CsvReaderTask implements Runnable {
    private final DataVisualisationPane dvpDialogController;
    private final char delimiter;

    public CsvReaderTask(DataVisualisationPane controller, char delimiter) {
        this.dvpDialogController = controller;
        this.delimiter = delimiter;
    }

    @Override
    public void run() {
        Map<String, Map<String, String>> mapData = new HashMap<>();
        try (CSVReader csvReader = new CSVReaderBuilder(new FileReader("src/main/resources/application/data/data.csv"))
                .withCSVParser(new CSVParserBuilder().withSeparator(delimiter).build())
                .build()) {
            String[] values = null;
            String[] header = csvReader.readNext();
            while ((values = csvReader.readNext()) != null && !values[0].equals("")) {
                mapData.put(values[0], new HashMap<String, String>());
                for (int i = 1; i < values.length; i++) {
                    mapData.get(values[0]).put(header[i], values[i]);
                }
            }
            this.dvpDialogController.setMapData(mapData);
        } catch (FileNotFoundException e) {
            throw new RuntimeException(e);
        } catch (IOException e) {
            throw new RuntimeException(e);
        } catch (CsvValidationException e) {
            throw new RuntimeException(e);
        } 
    }
}