package application.view ;

import application.control.ConfigurationFileForm;
import javafx.stage.Stage ;

/**
 * Contrôleur de vue du formulaire de paramétrage
 * d'un fichier de configuration.
 * 
 * Date de dernière modification :
 * - Mardi 18 novembre 2024 -
 * 
 * @author Victor Jockin (Équipe G2B12)
 */
public class ConfigurationFileFormViewController
{
    // déclaration des attributs
    private Stage stage ;
    private ConfigurationFileForm cffDialogController ;

    public void setStage(Stage _stage)
    {
        this.stage = _stage ;
    }

    public void setCffDialogController(ConfigurationFileForm _cffDialogController)
    {
        this.cffDialogController = _cffDialogController ;
    }

    /**
     * Affiche la fenêtre.
     */
    public void displayDialog()
    {
        this.stage.showAndWait() ;
    }
}