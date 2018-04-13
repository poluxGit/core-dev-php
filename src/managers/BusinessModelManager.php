<?php

namespace polux\CorePHP\Managers;

use polux\CorePHP\Internal\Interfaces\IManager;
use polux\CorePHP\Internal\Logs\Logger;
use polux\CorePHP\Internal\Model\AdditionalAttributeDefinition;
use polux\CorePHP\Internal\Model\LinkType;
use polux\CorePHP\Internal\Exceptions\GenericApplicationException;


require_once(__DIR__.'/../../vendor/smarty/smarty/libs/Smarty.class.php');

/**
 * BusinessModelManager
 * 
 * Gestion / Administration du modèle métier propre à l'instance d'application.
 */
class BusinessModelManager implements IManager
{
    /**
     * getObjectTypes => renvoi tous les types d'objet
     *
     * Retourne les caractéristiques de tous les objets du modèle métiers de l'application.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * 
     * @return array(mixed)     Caractéristiques par objet
     **/
    public static function getObjectTypes():array
    {
        $lStrSQLQuery = "SELECT
            tid,
            bid,
            stitle,
            ltitle,
            comment,
            obj_prefix,
            obj_tablename,
            cuser,
            ctime,
            uuser,
            utime,
            isActive,
            obj_type,
            isSystem
            FROM CORE_TYPEOBJECTS
            WHERE isSystem=0" ;
        return DatabaseManager::queryDB($lStrSQLQuery);
    }//end getObjectTypes()

    /**
     * getObjectTypeDataByTablename => Renvoi un type d'objet depuis son nom de table
     *
     * Retourne les caractéristiques de l'objet du modèle métiers de l'application
     * dont le nom de la table est passé en argument.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * 
     * @param string $pStrTablename     Nom de la table de l'objet
     * @return array(mixed)     Caractéristiques du type d'objet
     **/
    public static function getObjectTypeDataByTablename(string $pStrTablename):array
    {
        $lStrSQLQuery = "SELECT
            tid,
            bid,
            stitle,
            ltitle,
            comment,
            obj_prefix,
            obj_tablename,
            cuser,
            ctime,
            uuser,
            utime,
            isActive,
            obj_type,
            isSystem
            FROM CORE_TYPEOBJECTS
            WHERE isSystem=0 AND obj_tablename = '".$pStrTablename."'";
        return DatabaseManager::queryDB($lStrSQLQuery);
    }//end getObjectTypeDataByTablename()

    /**
     * getObjectTypeDataByTID => Renvoi un type d'objet depuis son TID
     *
     * Retourne les caractéristiques de l'objet du modèle métiers de l'application
     * dont le TID est passé en argument.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param string $pStrTID     TID du type d'objet
     * @return array(mixed)     Caractéristiques du type d'objet
     **/
    public static function getObjectTypeDataByTID(string $pStrTID):array
    {
        $lStrSQLQuery = "SELECT
            tid,
            bid,
            stitle,
            ltitle,
            comment,
            obj_prefix,
            obj_tablename,
            cuser,
            ctime,
            uuser,
            utime,
            isActive,
            obj_type,
            isSystem
            FROM CORE_TYPEOBJECTS
            WHERE isSystem=0 AND tid = '".$pStrTID."'";
        return DatabaseManager::queryDB($lStrSQLQuery);
    }//end getObjectTypeDataByTID()

