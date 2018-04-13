<?php
namespace polux\CorePHP\Managers;

use polux\CorePHP\Internal\Interfaces\IManager;
use polux\CorePHP\Internal\Logs\Logger;

/**
 * Log Manager Static Class Definition
 *
 * @author polux
 * @package     Core
 * @subpackage  Logs
 */
class LogsManager implements IManager {

  /**
   * Logs files Handler
   *
   * @var array(files handler)
   */
  private static $aLoggersHandler = [];

  /**
   * Default Output Path to store logs files
   * 
   * @var string
   */
  protected static $sOutputPath = "";

  public static function initManager()
  {
    return null;
  }

  /**
   * Renvoi l'objet Logger d'id demand�.
   * 
   * @static
   * @param string $idLogger  Identifiant du loggger sp�cifi�.
   * @throws \Exception Si l'id n'existe pas.
   * @return Logger
   */
  public static function getLogger($idLogger){
    if(!array_key_exists($idLogger,static::$aLoggersHandler)){
      throw new \Exception("Error Logger '$idLogger' not found!");
    }
    return static::$aLoggersHandler[$idLogger];
  }//end getLogger()

  /**
   * Ajout d'un nouveau Logger
   * 
   * @static
   * @param string $idLogger  Identifiant du loggger sp�cifi�.
   * @throws \Exception Si l'id n'existe pas.
   * @return Logger
   */
  public static function addLogger($idLogger,$filepath){
    if(array_key_exists($idLogger,static::$aLoggersHandler)){
      throw new \Exception(sprintf("Logger with ID '%s' already registered!",$idLogger));
    }
    self::$aLoggersHandler[$idLogger] = new Logger($idLogger,$filepath,true);
  }//end addLogger()

}//end class

?>