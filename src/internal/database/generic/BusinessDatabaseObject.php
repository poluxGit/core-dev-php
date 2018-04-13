<?php
namespace polux\CorePHP\Internal\Database\Generic;

use polux\CorePHP\Internal\Exceptions\DatabaseException;

use polux\CorePHP\Managers\DatabaseManager;
use polux\CorePHP\Internal\Model\AdditionalAttributeDefinition;
use polux\CorePHP\Managers\CoreModelManager;

/**
 * BusinessDBObject
 * 
 * Méthodes relatives aux comportements interconnectés des objets.
 * 
 */
class BusinessDatabaseObject extends DatabaseObject 
{
    /**
     * TID du type de l'objet
     * 
     * @var string
     */
    protected $objectTypeTID = null;

    /**
     * Constructeur par défaut 
     *
     * @param string $tablename     Nom de la table
     **/
    public function __construct($tablename)
    {
       parent::_construct($tablename);
    }
   
    /** 
     * Retourne les informations sur les liens dont le type d'objet est père 
     *  
     * @internal CORE_TYPELINKS    
     * 
     * @param \PDO      $pObjDBHandler      (Optionel) Database Handler.
     * @return array array() => tid, bid, stitle, ltitle, typobj_src, typobj_dst, comment, cuser, ctime, uuser, utime, isActive
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getLinkTypesFromObject(\PDO $pObjDBHandler=null):array
    {
        return self::getLinkTypesFromObjectType($this->getTIDObjectType($pObjDBHandler),$pObjDBHandler);
    }// end getLinkTypesFromObject()

    /** 
     * Retourne les informations sur les liens dont le type d'objet est père 
     *  
     * @internal CORE_TYPELINKS
     * @static
     * 
     * @param string    $pStrTIDObjectType  TID du type d'objet père de type de liens à chercher.
     * @param \PDO      $pObjDBHandler      (Optionel) Database Handler.
     * @return array => tid, bid, stitle, ltitle, typobj_src, typobj_dst, comment, cuser, ctime, uuser, utime, isActive 
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinkTypesFromObjectType($pStrTIDObjectType,\PDO $pObjDBHandler=null):array
    {
        // SQL Fields : tid, bid, stitle, ltitle, typobj_src, typobj_dst, comment, cuser, ctime, uuser, utime, isActive
        try {
            // SQL Query Building!
            $lStrSQLQuery = sprintf(
                "SELECT 
                    LNK.tid,
                    LNK.bid,
                    LNK.stitle,
                    LNK.ltitle,
                    LNK.typobj_src,
                    LNK.typobj_dst,
                    LNK.comment,
                    LNK.cuser,
                    LNK.ctime,
                    LNK.uuser,
                    LNK.utime,
                    LNK.isActive,
                    OBJ_SRC.stitle as obj_src_nom,
                    OBJ_DST.stitle as obj_dst_nom
                FROM 
                    CORE_TYPELINKS LNK 
                    INNER JOIN CORE_TYPEOBJECTS OBJ_SRC ON OBJ_SRC.tid = LNK.typobj_src
                    INNER JOIN CORE_TYPEOBJECTS OBJ_DST ON OBJ_DST.tid = LNK.typobj_dst
                WHERE LNK.typobj_src = '%s'",
                $pStrTIDObjectType
            );            
            return DatabaseManager::queryDB($lStrSQLQuery,$pObjDBHandler);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }// end getLinkTypesFromObjectType()

     /** 
     * Retourne les informations sur les liens dont le type d'objet est fils 
     *  
     * @internal CORE_TYPELINKS    
     * 
     * @param \PDO      $pObjDBHandler      (Optionel) Database Handler.
     * @return array array() => tid, bid, stitle, ltitle, typobj_src, typobj_dst, comment, cuser, ctime, uuser, utime, isActive
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getLinkTypesToObject(\PDO $pObjDBHandler=null):array
    {
        return self::getLinkTypesToObjectType($this->getTIDObjectType($pObjDBHandler),$pObjDBHandler);
    }// end getLinkTypesFromObject()

    /** 
     * Retourne les informations sur les liens dont le type d'objet est fils 
     *  
     * @internal CORE_TYPELINKS
     * @static
     * 
     * @param string    $pStrTIDObjectType  TID du type d'objet fils de type de liens à chercher.
     * @param \PDO      $pObjDBHandler      (Optionel) Database Handler.
     * @return array => tid, bid, stitle, ltitle, typobj_src, typobj_dst, comment, cuser, ctime, uuser, utime, isActive 
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinkTypesToObjectType($pStrTIDObjectType,\PDO $pObjDBHandler=null):array
    {
        // SQL Fields : tid, bid, stitle, ltitle, typobj_src, typobj_dst, comment, cuser, ctime, uuser, utime, isActive
        try {
            // SQL Query Building!
            $lStrSQLQuery = sprintf(
                "SELECT 
                    LNK.tid,
                    LNK.bid,
                    LNK.stitle,
                    LNK.ltitle,
                    LNK.typobj_src,
                    LNK.typobj_dst,
                    LNK.comment,
                    LNK.cuser,
                    LNK.ctime,
                    LNK.uuser,
                    LNK.utime,
                    LNK.isActive,
                    OBJ_SRC.stitle as obj_src_nom,
                    OBJ_DST.stitle as obj_dst_nom
                FROM 
                    CORE_TYPELINKS LNK 
                    INNER JOIN CORE_TYPEOBJECTS OBJ_SRC ON OBJ_SRC.tid = LNK.typobj_src
                    INNER JOIN CORE_TYPEOBJECTS OBJ_DST ON OBJ_DST.tid = LNK.typobj_dst
                WHERE LNK.typobj_dst = '%s'",
                $pStrTIDObjectType
            );            
            return DatabaseManager::queryDB($lStrSQLQuery,$pObjDBHandler);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }// end getLinkTypesToObjectType()

    /** 
     * Retourne un tableau contenant les TID des objets père liés selon un type de lien à l'objet courant.
     *  
     * @internal CORE_LINKS
     * 
     * @param string    $pStrLinkTypeTID    TID du type de liens.
     * @param \PDO      $pObjDBHandler      (Optionel) Database Handler.
     * 
     * @return array tid
     * @throws DatabaseException
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getLinkedTIDToObjectByLinkType($pStrLinkTypeTID,\PDO $pObjDBHandler=null):array
    {
        // SQL Fields : tid, bid, tlnk_tid, objsrc, objdst, cuser, ctime, uuser, utime, isActive        
        try {
            // SQL Query Building!
            $lStrSQLQuery = sprintf(
                "SELECT 
                    objdst as tid                    
                FROM CORE_LINKS WHERE tlnk_tid = '%s' AND objsrc = '%s'",
                $pStrLinkTypeTID,
                $this->getFieldValue('tid')
            );
            return DatabaseManager::queryDB($lStrSQLQuery,$pObjDBHandler);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }// end getLinkedTIDToObjectByLinkType()

    /** 
     * Retourne un tableau contenant les TID des objets fils liés selon un type de lien à l'objet courant.
     *  
     * @internal CORE_LINKS
     *
     * @param string    $pStrLinkTypeTID    TID du type de liens.
     * @param \PDO      $pObjDBHandler      (Optionel) Database Handler.
     * 
     * @return array tid
     * @throws DatabaseException
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getLinkedTIDFromObjectByLinkType($pStrLinkTypeTID,\PDO $pObjDBHandler=null):array
    {
        // SQL Fields : tid, bid, tlnk_tid, objsrc, objdst, cuser, ctime, uuser, utime, isActive        
        try {
            // SQL Query Building!
            $lStrSQLQuery = sprintf(
                "SELECT 
                    objsrc as tid
                FROM CORE_LINKS WHERE tlnk_tid = '%s' AND objdst = '%s'",
                $pStrLinkTypeTID,
                $this->getFieldValue('tid')
            );

            return DatabaseManager::queryDB($lStrSQLQuery,$pObjDBHandler);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }// end getLinkedTIDFromObjectByLinkType()

    /** 
     * Créer un lien entre l'objet courant et un autre objet
     *  
     * @param string    $pStrLinkTypeTID    TID du type de liens.
     * @param string    $pStrObjTID         TID de l'objet à lier.
     * @param \PDO      $pObjDBHandler      (Optionel) DB Handler   
     * 
     * @return boolean TRUE si liens bien créer, FALSE sinon  
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function linkCurrentObjectToObject($pStrLinkTypeTID, $pStrObjTID, \PDO $pObjDBHandler=null):bool
    {
        return !is_null(CoreModelManager::linkObjectToObject($pStrLinkTypeTID,$this->getFieldValue('tid'),$pStrObjTID, $pObjDBHandler));
    }// end linkCurrentObjectToObject()

     /** 
     * Retourne le TID du type de l'objet courant
     * 
     * @internal Table CORE_TYPEOBJECTS
     * 
     * @return string   TID du type de l'objet
     * 
     * @throws DatabaseException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getTIDObjectType():string
    {
        try {
            if (is_null($this->objectTypeTID)) {
                $lObjObjectType = CoreModelManager::getObjectTypeByTablename($this->getTablename());
                $this->objectTypeTID = $lObjObjectType->getFieldValue('tid');
            }
            return $this->objectTypeTID;
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }// end getTIDObjectType()

    /** 
     * Retourne les définitions des attributs complémentaires sur l'objet
     * 
     * @internal Table CORE_ATTRDEFS
     * 
     * @param \PDO      $pObjDBHandler      (Optionel) DB Handler  
     * @return array    array() => tid,tlnk_tid,tobj_tid,bid,stitle,ltitle,attr_type,attr_pattern,attr_default_value,comment,cuser,ctime,uuser,utime,isActive 
     * 
     * @throws DatabaseException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getAdditionalAttributesDefinition(\PDO $pObjDBHandler=null):array
    {
        return  AdditionalAttributeDefinition::getAttributesDefinitionByObjectType($this->getTIDObjectType($pObjDBHandler),$pObjDBHandler);
    }// end getAdditionalAttributesDefinition()

}//end class
?>