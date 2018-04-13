<?php

namespace polux\CorePHP\Model;

use polux\CorePHP\Database\Generic\SpecificDBObject;
use polux\CorePHP\Exceptions\DatabaseException;

use polux\CorePHP\Managers\DatabaseManager;

/**
 * Classe AdditionalAttributeDefinition - Attribut additionnel d'objet
 * 
 * Réprésente un Attribut additionnel d'objet du modèle métier
 * 
 * @author @polux_fr
 */
class AdditionalAttributeDefinition extends SpecificDBObject
{   
    /**
     * Chargement des informations d'un type d'objet depuis son TID
     * 
     * @param string $pStrTID   TID à charger. Si null, pas de chargement.
     */
    public function __construct($pStrTID=null)
    {
        $lArrKeyValue = null;
        if(!is_null($pStrTID)){
            $lArrKeyValue = ["tid" => $pStrTID];
        }
        parent::__construct('CORE_ATTRDEFS',$lArrKeyValue);
    }//end __construct()
    
    /**
     * Définie les champs caractérisant la classe
     */
    protected function initFields()
    {
        // SQL Fields : tid, tlnk_tid, tobj_tid, bid, stitle, ltitle, attr_type, attr_pattern, attr_default_value, comment, cuser, ctime, uuser, utime, isActive, isSystem`
        $this->addNewField('tid');
        $this->addNewField('tlnk_tid');
        $this->addNewField('tobj_tid');
        $this->addNewField('bid');
        $this->addNewField('stitle');
        $this->addNewField('ltitle');
        $this->addNewField('comment');
        $this->addNewField('attr_type');
        $this->addNewField('attr_pattern');
        $this->addNewField('attr_default_value');
        $this->addNewField('cuser');
        $this->addNewField('ctime');
        $this->addNewField('uuser');
        $this->addNewField('utime');
        $this->addNewField('isActive');
        $this->addNewField('isSystem');

        $this->setKeys(['tid']);
    }//end initFields()

    /**
     * Retourne les informations sur les attributs complémentaires du type d'objet.
     * 
     * @param string    $pStrTIDObjectType      TID du type d'objet.
     * @param \PDO      $pObjPDODBHandler       (Optionel) PDO database Handler.
     * 
     * @return array    array() tid, tlnk_tid, tobj_tid, bid, stitle, ltitle, attr_type, attr_pattern, attr_default_value, comment, cuser, ctime, uuser, utime, isActive 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getAttributesDefinitionByObjectType($pStrTIDObjectType,\PDO $pObjPDODBHandler=null):array
    {
        // SQL Fields tid, tlnk_tid, tobj_tid, bid, stitle, ltitle, attr_type, attr_pattern, attr_default_value, comment, cuser, ctime, uuser, utime, isActive 
        try {
            // SQL Query Building!            
            $lStrSQLQuery = sprintf(
                "SELECT 
                    tid,
                    tlnk_tid,
                    tobj_tid,
                    bid,
                    stitle,
                    ltitle,
                    attr_type,
                    attr_pattern,
                    attr_default_value,
                    comment,
                    cuser,
                    ctime,
                    uuser,
                    utime,
                    isActive 
                FROM CORE_ATTRDEFS 
                WHERE tobj_tid = '%s' AND isSystem = 0 ",
                $pStrTIDObjectType
            );

            return DatabaseManager::queryDB($lStrSQLQuery, $pObjPDODBHandler);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }//end getAttributesDefinitionByObjectType()

    /**
     * Retourne les informations sur les attributs complémentaires du type de liens.
     * 
     * @param string    $pStrTIDLnkType         TID du type de lien.
     * @param \PDO      $pObjPDODBHandler       (Optionel) PDO database Handler.
     * 
     * @return array    array() tid, tlnk_tid, tobj_tid, bid, stitle, ltitle, attr_type, attr_pattern, attr_default_value, comment, cuser, ctime, uuser, utime, isActive 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getAttributesDefinitionByLinkType($pStrTIDLnkType,\PDO $pObjPDODBHandler=null):array
    {
        // SQL Fields tid, tlnk_tid, tobj_tid, bid, stitle, ltitle, attr_type, attr_pattern, attr_default_value, comment, cuser, ctime, uuser, utime, isActive 
        try {
            // SQL Query Building!            
            $lStrSQLQuery = sprintf(
                "SELECT 
                    tid,
                    tlnk_tid,
                    tobj_tid,
                    bid,
                    stitle,
                    ltitle,
                    attr_type,
                    attr_pattern,
                    attr_default_value,
                    comment,
                    cuser,
                    ctime,
                    uuser,
                    utime,
                    isActive 
                FROM CORE_ATTRDEFS 
                WHERE tlnk_tid = '%s' AND isSystem = 0 ",
                $pStrTIDLnkType
            );

            return DatabaseManager::queryDB($lStrSQLQuery, $pObjPDODBHandler);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }//end getAttributesDefinitionByLinkType()

}//end class