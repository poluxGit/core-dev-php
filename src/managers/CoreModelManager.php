<?php

namespace polux\CorePHP\Managers;

use polux\CorePHP\Internal\Interfaces\IManager;
use polux\CorePHP\Internal\Model\AdditionalAttributeDefinition;
use polux\CorePHP\Internal\Model\AdditionalAttributeValue;
use polux\CorePHP\Internal\Model\Link;
use polux\CorePHP\Internal\Model\LinkType;
use polux\CorePHP\Internal\Model\ObjectType;
use polux\CorePHP\Internal\Exceptions\CoreModelException;
use polux\CorePHP\Internal\Exceptions\GenericApplicationException;

/**
 * Manager des fonctionnalités d'accès au 'CoreModel'
 *
 * @package     Core
 * @subpackage  Model
 */
class CoreModelManager implements IManager {

    /**
     * Initialisation du Manager
     */
    public static function initManager()
    {
        return null;
    }//end initManager()

    // ---------------------------------------------------------------------------------
    // Object Type Methods
    // ---------------------------------------------------------------------------------
    /**
     * Retourne un objet ObjectType depuis son nom de table
     * 
     * @param string $tablename Nom de la table de l'objet à chercher.
     * 
     * @return ObjectType   NULL si Erreur ou non trouvé.
     */
    public static function getObjectTypeByTablename(string $tablename):ObjectType 
    {
        $lObjResult = null;

        try {
            $lObjResult = new ObjectType();
            $lArrObjectCriteria = [ "obj_tablename" => strtoupper($tablename)];
            $lObjResult->loadObject($lArrObjectCriteria);
        }
        catch(\Exception $e)
        {
            $lObjResult = null;
        }
        return $lObjResult;
    }//end getObjectTypeByTablename()

    /**
     * Retourne un objet ObjectType depuis son nom 
     * 
     * @param string $name  Nom l'objet à chercher (i.e => stitle)
     * 
     * @return ObjectType   NULL si Erreur ou non trouvé.
     */
    public static function getObjectTypeByName(string $name):ObjectType 
    {
        $lObjResult = null;
        try {
            $lObjResult = new ObjectType();
            $lArrObjectCriteria = [ "LCASE(stitle)" => strtolower($name)];
            $lObjResult->loadObject($lArrObjectCriteria);
        }
        catch(\Exception $e)
        {
            $lObjResult = null;
        }
        return $lObjResult;
    }//end getObjectTypeByName()

    /**
     * Retourne un objet ObjectType depuis son TID 
     * 
     * @param string $tid  TID de l'objet à chercher 
     * 
     * @return ObjectType   NULL si Erreur ou non trouvé.
     */
    public static function getObjectTypeByTID(string $tid):ObjectType 
    {
        $lObjResult = null;
        try {
            $lObjResult = new ObjectType($tid);  
        }catch(\Exception $e)
        {
            $lObjResult = null;
        }
        return $lObjResult;
    }//end getObjectTypeByTID()

    // protected static function getObjectTypeByObject($object):ObjectType
    // {

    // }

    // ---------------------------------------------------------------------------------
    // AdditionalAttributeDefinition Methods
    // ---------------------------------------------------------------------------------
    /**
     * Retourne un objet AdditionalAttributeDefinition depuis son TID 
     * 
     * @param string $tid  TID de l'objet à chercher 
     * 
     * @return AdditionalAttributeDefinition   NULL si Erreur ou non trouvé.
     */
    public static function getAdditionalAttributeDefinitionByTID($tid):AdditionalAttributeDefinition
    {
        $lObjResult = null;
        try {
            $lObjResult = new AdditionalAttributeDefinition($tid);  
        }catch(\Exception $e)
        {
            $lObjResult = null;
        }
        return $lObjResult;
    }

