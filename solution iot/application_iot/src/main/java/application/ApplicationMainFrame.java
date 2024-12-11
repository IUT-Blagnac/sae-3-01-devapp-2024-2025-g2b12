package application ;

import application.control.ConfigurationFileForm;
import application.control.DataVisualisationPane;
import application.view.ApplicationMainFrameViewController ;

import javafx.application.Application ;
import javafx.fxml.FXMLLoader ;
import javafx.scene.Scene ;
import javafx.stage.Stage ;

/**
 * Contrôleur de dialogue du menu principal.
 * 
 * Date de dernière modification :
 * - Mardi 18 novembre 2024 -
 * 
 * @author Victor Jockin (Équipe G2B12)
 */
public class ApplicationMainFrame extends Application
{
    // déclaration des attributs
    private Stage amfStage ;

    /**
	 * Initialise et affiche la fenêtre principale de l'application.
	 * @param primaryStage  Le stage principal de l'application
	 */
    @Override
    public void start(Stage primaryStage)
    {
        // initialisation des attributs
        this.amfStage = primaryStage ;

        // chargement de la fenêtre principale
        try
        {
            // chargement de la vue FXML du menu principal
            FXMLLoader fxmlLoader = new FXMLLoader(ApplicationMainFrame.class.getResource("view/applicationMainFrame.fxml")) ;

            // initialisation de la scène
            Scene scene = new Scene(fxmlLoader.load(), 600, 600) ;
            scene.getStylesheets().add(getClass().getResource("style/style.css").toExternalForm()) ;
            this.amfStage.setScene(scene) ;
            this.amfStage.setTitle("S.A.E.") ;

            // initialisation du contrôleur
            ApplicationMainFrameViewController amfViewController = fxmlLoader.getController() ;
            amfViewController.setStage(this.amfStage) ;
            amfViewController.setAmfDialogController(this) ;
            amfViewController.initializeView() ;

            // Afficher la fenêtre
            amfViewController.displayDialog() ;
        }
        catch (Exception e)
        {
            e.printStackTrace() ;
        }
    }

    /**
     * Lance l'application.
     */
	public static void runApp()
    {
		Application.launch() ;
	}

    /**
     * Ouvre le formulaire de paramétrage d'un fichier de configuration.
     */
    public void parametrerConfiguration()
    {
        ConfigurationFileForm configurationFileForm = new ConfigurationFileForm(this.amfStage) ;
        configurationFileForm.doConfigurationFileFormDialog() ;
    }

    /**
     * Ouvre la fenêtre de visualisation des données.
     */
    public void visualiserDonnees()
    {
        DataVisualisationPane dataVisualisationPane = new DataVisualisationPane(this.amfStage) ;
        dataVisualisationPane.doDataVisualisationPaneDialog() ;
    }
}