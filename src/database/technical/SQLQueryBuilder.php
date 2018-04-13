<?php
namespace polux\CorePHP\Database\Technical;

/**
 * DBObject -  Classe de translation des informations métier en Objet et requete SQL
 *
 * Classe mère generique pour objets stockés en base de données.
 *
 * @author polux <polux@poluxfr.org>
 */
// use polux\CorePHP\Application\ApplicationException as AppException;
//use polux\CorePHP\Database\Technical\DbQuery as CoreDBQuery;
use polux\CorePHP\Database\Generic\SimpleGenericDBObject;

/**
 * SQLQueryBuilder
 *
 * Mécanisme natif du système de stockage en base de données.
 */
class SQLQueryBuilder {

    /**
     * generateSQLUpdateOrderFromObj
     *
     * Génère l'ordre de mise à jour nécessaire pour l'objet en cours.
     *
     * @param SimpleGenericDBObject $oCore Objet à mettre à jour en base de données.
     * @return string Requête SQL de mise à jour de l'objet.
     **/
    public static function generateSQLUpdateOrderFromObj(SimpleGenericDBObject $oCore):string
    {
        // UPDATE
        $lStrSQLOrder  = "UPDATE ";
        $lStrSQLOrder .= $oCore->getTablename();
        $lStrSQLOrder .= " ";

        // SET
        $lArrSetCond = [];
        foreach(\array_keys($oCore->getFields()) as  $value)
        {
            if($oCore->isFieldNeedAnUpdate($value))
            {
                $lxFieldValue = $oCore->getFieldValue($value);
                $lArrSetCond[] = $value . " = '" .$lxFieldValue. "'" ;
            }
        }        
        
        $lStrSQLOrder .= " SET ".implode(", ",$lArrSetCond);
        
        // WHERE
        $lStrSQLOrder .= " WHERE tid='".$oCore->getFieldValue("tid")."'";

        return $lStrSQLOrder;
    }//end generateSQLUpdateOrderFromObj()

    /**
     * buildSQLUpdateOrder
     *
     * Génère l'ordre de mise à jour nécessaire depuis les paramètres
     *
     * @param string $pStrTablename         Nom de la table
     * @param array $pArrSet                Tableau des ordres Set 
     * @param array $pArrWhereCondition     Tableaux des conditions WHERE (Key => Valeur) 
     * @return string Requête SQL de mise à jour de l'objet.
     **/
    public static function buildSQLUpdateOrder(string $pStrTablename, array $pArrSet , array $pArrWhereCondition):string
    {
        // UPDATE
        $lStrSQLOrder  = "UPDATE ";
        $lStrSQLOrder .= $pStrTablename;
        $lStrSQLOrder .= " ";

        // SET
        $lStrSQLOrder .= " SET ".implode(", ",$pArrSet);
                
        // WHERE 
        $lArrWhereConditions = [];
        foreach ($pArrWhereCondition as $key => $value) {
            $lArrWhereConditions[] = $key." = '".$value."'";
        }
        $lStrSQLOrder .= " WHERE ".implode(" AND ",$lArrWhereConditions);
        
        return $lStrSQLOrder;
    }//end buildSQLUpdateOrder()

    /**
     * generateSQLDeleteOrderFromObj
     *
     * Génère l'ordre de suppression pour l'objet en cours.
     *
     * @param SimpleGenericDBObject $oCore Objet à mettre à jour en base de données.
     * @return string Requête SQL de mise à jour de l'objet.
     **/
    public static function generateSQLDeleteOrderFromObj(SimpleGenericDBObject $oCore):string
    {
      // DELETE
      $lStrSQLOrder  = "DELETE FROM ";
      $lStrSQLOrder .= $oCore->getTablename();
      
      // WHERE
      $lStrSQLOrder .= " WHERE tid='".$oCore->getFieldValue("tid")."'";

      return $lStrSQLOrder;
    }//end generateSQLDeleteOrderFromObj()

