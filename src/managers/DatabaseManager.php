<?php

namespace polux\CorePHP\Managers;

use polux\CorePHP\Interfaces\IManager;
use polux\CorePHP\Logs\Logger;
use polux\CorePHP\Exceptions\DatabaseException;


/**
 * Gestionnaire des DH Handler PDO
 *
 * @package     Core
 * @subpackage  Database
 */

class DatabaseManager implements IManager {

  /**
   * Collection des objets PDO
   *
   * @var array(\PDO)
   */
  private static $aDBPdoDBHanlder = [];

  /**
   * Logger par défaut
   *
   * @var Logger
   */
  private static $objLogger = null;

  public static function initManager()
  {
    return null;
  }

  /**
   * Définie le Logger statique par défaut.
   * 
   * @param Logger $objetLogger Objet Logger
   */
  public static function setDefaultLogger(Logger $objetLogger)
  {
    self::$objLogger = $objetLogger;
  }//end setDefaultLogger()

  /**
   * queryDB => Renvoi le resultat de la requete passée en paramètre.
   * 
   * Execution des requetes de types SELECT
   * 
   * @internal execute la requete sur le DB Handler par DEFAUT
   * 
   * @static
   * 
   * @param string  $pStrSQLQuery   Requete à executer
   * @param \PDO    $pObjDBHandler  Database Handler Object.
   * 
   * @return array(mixed)   Résultat de la requete
   */
  public static function queryDB($pStrSQLQuery,\PDO $pObjDBHandler=null):array
  {
    if (! is_null(static::$objLogger)) {
        $lStrQuery = str_replace(CHR(13).CHR(10),'',$pStrSQLQuery);
        $lStrQuery = preg_replace("# +#", " ",$lStrQuery);
        static::$objLogger->logMessage("DatabaseManager::queryDB | SQL : '$lStrQuery'.");
    }
    $lObjDBHandler = $pObjDBHandler ?? self::getPDODatabaseHandler();
    try {
      // SQL Select Order Building and Execution!           
      $lObjSth = $lObjDBHandler->query($pStrSQLQuery, \PDO::CURSOR_FWDONLY);
     
      // Invalid PDOStatement
      if ($lObjSth === false) {
        $lArrParams = [
          $pStrSQLQuery,
          $lObjDBHandler->error_get_last()
        ];
        throw new DatabaseException('DB-GEN-QUERY_INVALID',$lArrParams);
      }

      // Fetch SQL result to object!
      $lArrResultObject = $lObjSth->fetchAll(\PDO::FETCH_ASSOC);
      return $lArrResultObject;
    }
    catch(\Exception $e)
    {
      throw new DatabaseException('DB-GEN-QUERY_ERROR',$e->getMessage());
    }        
  }//end queryDB()

  /**
   * queryDBStatement => Renvoi le PDOStatment après execution de la requête.
   * 
   * Execution des requetes de types SELECT
   * 
   * @internal execute la requete sur le DB Handler par DEFAUT
   * 
   * @static
   * 
   * @param string  $pStrSQLQuery   Requete à executer
   * @param \PDO    $pObjDBHandler  Database Handler Object.
   * 
   * @return \PDOStatement   Résultat de la requete
   */
  public static function queryDBStatement($pStrSQLQuery,\PDO $pObjDBHandler=null):\PDOStatement
  {
    $lObjDBHandler = $pObjDBHandler ?? self::getPDODatabaseHandler();
    try {
      // SQL Select Order Building and Execution!           
      $lObjSth = $lObjDBHandler->query($pStrSQLQuery, \PDO::CURSOR_FWDONLY);
      return $lObjSth;
    }
    catch(\Exception $e)
    {
      throw new DatabaseException('DB-GEN-QUERY_ERROR',$e->getMessage());
    }        
  }//end queryDBStatement()

  /**
   * execDB => Execuete la requete passée en paramètre.
   * 
   * Execution des requetes de types INSERT,UPDATE,DELETE
   * 
   * @static
   * @internal execute la requete sur le DB Handler par DEFAUT
   * 
   * @param string  $pStrSQLQuery   Requete à executer
   * @param \PDO    $pObjDBHandler  Database Handler Object.
   * @return int  Nombre d'enregistrements impactés
   */
  public static function execDB($pStrSQLQuery,\PDO $pObjDBHandler=null):int
  {
    try{
      $lObjDBHandler = $pObjDBHandler ?? self::getPDODatabaseHandler();
      // SQL Select Order Building and Execution!  
      $lIntNbRowsImpacted = $lObjDBHandler->exec($pStrSQLQuery);      
      return $lIntNbRowsImpacted;
    }
    catch(\Exception $e)
    {
      throw new DatabaseException('DB-GEN-EXEC_ERROR',$e->getMessage());
    }        
  }//end execDB()

  /**
   * registerDbPDODBHandler
   *
   * Register a new  PDO DB Handler
   * @static
   * @param \PDO    $pObjPDODBHandler   PDO DB Handler Object.
   * @param string  $dbID               UID of Database Connection
   */
  public static function registerDbPDOHandler(\PDO $pObjPDODBHandler,$dbID=null)
  {
    // TODO Check existence dans le tableau avant allocation!
    $ldbID = $dbID;
    if($dbID === null)
    {
      $ldbID = 'DEFAULT';
    }
    static::$aDBPdoDBHanlder[$ldbID] = $pObjPDODBHandler;
  }//end registerDbPDODBHandler()

  /**
   * initDatabaseHandler
   *
   * Returns  a PDO Database Handler initialized.
   * @static
   *
   */
  public static function initDatabaseHandler($dsn,$login,$pass,$dbID=null){
      $dbh = new \PDO($dsn,$login,$pass);

      if(!is_null($dbID))
      {
        static::registerDbPDOHandler($dbh,$dbID);
      }
      return $dbh;
  }//end initDatabaseHandler()

  /**
   * getPDODatabaseHandler
   *
   * Returns Specific  Database Handler Object
   * @static
   * @param string $dbInternalID  Internal ID of Database PDO Handler
   * @throws \Exception If DB not founded or null.
   *
   * @return \PDO   DB Handler Object
   */
  public static function getPDODatabaseHandler($dbInternalID='DEFAULT'){
    if(count(static::$aDBPdoDBHanlder) === 0 
        || !array_key_exists($dbInternalID,static::$aDBPdoDBHanlder) 
        || static::$aDBPdoDBHanlder[$dbInternalID] === null)
    {
      throw new \Exception(sprintf("DB Handler '%s' not founded or not initialized !.",$dbInternalID));
    }
    return static::$aDBPdoDBHanlder[$dbInternalID];
  }//end getSpecificPDODatabaseHandler()

}//end class

 ?>
