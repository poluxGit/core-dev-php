<?php

namespace polux\CorePHP\Internal\Model;

use polux\CorePHP\Internal\Database\Generic\SpecificDBObject;

/**
 * Classe ObjectType - Type d'Objet
 * 
 * Réprésente un Type d'objet du modèle métier
 * 
 * @author @polux_fr
 */
class ObjectType extends SpecificDbObject{
    
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
        parent::__construct('CORE_TYPEOBJECTS',$lArrKeyValue);
    }//end __construct()
    
    /**
     * Définie les champs caractérisant la classe
     */
    protected function initFields()
    {
        // SQL Fields :  tid, bid, stitle, ltitle, comment, obj_prefix, obj_tablename, cuser, ctime, uuser, utime, isActive, obj_type, isSystem
        $this->addNewField('tid');
        $this->addNewField('bid');
        $this->addNewField('stitle');
        $this->addNewField('ltitle');
        $this->addNewField('comment');
        $this->addNewField('obj_prefix');
        $this->addNewField('obj_tablename');
        $this->addNewField('cuser');
        $this->addNewField('ctime');
        $this->addNewField('uuser');
        $this->addNewField('utime');
        $this->addNewField('isActive');
        $this->addNewField('obj_type');
        $this->addNewField('isSystem');

        $this->setKeys(['tid']);
    }

    public function getName():string
    {
        return $this->getFieldValue('stitle');
    }

    public function getTablename():string
    {
        return $this->getFieldValue('obj_tablename');
    }

    public function getPrefix():string
    {
        return $this->getFieldValue('obj_prefix');
    }

    public function getObjectType():string
    {
        return $this->getFieldValue('obj_type');
    }
    
}