    /**
     * generateSQLSelectOrderFromObj
     *
     * Génère l'ordre SQL de sélection correspondant à l'objet en argument.
     *
     * @param SimpleGenericDBObject $oCore  Objet générique à "convertir"
     * @param array(mixed) $pArrKeyDefValues  Tableau des clés / valeurs à intégrer dans la clause WHERE
     * @return string
     * @static
     **/
    public static function generateSQLSelectOrderFromObj(SimpleGenericDBObject $oCore, $pArrKeyDefValues):string
    {
        // SELECT
        $lStrSQLOrder = "SELECT ";
        $lStrSQLOrder .= implode(
            ", ",
            array_keys($oCore->getFields())
        );
        
        // FROM
        $lStrSQLOrder .= " FROM ".$oCore->getTablename()." ";

        // WHERE 
        $lArrWhereConditions = [];
        foreach ($pArrKeyDefValues as $key => $value) {
            $lArrWhereConditions[] = $key." = '".$value."'";
        }
        $lStrSQLOrder .= " WHERE ".implode(" AND ",$lArrWhereConditions);

        return $lStrSQLOrder;
    }//end generateSQLSelectOrderFromObj()

    /**
     * buildSQLSelectOrder
     *
     * Génère l'ordre SQL de sélection selon les arguments.
     *
     * @param string $pStrTablename  Nom de la table
     * @param array(mixed) $pArrFieldnames  Tableau des nom de champs
     * @param array(mixed) $pArrKeyDefValues  Tableau des clés / valeurs à intégrer dans la clause WHERE
     * @return string
     * @static
     **/
    public static function buildSQLSelectOrder($pStrTablename,$pArrFieldnames ,$pArrWhereCondition):string
    {
        // SELECT
        $lStrSQLOrder = "SELECT ";
        $lStrSQLOrder .= implode(", ",$pArrFieldnames);
        
        // FROM
        $lStrSQLOrder .= " FROM ".$pStrTablename." ";

        // WHERE 
        if (!is_null($pArrWhereCondition)) {
            $lArrWhereConditions = [];
            foreach ($pArrWhereCondition as $key => $value) {
                $lArrWhereConditions[] = $key." = '".$value."'";
            }
            $lStrSQLOrder .= " WHERE ".implode(" AND ", $lArrWhereConditions);
        }
        return $lStrSQLOrder;
    }//end buildSQLSelectOrder()

    /**
     * generateSQLInsertOrderFromObj
     *
     * Génère l'ordre SQL d'insertion correspondant à l'objet en argument.
     *
     * @param SimpleGenericDBObject $oCore  Objet générique à "convertir"
     *
     * @return string Ordre SQL Insertion
     * @static
     **/
    public static function generateSQLInsertOrderFromObj(SimpleGenericDBObject $oCore):string
    {
        $lArrFieldNames  = [];
        $lArrFieldValues = [];
        foreach(\array_keys($oCore->getFields()) as $value ){
            if($oCore->isFieldNeedAnUpdate($value)){
                $lArrFieldNames[]=$value;
                $lArrFieldValues[]=$oCore->getFieldCurrentValue($value);
            }
        }

        // TODO EXCEPTION PAS DE CHAMPS 0 INSERER
        
        $lStrSQLOrder = "INSERT INTO  ".$oCore->getTablename(). " (";
        $lStrSQLOrder .= implode(", ",$lArrFieldNames);
        
        $lStrSQLOrder .= ") VALUES (";
        $lStrSQLOrder .= "'".implode("', '",$lArrFieldValues)."')";
        
        return $lStrSQLOrder;
    }//end generateSQLInsertOrderFromObj()


     /**
     * buildSQLInsertOrder
     *
     * Génère l'ordre SQL d'insertion depuis les arguments.
     *
     * @param string $pStrTablename  Nom de la table
     * @param array(mixed) $pArrFieldNames      Tableau des nom de champs
     * @param array(mixed) $pArrFieldvalues     Tableau des valeurs des champs
     * 
     * @return string
     * @static
     **/
    public static function buildSQLInsertOrder(string $pStrTablename,array $pArrFieldNames, array $pArrFieldvalues):string
    {
        $lStrSQLOrder = "INSERT INTO  ".$pStrTablename. " (";
        $lStrSQLOrder .= implode(", ",$pArrFieldNames);
        
        $lStrSQLOrder .= ") VALUES (";
        $lStrSQLOrder .= "'".implode("', '",$pArrFieldvalues)."')";
        
        return $lStrSQLOrder;
    }//end buildSQLInsertOrder()


 }//end class
 ?>
