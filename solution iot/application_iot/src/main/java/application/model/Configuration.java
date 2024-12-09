package application.model ;

import java.util.List ;
import java.util.Map ;

/**
 * Classe modèle représentant une configuration.
 * 
 * Date de dernière modification :
 * - Lundi 9 décembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class Configuration
{
    // déclaration des attributs
    private String name ;                       // nom de la configuration
    private String topicPrefix ;                // préfixe des topics MQTT auxquels s'abonner
    private List<String> subjectList ;          // liste des sujets à observer
    private List<String> dataTypeList ;         // liste des types de données récupérés
    private Map<String, String> thresholdMap ;  // dictionnaire des seuils d'alerte par type de données
    private double readingFrequency ;           // fréquence de lecture des données

    /**
     * Constructeur paramétré : 6 paramètres.
     * @param _name             le nom de la configuration
     * @param _topicPrefix      le préfixe des topics MQTT auxquels s'abonner
     * @param _subjectList      la liste des sujets à observer
     * @param _dataTypeList     la liste des types de données récupérés
     * @param _thresholdMap     le dictionnaire des seuils d'alerte par type de données
     * @param _readingFrequency la fréquence de lecture des données
     */
    public Configuration(
        String _name,
        String _topicPrefix,
        List<String> _subjectList,
        List<String> _dataTypeList,
        Map<String, String> _thresholdMap,
        double _readingFrequency
    ) {
        // initialisation des attributs
        this.name               = _name ;
        this.topicPrefix        = _topicPrefix ;
        this.subjectList        = _subjectList ;
        this.dataTypeList       = _dataTypeList ;
        this.thresholdMap       = _thresholdMap ;
        this.readingFrequency   = _readingFrequency ;
    }

    /**
     * Donne le nom de la configuration.
     * @return  le nom de la configuration
     */
    public String getName()
    {
        return this.name ;
    }

    /**
     * Donne le préfixe des topics MQTT auxquels s'abonner.
     * @return  le préfixe des topics MQTT auxquels s'abonner
     */
    public String getTopicPrefix()
    {
        return this.topicPrefix ;
    }

    /**
     * Donne la liste des sujets observés.
     * @return  la liste des sujets observés
     */
    public List<String> getSubjectList()
    {
        return this.subjectList ;
    }

    /**
     * Donne la liste des types de données récupérés.
     * @return  la liste des types de données récupérés
     */
    public List<String> getDataTypeList()
    {
        return this.dataTypeList ;
    }

    /**
     * Donne le dictionnaire des seuils d'alerte par type de données.
     * @return  le dictionnaire des seuils d'alerte par type de données
     */
    public Map<String, String> getThresholdMap()
    {
        return thresholdMap ;
    }

    /**
     * Donne la fréquence de lecture des données.
     * @return  la fréquence de lecture des données
     */
    public double getReadingFrequency()
    {
        return readingFrequency ;
    }
}