    /**
     * getObjectTypeDataByPrefix => Renvoi un type d'objet depuis son préfix
     *
     * Retourne les caractéristiques de l'objet du modèle métiers de l'application
     * dont le préfix est passé en argument.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param string $pStrObjectsPrefix     Préfix du type d'objet
     * @return array(mixed)     Caractéristiques du type d'objet
     **/
    public static function getObjectTypeDataByPrefix(string $pStrObjectsPrefix):array
    {
        $lStrSQLQuery = "SELECT
            tid,
            bid,
            stitle,
            ltitle,
            comment,
            obj_prefix,
            obj_tablename,
            cuser,
            ctime,
            uuser,
            utime,
            isActive,
            obj_type,
            isSystem
            FROM CORE_TYPEOBJECTS
            WHERE isSystem=0 AND obj_prefix = '".$pStrObjectsPrefix."'";
    return DatabaseManager::queryDB($lStrSQLQuery);
    }//end getObjectTypeDataByPrefix()

    
    /**
     * addNewLinkTypesBwnObj => Définie un nouveau type de liens entre objets
     * 
     * Définition d'un nouveau type de liens entre objet du modèle métiers de l'application.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     * 
     * @param string $pStrShortTitle        Titre court du lien
     * @param string $pStrLongTitle         Titre Long du lien
     * @param string $pStrTablenameParent   Table de l'objet Parent du lien
     * @param string $pStrTablenameParent   Table de l'objet Fils du lien
     * 
     * @return boolean TRUE si OK, FALSE SI ERREUR NON CRITIQUE
     */
    public static function addNewLinkTypesBwnObj($pStrShortTitle,$pStrLongTitle,$pStrTablenameParent,$pStrTablenameSon,$pStrComment=null):bool
    {
        // CALL `CORE_addTypeLinkFromTableName`(
        //      <{IN pStrShortTile VARCHAR(30)}>, 
        //      <{IN pStrLongTile VARCHAR(100)}>, 
        //      <{IN pStrComment TEXT}>, 
        //      <{IN pStrTableNameParent VARCHAR(150)}>, 
        //      <{IN pStrTableNameSon VARCHAR(150)}>);

        $lStrSQLQuery = sprintf(
            "CALL `CORE_addTypeLinkFromTableName`('%s','%s','%s','%s','%s');" ,
            $pStrShortTitle,
            $pStrLongTitle,
            $pStrComment,
            $pStrTablenameParent,
            $pStrTablenameSon
        );
        return DatabaseManager::execDB($lStrSQLQuery);
       
    }//end addNewLinkTypesBwnObj()
    
    /**
     * getAdditionalAttributesDefinition => Renvoi toutes les défintions d'attributs complémentaires des objets
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     * 
     * Retourne les informations de tous les attributs complémentaires du modèle métier de l'application.
     * 
     * @return array
     */
    public static function getAdditionalAttributesData():array
    {
        $lStrSQLQuery = "SELECT 
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
                isActive,
                isSystem
            FROM CORE_ATTRDEFS
            WHERE isSystem=0";
        return DatabaseManager::queryDB($lStrSQLQuery);
    }//end getAdditionalAttributesData()

