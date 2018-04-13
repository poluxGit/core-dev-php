<?php

namespace polux\CorePHP\Internal\Model;

use polux\CorePHP\Internal\Database\Generic\SpecificDBObject;

use polux\CorePHP\Managers\DatabaseManager;

use polux\CorePHP\Internal\Exceptions\DatabaseException;

/**
 * Classe LinkType - Type de lien
 * 
 * Réprésente un Type de liens du modèle métier
 * 
 * @author @polux_fr
 */
class Link extends SpecificDBObject{
    
    /**
     * Chargement des informations d'un lien depuis son TID
     * 
     * @param string $pStrTID   TID du lien à charger. Si null, pas de chargement.
     */
    public function __construct($pStrTID=null)
    {
        $lArrKeyValue = null;
        if(!is_null($pStrTID)){
            $lArrKeyValue = ["tid" => $pStrTID]; 
        }
        parent::__construct('CORE_LINKS',$lArrKeyValue);
    }//end __construct()
    
    /**
     * Définie les champs caractérisant la classe
     */
    protected function initFields():void
    {
        // SQL Fields :  tid, bid, tlnk_tid, objsrc, objdst, cuser, ctime, uuser, utime, isActive
        $this->addNewField('tid');
        $this->addNewField('bid');
        $this->addNewField('tlnk_tid');
        $this->addNewField('objsrc');
        $this->addNewField('objdst');
        $this->addNewField('cuser');
        $this->addNewField('ctime');
        $this->addNewField('uuser');
        $this->addNewField('utime');
        $this->addNewField('isActive');
        
        $this->setKeys(['tid']);
    }//end initFields()

    public function getSourceObjectTID():string
    {
        return $this->getFieldValue('objsrc');
    }

    public function getTargetObjectTID():string
    {
        return $this->getFieldValue('objdst');
    }

    public function getLinkTypeTID():string
    {
        return $this->getFieldValue('tlnk_tid');
    }

    /**
     * Retourne un tableau de TID des Liens dont l'objet est père
     * 
     * @param string $tidObject TID de l'objet père
     * 
     * @return array    Tableau de TID de liens trouvés
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinksByFatherObject($tidObject):array
    {
        try {
            // SQL Query Building!
            $lStrSQLQuery = sprintf(
                "SELECT 
                    LNK.tid as tid,
                FROM 
                    CORE_LINKS LNK 
                WHERE LNK.objsrc = '%s'",
                $tidObject
            );            
            return DatabaseManager::queryDB($lStrSQLQuery);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }//end getLinksByFatherObject()

    /**
     * Retourne un tableau de TID des Liens dont l'objet est fils
     * 
     * @param string $tidObject TID de l'objet fils
     * 
     * @return array    Tableau de TID de liens trouvés
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinksBySonObject($tidObject):array
    {
        try {
            // SQL Query Building!
            $lStrSQLQuery = sprintf(
                "SELECT 
                    LNK.tid as tid,
                FROM 
                    CORE_LINKS LNK 
                WHERE LNK.objdst = '%s'",
                $tidObject
            );            
            return DatabaseManager::queryDB($lStrSQLQuery);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }//end getLinksBySonObject()

    /** 
     * Création d'un lien entre 2 objets (et selon un type de lien).
     *  
     * @static
     * @param string    $linkTypeTID    TID du type de liens.
     * @param string    $srcObjectTID   TID de l'objet Source à lier.
     * @param string    $dstObjectTID   TID de l'objet Destination à lier.
     * 
     * @return boolean TRUE si liens bien créer, FALSE sinon  
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function linkObjectToObject(string $linkTypeTID, string $srcObjectTID,string $dstObjectTID):bool
    {
        // SQL Fields : tid, bid, tlnk_tid, objsrc, objdst, cuser, ctime, uuser, utime, isActive        
        try {
            // SQL Query Building!
            $lStrSQLQuery = sprintf(
                "INSERT INTO CORE_LINKS (tlnk_tid,objsrc,objdst) VALUES ('%s','%s','%s')",
                $linkTypeTID,
                $srcObjectTID,
                $dstObjectTID
            );
            // Execution
            $lIntNbRows = DatabaseManager::execDB($lStrSQLQuery);
            return ($lIntNbRows == 1);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }        
    }// end linkObjectToObject()

}//end class