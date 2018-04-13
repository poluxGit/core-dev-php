<?php

namespace polux\CorePHP\Internal\Model;

use polux\CorePHP\Internal\Database\Generic\SpecificDBObject;
use polux\CorePHP\Internal\Exceptions\DatabaseException;

use polux\CorePHP\Managers\DatabaseManager;

/**
 * Classe AdditionalAttributeValue - Attribut additionnel d'objet
 * 
 * Réprésente un Attribut additionnel d'objet du modèle métier
 * 
 * @author @polux_fr
 */
class AdditionalAttributeValue extends SpecificDBObject
{   
    /**
     * Chargement des informations d'un type d'objet depuis son TID
     * 
     * @param string $pStrTID   TID à charger. Si null, pas de chargement.
     */
    public function __construct($pStrTIDObject,$pStrTIDAddAttrDef)
    {
        $lArrKeyValue = [
            "obj_tid" => $pStrTIDObject,
            "adef_tid" => $pStrTIDAddAttrDef
        ];
        
        parent::__construct('CORE_ATTROBJECTS',$lArrKeyValue);
    }//end __construct()
    
    /**
     * Définie les champs caractérisants la classe
     */
    protected function initFields()
    {
        // SQL Fields : tid, bid, stitle, ltitle, obj_tid, adef_tid, attr_value, comment, cuser, ctime, uuser, utime, isActive, isSystem
        $this->addNewField('tid');
        $this->addNewField('bid');
        $this->addNewField('stitle');
        $this->addNewField('ltitle');
        $this->addNewField('comment');
        $this->addNewField('obj_tid');
        $this->addNewField('adef_tid');
        $this->addNewField('attr_value');
        $this->addNewField('cuser');
        $this->addNewField('ctime');
        $this->addNewField('uuser');
        $this->addNewField('utime');
        $this->addNewField('isActive');
        $this->addNewField('isSystem');

        $this->setKeys(['tid']);
    }//end initFields()

    // ---------------------------------------------------------------------------------
    // Specifics Getters & Setters
    // ---------------------------------------------------------------------------------    
    /**
     * Renvoi la valeur de l'attribut sur objet
     * 
     * @return string Valeur de l'attibut additionnel/complémentaire
     */
    public function getAttributeValue()
    {
        return $this->getFieldValue('attr_value');
    }//end getAttributeValue()

    /**
     * Définie la valeur de l'attribut sur objet
     * 
     * @param string $value Valeur de l'attribut à définir.
     */
    public function setAttributeValue($value)
    {
        $this->setFieldValue('attr_value',$value);
    }//end setAttributeValue()

    /**
     * Renvoi le TID de AdditionalAttributeDefinition
     * 
     * @return string Valeur de l'attibut TID de AdditionalAttributeDefinition
     */
    public function getAdditionalAttributeDefinitionTID():string
    {
        return $this->getFieldValue('adef_tid');
    }//end getAdditionalAttributeDefinitionTID()

     /**
     * Renvoi le TID de ObjectType
     * 
     * @return string Valeur de l'attibut TID de ObjectType
     */
    public function getObjectTypeTID():string
    {
        return $this->getFieldValue('obj_tid');
    }//end getObjectTypeTID()

    // ---------------------------------------------------------------------------------
    // Méthodes STATIQUES - STATIC
    // ---------------------------------------------------------------------------------
    /**
     * Retourne la valeur d'un attribut sur objet selon un type d'attribut.
     * 
     * @param string    $pStrTIDObject          TID de l'objet.
     * @param string    $pStrTIDAttrDef         TID de la définition d'attribut.
     * 
     * @return array    Tableaux de TID de AdditionalAttributeValue
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getAttributeValuesOnObject($pStrTIDObject):array
    {
        try {
            // SQL fields : tid, bid, stitle, ltitle, obj_tid, adef_tid, attr_value, comment, cuser, ctime, uuser, utime, isActive, isSystem
            $lStrSQLQuery = sprintf(
                "SELECT 
                   tid               
                FROM CORE_ATTROBJECTS WHERE obj_tid = '%s'",
                $pStrTIDObject
            );
            $lArrResult = DatabaseManager::queryDB($lStrSQLQuery);
            return $lArrResult;
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQuery,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-EX",$lArrParams);
        }      
    }//end getAttributeValuesOnObject()

}//end class