    /**
     * Retourne un tableau d'objets AdditionalAttributeDefinition depuis un nom de type d'objet
     * 
     * @param string    $objectTypeName    Nom de l'objet à chercher.
     * @return array    Tableaux d'objets 'AdditionalAttributeDefinition' | NULL si Erreur ou non trouvé.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getAdditionalAttributeDefinitionsByObjectTypeName(string $objectTypeName):array
    {
        $lArrResultats = [];
        
        // Récupération de l'ObjectType
        $oObjectType = self::getObjectTypeByName($objectTypeName);
        if(is_null($oObjectType))
        {
            $lArrParams = [
                $objectTypeName
            ];
            throw new CoreModelException('COREMODEL-OBJECT_NAME_NOT_FOUND',$lArrParams);
        }

        try {
            // Pour chacun des AdditionalAttributeDefinition sur le type d'objet !
            foreach(AdditionalAttributeDefinition::getAttributesDefinitionByObjectType($oObjectType->getFieldValue('tid')) as $sAADTIDValue)
            {
                $lArrResultats[$sAADTIDValue['tid']] = new AdditionalAttributeDefinition($sAADTIDValue['tid']); 
            }            
        }catch(\Exception $e)
        {
            $lArrParams = [
                $objectTypeName,
                $e->getMessage()
            ];
            throw new GenericApplicationException('GEN-EXCEPTION',$lArrParams);
        }
        return $lArrResultats;
    }//end getAdditionalAttributeDefinitionsByObjectTypeName()    

    // ---------------------------------------------------------------------------------
    // LinkType Methods
    // ---------------------------------------------------------------------------------
    /**
     * Retourne un objet LinkType depuis son TID 
     * 
     * @param string    $tid    TID du type de lien. 
     * 
     * @return LinkType NULL si Erreur ou non trouvé.
     */
    public static function getLinkTypeByTID(string $tid):LinkType 
    {
        $lObjResult = null;
        try {
            $lObjResult = new LinkType($tid);  
        }catch(\Exception $e)
        {
            $lObjResult = null;
        }
        return $lObjResult;
    }//end getLinkTypeByTID()

    /**
     * Retourne un tableau d'objets LinkType depuis un nom de type d'objet père
     * 
     * Retourne les liens dont l'objet est père uniquement.
     * 
     * @param string    $objectTypeName    Nom de l'objet à chercher.
     * @return array    Tableaux d'objets 'LinkType' | NULL si Erreur ou non trouvé.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinkTypeByFatherObjectTypeName(string $objectTypeName):array
    {
        $lArrResultats = [];
        
        // Récupération de l'ObjectType
        $oObjectType = self::getObjectTypeByName($objectTypeName);
        if(is_null($oObjectType))
        {
            $lArrParams = [
                $objectTypeName
            ];
            throw new CoreModelException('COREMODEL-OBJECT_NAME_NOT_FOUND',$lArrParams);
        }

        try {
            // Pour chacun des LinkType sur le type d'objet père !
            foreach(LinkType::getLinkTypeFromObjectTypeSource($oObjectType->getFieldValue('tid')) as $sLnkTypeTIDValue)
            {
                $lArrResultats[$sLnkTypeTIDValue] = new LinkType($sLnkTypeTIDValue); 
            }            
        }catch(\Exception $e)
        {
            $lArrParams = [
                $objectTypeName,
                $e->getMessage()
            ];
            throw new GenericApplicationException('GEN-EXCEPTION',$lArrParams);
        }
        return $lArrResultats;
    }//end getLinkTypeByFatherObjectTypeName()    

     /**
     * Retourne un tableau d'objets LinkType depuis un nom de type d'objet père
     * 
     * Retourne les liens dont l'objet est père uniquement.
     * 
     * @param string    $objectTypeName    Nom de l'objet à chercher.
     * @return array    Tableaux d'objets 'LinkType' | NULL si Erreur ou non trouvé.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinkTypeBySonObjectTypeName(string $objectTypeName):array
    {
        $lArrResultats = [];
        
        // Récupération de l'ObjectType
        $oObjectType = self::getObjectTypeByName($objectTypeName);
        if(is_null($oObjectType))
        {
            $lArrParams = [
                $objectTypeName
            ];
            throw new CoreModelException('COREMODEL-OBJECT_NAME_NOT_FOUND',$lArrParams);
        }

        try {
            // Pour chacun des LinkType sur le type d'objet père !
            foreach(LinkType::getLinkTypeFromObjectTypeDestination($oObjectType->getFieldValue('tid')) as $sLnkTypeTIDValue)
            {
                $lArrResultats[$sLnkTypeTIDValue] = new LinkType($sLnkTypeTIDValue); 
            }            
        }catch(\Exception $e)
        {
            $lArrParams = [
                $objectTypeName,
                $e->getMessage()
            ];
            throw new GenericApplicationException('GEN-EXCEPTION',$lArrParams);
        }
        return $lArrResultats;
    }//end getLinkTypeByFatherObjectTypeName()   

    // ---------------------------------------------------------------------------------
    // AdditionalAttributeValue Methods
    // ---------------------------------------------------------------------------------
    /**
     * Retourne un objet AdditionalAttributeValue depuis un objet et sa definition d'attribut
     * 
     * @param string $tidObject     TID de l'objet à chercher 
     * @param string $tidAddAttrDef TID de la définition d'attribut
     * 
     * @return AdditionalAttributeValue   NULL si Erreur ou non trouvé.
     */
    public static function getAdditionalAttributeValueOnObjectByTID($tidObject,$tidAddAttrDef):AdditionalAttributeValue
    {
        $lObjResult = null;
        try {
            $lObjResult = new AdditionalAttributeValue($tidObject,$tidAddAttrDef);  
        }catch(\Exception $e)
        {
            $lObjResult = null;
        }
        return $lObjResult;
    }//end getAdditionalAttributeValueOnObjectByTID()

