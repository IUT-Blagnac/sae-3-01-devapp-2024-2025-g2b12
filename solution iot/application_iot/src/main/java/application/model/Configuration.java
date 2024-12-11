package application.model ;

import java.util.List ;
import java.util.Map ;

import application.config.ConfigurationFileWriter;

/**
 * Classe modèle représentant une configuration.
 * 
 * Date de dernière modification :
 * - Mardi 10 décembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class Configuration
{
    // instance unique de la configuration
    private static Configuration singleInstance ;

    // déclaration des attributs
    private String name ;                       // nom de la configuration
    private String topicPrefix ;                // préfixe des topics MQTT auxquels s'abonner
    private List<String> subjectList ;          // liste des sujets à observer
    private List<String> dataTypeList ;         // liste des types de données récupérés
    private Map<String, Double> thresholdMap ;  // dictionnaire des seuils d'alerte par type de données
    private int readingFrequency ;              // fréquence de lecture des données

    /**
     * Donne l'unique instance de la configuration.
     * @return  l'unique instance de la configuration
     */
    public static synchronized Configuration getInstance()
    {
        if (singleInstance == null)
        {
            singleInstance = new Configuration() ;
        }
        return singleInstance ;
    }

    /**
     * Indique si une configuration est définie ou non.
     * @return  true si une configuration est définie, false sinon
     */
    public static synchronized boolean isDefined() { return singleInstance != null ; }

    /**
     * Donne le nom de la configuration.
     * @return  le nom de la configuration
     */
    public String getName() { return this.name ; }

    /**
     * Définit le nom de la configuration.
     * @param _name le nom à donner à la configuration
     */
    public void setName(String _name) { this.name = _name ; }

    /**
     * Donne le préfixe des topics MQTT auxquels s'abonner.
     * @return  le préfixe des topics MQTT auxquels s'abonner
     */
    public String getTopicPrefix() { return this.topicPrefix ; }

    /**
     * Définit le préfix des topics MQTT auxquels s'abonner.
     * @param _topicPrefix le préfix des topics MQTT
     */
    public void setTopicPrefix(String _topicPrefix) { this.topicPrefix = _topicPrefix ; }

    /**
     * Donne la liste des sujets observés.
     * @return  la liste des sujets observés
     */
    public List<String> getSubjectList() { return this.subjectList ; }

    /**
     * Définit la liste des sujets à observer.
     * @param _subjectList la liste des sujets à observer
     */
    public void setSubjectList(List<String> _subjectList) { this.subjectList = _subjectList ; }

    /**
     * Donne la liste des types de données récupérés.
     * @return  la liste des types de données récupérés
     */
    public List<String> getDataTypeList() { return this.dataTypeList ; }

    /**
     * Définit la liste des types de données à récupérer.
     * @param _dataTypeList la liste des types de données à récupérer
     */
    public void setDataTypeList(List<String> _dataTypeList) { this.dataTypeList = _dataTypeList ; }

    /**
     * Donne le dictionnaire des seuils d'alerte par type de données.
     * @return  le dictionnaire des seuils d'alerte par type de données
     */
    public Map<String, Double> getThresholdMap() { return thresholdMap ; }

    /**
     * Définit le dictionnaire des seuils d'alerte par type de données.
     * @param _thresholdMap le dictionnaire des seuils d'alerte par type de données
     */
    public void setThresholdMap(Map<String, Double> _thresholdMap) { this.thresholdMap = _thresholdMap ; }

    /**
     * Donne la fréquence de lecture des données.
     * @return  la fréquence de lecture des données
     */
    public int getReadingFrequency() { return readingFrequency ; }

    /**
     * Définit la fréquence de lecture des données.
     * @param _readingFrequency la fréquence de lecture des données
     */
    public void setReadingFrequency(int _readingFrequency) { this.readingFrequency = _readingFrequency ; }

    /**
     * Enregistre la configuration en tant que fichier.
     */
    public void createFile() { ConfigurationFileWriter.writeConfigurationFile(singleInstance) ; }

    /**
     * Méthode d'affichage (pour tests).
     */
    public String toString()
    {
        return  "Configuration\n"
            +   "-------------\n"
            +   this.name + "\n"
            +   this.topicPrefix + "\n"
            +   this.subjectList + "\n"
            +   this.dataTypeList + "\n"
            +   this.thresholdMap + "\n"
            +   this.readingFrequency ;
    }

    /**
     * Constructeur privé.
     */
    private Configuration() { }
}