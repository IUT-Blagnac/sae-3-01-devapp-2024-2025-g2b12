package application.thread;

import application.view.DataVisualisationPaneViewController;

import com.opencsv.CSVReader;
import com.opencsv.CSVReaderBuilder;
import com.opencsv.CSVParserBuilder;
import com.opencsv.exceptions.CsvValidationException;

import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.Collections;
import java.util.HashMap;
import java.util.Map;

public class CsvReaderTask implements Runnable {
    private final DataVisualisationPaneViewController controller;
    private final char delimiter;

    public CsvReaderTask(DataVisualisationPaneViewController controller, char delimiter) {
        this.controller = controller;
        this.delimiter = delimiter;
    }

    @Override
    public void run() {
        System.out.println("running thread") ;
        Map<String, Map<String, String>> salles = new HashMap<>();
        try (CSVReader csvReader = new CSVReaderBuilder(new FileReader("src/main/resources/application/data/data.csv"))
                .withCSVParser(new CSVParserBuilder().withSeparator(delimiter).build())
                .build()) {
            String[] values = null;
            String[] header = csvReader.readNext();
            while ((values = csvReader.readNext()) != null && !values[0].equals("")) {
                salles.put(values[0], new HashMap<String, String>());
                for (int i = 1; i < values.length; i++) {
                    salles.get(values[0]).put(header[i], values[i]);
                }
            }
            System.out.println(salles);


        } catch (Exception e) {System.out.println(e) ;}/* catch (FileNotFoundException e) {
            throw new RuntimeException(e);
        } catch (IOException e) {
            throw new RuntimeException(e);
        } catch (CsvValidationException e) {
            throw new RuntimeException(e);
        } */
    }
}