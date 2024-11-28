package application.model ;

import java.util.Map ;

/**
 * Classe modèle représentant une ligne de données.
 * Exemple : Données d'une salle / d'un panneau solaire.
 * 
 * Date de dernière modification :
 * - Jeudi 20 novembre 2024 -
 * 
 * @author Victor Jockin
 * - Équipe 2B12 -
 */
public class DataRow
{
    // déclaration des attributs
    private String name ;
    private Map<String, String> dataMap ;

    /**
     * Constructeur par défaut : 1 paramètre
     */
    public DataRow(String _name, Map<String, String> _dataMap)
    {
        // initialisation des attributs
        this.name = _name ;
        this.dataMap = _dataMap ;
    }

    /**
     * Accesseur : donne le nom de la ligne de données.
     * @return  le nom de la ligne de données
     */
    public String getName()
    {
        return this.name ;
    }

    /**
     * Accesseur : donne les données de la ligne.
     * @return  les données de la ligne
     */
    public Map<String, String> getData()
    {
        return this.dataMap ;
    }
}