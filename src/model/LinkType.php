<?php

namespace polux\CorePHP\Model;

use polux\CorePHP\Database\Generic\SpecificDBObject;
use polux\CorePHP\Database\Generic\BusinessDatabaseObject;

/**
 * Classe LinkType - Type de lien
 * 
 * Réprésente un Type de liens du modèle métier
 * 
 * @author @polux_fr
 */
class LinkType extends SpecificDBObject{
    
    /**
     * Chargement des informations type de liens depuis son TID
     * 
     * @param string $pStrTID   TID à charger. Si null, pas de chargement.
     */
    public function __construct($pStrTID=null)
    {
        $lArrKeyValue = null;
        if(!is_null($pStrTID)){
            $lArrKeyValue = ["tid" => $pStrTID]; 
        }
        parent::__construct('CORE_TYPELINKS',$lArrKeyValue);
    }//end __construct()
    
    /**
     * Définie les champs caractérisant la classe
     */
    protected function initFields()
    {
        // SQL Fields :  tid, bid, stitle, ltitle, typobj_src, typobj_dst, comment, cuser, ctime, uuser, utime, isActive, isSystem
        $this->addNewField('tid');
        $this->addNewField('bid');
        $this->addNewField('stitle');
        $this->addNewField('ltitle');
        $this->addNewField('comment');
        $this->addNewField('typobj_src');
        $this->addNewField('typobj_dst');
        $this->addNewField('cuser');
        $this->addNewField('ctime');
        $this->addNewField('uuser');
        $this->addNewField('utime');
        $this->addNewField('isActive');
        $this->addNewField('isSystem');

        $this->setKeys(['tid']);
    }//end initFields()

    /**
     * Retourne toutes les informations sur les types de liens père pour un type d'objet
     * 
     * @param string    $pStrTIDObjectType      TID du type d'objet pere à chercher
     * @param \PDO      $pObjDBHandler          (Optionel) DB Handler
     * 
     * @return array empty si non trouvés
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinkTypeFromObjectTypeSource($pStrTIDObjectType,\PDO $pObjDBHandler=null)
    {
        return BusinessDatabaseObject::getLinkTypesFromObjectType($pStrTIDObjectType,$pObjDBHandler);
    }//end getLinkTypeFromObjectTypeSource()

    /**
     * Retourne toutes les informations sur les types de liens fils pour un type d'objet
     * 
     * @param string    $pStrTIDObjectType      TID du type d'objet fils à chercher
     * @param \PDO      $pObjDBHandler          (Optionel) DB Handler
     * 
     * @return array empty si non trouvés
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinkTypeFromObjectTypeDestination($pStrTIDObjectType,\PDO $pObjDBHandler=null)
    {
        return BusinessDatabaseObject::getLinkTypesToObjectType($pStrTIDObjectType,$pObjDBHandler);
    }//end getLinkTypeFromObjectTypeDestination()
}//end class