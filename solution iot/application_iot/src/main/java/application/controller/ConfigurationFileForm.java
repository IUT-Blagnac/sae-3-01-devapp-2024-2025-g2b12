package application.controller ;

import application.view.ConfigurationFileFormViewController ;

import javafx.fxml.FXMLLoader ;
import javafx.scene.Scene ;
import javafx.stage.Modality ;
import javafx.stage.Stage ;

/**
 * Contrôleur de dialogue du formulaire de paramétrage
 * d'un fichier de configuration.
 * 
 * Date de dernière modification :
 * - Mardi 18 novembre 2024 -
 * 
 * @author Victor Jockin (Équipe G2B12)
 */
public class ConfigurationFileForm
{
    // déclaration des attributs
    private Stage cffStage ;
    private ConfigurationFileFormViewController cffViewController ;

    /**
     * Constructeur : charge le formulaire.
     */
    public ConfigurationFileForm(Stage _stageParent)
    {
        try
        {
            // initialisation d'un nouveau stage pour le formulaire
            this.cffStage = new Stage() ;
            this.cffStage.initOwner(_stageParent) ;
            this.cffStage.initModality(Modality.WINDOW_MODAL) ;

            // chargement de la vue FXML du formulaire
            FXMLLoader fxmlLoader = new FXMLLoader(ConfigurationFileFormViewController.class.getResource("configurationFileForm.fxml")) ;

            // initialisation de la scène
            Scene scene = new Scene(fxmlLoader.load(), 600, 400) ;
            this.cffStage.setScene(scene) ;
            this.cffStage.setTitle("Créer une configuration") ;

            // initialisation du contrôleur
            this.cffViewController = fxmlLoader.getController() ;
            this.cffViewController.setStage(this.cffStage) ;
            this.cffViewController.setCffDialogController(this) ;
        }
        catch (Exception e)
        {
            e.printStackTrace() ;
        }
    }

    public void doConfigurationFileFormDialog()
    {
        this.cffViewController.displayDialog() ;
    }
}