    /**
     * getAdditionalAttributesDefinition => Renvoi toutes les défintions d'attributs complémentaires des objets
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     * 
     * Retourne les informations de tous les attributs complémentaires du modèle métier de l'application.
     * 
     * @return array
     */
    public static function getAdditionalAttributesDataByObjetcType($pStrObjType):array
    {
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
            WHERE isSystem=0 AND tobj_tid = '%s'",
            $pStrObjType
        );
        return DatabaseManager::queryDB($lStrSQLQuery);
    }//end getAdditionalAttributesDataByObjetcType()

    /**
     * addNewAttributesOnObject => Définie un nouvel attribut complémentaire sur un objet
     * 
     * Définition d'un nouvel attribut complémentaire sur un objet du modèle métiers de l'application.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     * 
     * @param string $pStrObjTablename      Table de l'objet concerné
     * @param string $pStrShortTitle        Titre court de l'attribut
     * @param string $pStrLongTitle         Titre Long de l'attribut
     * @param string $pStrComment           Commentaire de l'attribut
     * @param string $pStrAttrType          Type de l'attribut
     * @param string $pStrDefaultValue      Valeur par défaut l'attribut
     * 
     * @return boolean TRUE si OK, FALSE SI ERREUR NON CRITIQUE
     */
    public static function addNewAttributesOnObject($pStrObjTablename,$pStrShortTitle,$pStrLongTitle,$pStrComment,$pStrAttrType, $pStrDefaultValue):bool
    {
        // CALL `CORE_addAttributeDefinitionForAnObject`(
        //      <{IN pTypeObjTableName VARCHAR(150)}>, 
        //      <{IN pStrShortTitle VARCHAR(30)}>, 
        //      <{IN pStrLongTitle VARCHAR(100)}>, 
        //      <{IN pStrComment TEXT}>, 
        //      <{IN pStrAttrType VARCHAR(100)}>, 
        //      <{IN pStrAttrPattern VARCHAR(200)}>, 
        //      <{IN pStrAttrDefaultValue VARCHAR(1000)}>);
        $lStrSQLQuery = sprintf(
            "CALL `CORE_addAttributeDefinitionForAnObject`('%s','%s','%s','%s','%s','%s','%s');" ,
            $pStrObjTablename,
            $pStrShortTitle,
            $pStrLongTitle,
            $pStrComment,
            $pStrAttrType,
            $pStrDefaultValue
        );
        DatabaseManager::execDB($lStrSQLQuery);

        return true;

    }//end addNewAttributesOnObject()

    /**
     * initManager
     * 
     * Initialisation du Manager
     * 
     * @static
     */
    public static function initManager()
    {
        // initialisation de Smarty ?
        return null;
    }//end initManager()


    public static function generatePHPClassesFilesFromTemplates()
    {

    }

    /**
     * Génération des classes sur Objets simples enregistrés en base de données
     * 
     * @internal CORE_TYPEOBJECTS, CORE_TYPELINKS
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function generatePHPFilesClassesForSimpleObjects(\PDO $pObjDBHandler, Logger $pObjLogger = null)
    {
        try {

            (!is_null($pObjLogger))?$pObjLogger->logMessage("Démarrage de la génération des classes PHP depuis les objets 'Simples'."):null;
            $lObjSmarty = new \Smarty();
            $lArrObjectsTypes = self::getObjectTypes();            

            // Vérification existance template Smarty
            // Pour chacun des objets
            foreach($lArrObjectsTypes as $lArrRowOT)
            {
                // On filtre sur les simples uniquement!
                if ($lArrRowOT['obj_type']=='Simple') {

                    (!is_null($pObjLogger))?$pObjLogger->logMessage("* Classe PHP Simple - Objet '".$lArrRowOT['stitle']."'."):null;

                    // Initialisation Objet Template SMARTY
                    $lObjTemplate = $lObjSmarty->createTemplate(__DIR__."/templates/SimpleObjClassTemplate.tpl");
  
                    // Affectation des variables
                    $lObjTemplate->assign('nomClasse', $lArrRowOT['stitle']);
                    $lObjTemplate->assign('descriptionClasse', $lArrRowOT['ltitle']);
                    $lObjTemplate->assign('classeTablename', $lArrRowOT['obj_tablename']);

                    // Attributs Complémentaires ...
                     $lArrAdditionalAtts = AdditionalAttributeDefinition::getAttributesDefinitionByObjectType($lArrRowOT['tid'],$pObjDBHandler) ;
                     $lObjTemplate->assign('addAttributes', $lArrAdditionalAtts);

                    // Links Pere & Fils
                    $lArrFLinks = LinkType::getLinkTypeFromObjectTypeSource($lArrRowOT['tid'],$pObjDBHandler);
                    $lArrSLinks = LinkType::getLinkTypeFromObjectTypeDestination($lArrRowOT['tid'],$pObjDBHandler);
                    $lObjTemplate->assign('liensPere', $lArrFLinks);
                    $lObjTemplate->assign('liensFils', $lArrSLinks);
                    
                    $lStrPHPClassContent = $lObjTemplate->fetch();
                    $lStrPHPClassFilename = __DIR__.'/classes/'.$lArrRowOT['stitle'].".php";

                    if(\file_exists($lStrPHPClassFilename))
                    {
                        \unlink($lStrPHPClassFilename);
                    }
                    file_put_contents($lStrPHPClassFilename, $lStrPHPClassContent);                    
                    (!is_null($pObjLogger))?$pObjLogger->logMessage("* Enregistrement dans le fichier '".$lStrPHPClassFilename."' OK !"):null;
                }

                $lObjTemplate = null;
            }

        } catch(\Exception $ex)
        {
            $lArrParams = [
                $ex->getMessage()
            ];
            throw new GenericApplicationException('GEN-TPL_PHP_SIMPLEOBJ_GENERATION',$lArrParams);
        }
    }//end generatePHPFilesClassesForSimpleObjects()

    /**
     * Génération des classes sur Objets Complexes enregistrés en base de données
     * 
     * @internal CORE_TYPEOBJECTS, CORE_TYPELINKS
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function generatePHPClassesForComplexObjects(\PDO $pObjDBHandler,Logger $pObjLogger = null)
    {
        try {            
            (!is_null($pObjLogger))?$pObjLogger->logMessage("Démarrage de la génération des classes PHP depuis les objets 'Complex'."):null;
            $lObjSmarty = new \Smarty();
            $lArrObjectsTypes = self::getObjectTypes();            

            // Vérification existance template 'Smarty'
            // Pour chacun des objets
            foreach($lArrObjectsTypes as $lArrRowOT)
            {
                // On filtre sur les simples uniquement!
                if ($lArrRowOT['obj_type']=='Complex') {

                    (!is_null($pObjLogger))?$pObjLogger->logMessage("* Classe PHP Simple - Objet '".$lArrRowOT['stitle']."'."):null;
                    // Initialisation Objet Template SMARTY
                    $lObjTemplate = $lObjSmarty->createTemplate(__DIR__."/templates/ComplexObjClassTemplate.tpl");
  
                    // Affectation des variables
                    $lObjTemplate->assign('nomClasse', $lArrRowOT['stitle']);
                    $lObjTemplate->assign('descriptionClasse', $lArrRowOT['ltitle']);
                    $lObjTemplate->assign('classeTablename', $lArrRowOT['obj_tablename']);

                    // Attributs Complémentaires ...
                     $lArrAdditionalAtts = AdditionalAttributeDefinition::getAttributesDefinitionByObjectType($lArrRowOT['tid'],$pObjDBHandler) ;
                     $lObjTemplate->assign('addAttributes', $lArrAdditionalAtts);

                    // Links Pere & Fils
                    $lArrFLinks = LinkType::getLinkTypeFromObjectTypeSource($lArrRowOT['tid'],$pObjDBHandler);
                    $lArrSLinks = LinkType::getLinkTypeFromObjectTypeDestination($lArrRowOT['tid'],$pObjDBHandler);
                    $lObjTemplate->assign('liensPere', $lArrFLinks);
                    $lObjTemplate->assign('liensFils', $lArrSLinks);
                    
                    $lStrPHPClassContent = $lObjTemplate->fetch();
                    $lStrPHPClassFilename = __DIR__.'/classes/'.$lArrRowOT['stitle'].".php";

                    if(\file_exists($lStrPHPClassFilename))
                    {
                        \unlink($lStrPHPClassFilename);
                    }
                    file_put_contents($lStrPHPClassFilename, $lStrPHPClassContent);     
                    (!is_null($pObjLogger))?$pObjLogger->logMessage("* Enregistrement dans le fichier '".$lStrPHPClassFilename."' OK !"):null;               
                }
                // RAZ du template
                $lObjTemplate = null;
            }
        } catch(\Exception $ex)
        {
            $lArrParams = [
                $ex->getMessage()
            ];
            throw new GenericApplicationException('GEN-TPL_PHP_COMPLEXOBJ_GENERATION',$lArrParams);
        }
    }//end generatePHPClassesForComplexObjects()
}//end class