    // ---------------------------------------------------------------------------------
    // Link Methods
    // ---------------------------------------------------------------------------------
    /**
     * Retourne un objet Link depuis son TID
     * 
     * @param string $tidLink     TID du liens à charger.
     * 
     * @return Link   NULL si Erreur ou non trouvé.
     */
    public static function getLinkByTID($tidLink):Link
    {
        $lObjResult = null;
        try {
            $lObjResult = new Link($tidLink);  
        }catch(\Exception $e)
        {
            $lObjResult = null;
        }
        return $lObjResult;
    }//end getLinkByTID()

    /**
     * Retourne un tableau d'objets Link depuis un objet père
     * 
     * Retourne les liens dont l'objet est père uniquement.
     * 
     * @param string    $objectTID    TID de l'objet source à chercher.
     * @return array    Tableaux d'objets 'Link' | NULL si Erreur ou non trouvé.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinksByFatherObject(string $objectTID):array
    {
        $lArrResultats = [];
    
        try {
            // Pour chacun des Links sur l'objet père !
            foreach(Link::getLinksByFatherObject($objectTID) as $sLnkTIDValue)
            {
                $lArrResultats[$sLnkTIDValue] = new Link($sLnkTIDValue); 
            }            
        }catch(\Exception $e)
        {
            $lArrParams = [
                $objectTID,
                $e->getMessage()
            ];
            throw new GenericApplicationException('GEN-EXCEPTION',$lArrParams);
        }
        return $lArrResultats;
    }//end getLinksByFatherObject()   

    /**
     * Retourne un tableau d'objets Link depuis un objet fils
     * 
     * Retourne les liens dont l'objet est père uniquement.
     * 
     * @param string    $objectTID    TID de l'objet destination à chercher.
     * @return array    Tableaux d'objets 'Link' | NULL si Erreur ou non trouvé.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getLinksBySonObject(string $objectTID):array
    {
        $lArrResultats = [];    
        try {
            // Pour chacun des Links sur l'objet fils !
            foreach(Link::getLinksBySonObject($objectTID) as $sLnkTIDValue)
            {
                $lArrResultats[$sLnkTIDValue] = new Link($sLnkTIDValue); 
            }            
        }catch(\Exception $e)
        {
            $lArrParams = [
                $objectTID,
                $e->getMessage()
            ];
            throw new GenericApplicationException('GEN-EXCEPTION',$lArrParams);
        }
        return $lArrResultats;
    }//end getLinksBySonObject()  

    /** 
     * Création d'un lien entre 2 objets (et selon un type de lien).
     *  
     * @static
     * @param string    $linkTypeTID    TID du type de liens.
     * @param string    $srcObjectTID   TID de l'objet Source à lier.
     * @param string    $dstObjectTID   TID de l'objet Destination à lier.
     * 
     * @todo Rajouter check des type d'objets en fonction du type de liens....
     * 
     * @return Link     Objet Link crée 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function linkObjectToObject(string $linkTypeTID, string $srcObjectTID,string $dstObjectTID):Link
    {
        $lObjResult = null;
        try {
            $bOk = Link::linkObjectToObject($linkTypeTID,$srcObjectTID,$dstObjectTID);
            if (! $bOk) {
                throw new \Exception("Error during creation of a link...");
            }
            $lObjResult = new Link();
            $lArrConditions = [
                "tlnk_id"=>"$linkTypeTID",
                "objsrc"=>"$srcObjectTID",
                "objdst"=>"$dstObjectTID"
            ];
            $lObjResult->loadObject($lArrConditions);
            
        }catch(\Exception $e)
        {
            $lArrParams = [
                $linkTypeTID,
                $srcObjectTID,
                $dstObjectTID,
                $e->getMessage()
            ];
            throw new GenericApplicationException('GEN-EXCEPTION',$lArrParams);
        }

        return $lObjResult;
    }//end linkObjectToObject()

}//end class

 ?>
