<?php
namespace polux\CorePHP\Internal\Database\Generic;

/**
 * SimpleGenericDObject
 *
 * Objet générique des objets en BD de l'application
 */
class SimpleGenericDBObject extends BusinessDatabaseObject
{
    /**
     * Tableau des attributs communs
     *
     * Définition statique des attributs commun à toutes tables
     * d'objet de l'application.
     *
     * @var array(string)
     * @staticvar
     * @access protected
     */
    protected static $commonFields = [
        'tid',
        'bid',
        'stitle',
        'ltitle',
        'comment',
        'ctime',
        'cuser',
        'utime',
        'uuser',
        'isActive'        
    ];

    /**
     * Tableau des clés communes
     *
     * Définition statique des clés communes à toutes tables
     * d'objet de l'application.
     *
     * @var array(string)
     * @staticvar
     * @access protected
     */
    protected static $commonKeys = [
        'tid'
     ];

    /**
     * Constructeur par défaut
     *
     * Initialise l'objet courant et lance le chargement de l'objet.
     *
     * @param string        $tablename          Nom de la table des objets de la classe.
     * @param array(string) $aLoadKeysValues    Tableau clé - valeur de l'objet à charger. (Optionel)
     *
     * @return self
     **/
    public function __construct($tablename, $aLoadKeysValues=null)
    {
        $this->setTablename($tablename);
        $this->initCommonFields();

        // Chargement de l'objet !
        if (!is_null($aLoadKeysValues)) {
            $this->loadObject($aLoadKeysValues);
        }
    }

    /**
     * initCommonFields
     *
     * Initialisation des attributs commun aux objets de l'application
     *
     * @param string $var Type
     * @return string
     **/
    protected function initCommonFields()
    {
        foreach (static::$commonFields as $fieldname) {
            $this->addNewField($fieldname);
        }
    }

    // /**
    //  * Chargement des attributs de l'objet depuis la base de données
    //  *
    //  * @param array $pArrKeyValues  Tableaux des identifiants de l'objet à charger.
    //  * @return boolean TRUE si OK, FALSE si non trouvé
    //  * @throws DBExceptioon => Résultat multiple + Gestion des erreurs SQL
    //  **/
    // public function loadObjectFromArray(array $pArrAttributesValues):boolean
    // {
    //     try{
    //         $lStrSQLQuery = "";
    //         if(!is_null($pArrKeyValues))
    //         {
    //             $lStrSQLQuery = 
    //         }
    //     }
    //     catch(\Exception $e)
    //     {

    //     }
    // }
}//end class
