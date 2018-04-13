<?php
namespace polux\CorePHP\Internal\Database\Generic;

use \polux\CorePHP\Internal\Exceptions\DatabaseException;

/**
 * ComplexGenericDBObject
 *
 * Objet générique complex des objets en BD de l'application
 */
abstract class ComplexGenericDBObject extends BusinessDatabaseObject
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
        'version',
        'revision',
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
     **/
    protected function initCommonFields()
    {
        foreach (static::$commonFields as $fieldname) {
            $this->addNewField($fieldname);
        }
    }

    /**
     * Renvoi les informations sur les autres versions / revisions de l'objet.
     * 
     * @internal SQL
     * 
     * @param \PDO $pObjDBHandler   PDO DB Handler.
     * 
     * @throws DatabaseException
     * @return array(mixed)
     **/    
    public function getHistoryData(\PDO $pObjDBHandler)
    {
        try {
            // SQL Query building!
            $lStrSQLQuery = sprintf(
                "SELECT tid,bid,version,revision 
                FROM %s 
                WHERE bid = '%s'
                ORDER BY version,revision",
                $this->tablename,
                $this->getFieldValue('bid')
            );

            $lObjStmt = $pObjDBHandler->query($lStrSQLQuery);

            if($lObjStmt===false)
            {
                $lArrParams=[
                    $this->tablename,
                    $this->getFieldValue('bid')
                ];
                throw new DatabaseException('DB-LOAD-HISTORY_COBJ',$lArrParams);
            }

            return $lObjStmt->fetchAll(\PDO::FETCH_ASSOC);            
        }
        catch(\Exception $ex)
        {
            $lArrParams=[
               $ex->getMessage()
            ];
            throw new DatabaseException('DB-LOAD-HISTORY_COBJ',$lArrParams);
        }
    }

    // ---------------------------------------------------------------------------------
    // Méthodes ABSTRACT
    // ---------------------------------------------------------------------------------
    abstract function addNewRevision();
    abstract function addNewVersion();

}//end class
