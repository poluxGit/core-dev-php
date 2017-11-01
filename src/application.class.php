<?php

/**
 * Main Application Entry Point
 *
 */
namespace Core;

use Core\Settings\SettingsManager as SettingsMng;
use Core\Logs\LogsManager as LogsMng;
use Core\Database\DatabaseManager as DBMng;

require_once 'core.inc.php';

/**
 * Application Class
 */
class Application {

  protected static $_applicationSettingsFilepath = 'application.settings.json';

  /**
   * initApplication
   *
   * Initialization of application
   *
   * @static
   * @access public
   */
  public static function initApplication($filename=null)
  {
    // Settings file existance checks !
    $lfilename = $filename;
    if(is_null($filename)){
      $filename = static::$_applicationSettingsFilepath;
    }

    // Settings loading !
    SettingsMng::loadSettingsFromJsonFile($filename);

    // Loggers initilization !
    static::_initAllApplicationLoggers();
    static::getApplicationLogger()->logMessage('Application init - Loggers OK.');

    // Database handler init !
    static::_initAllDatabasesPDOHandler();
    static::getApplicationLogger()->logMessage('Application init - DBHandler(s) OK.');

    // TODO Chargement de modules tiers ... A Voir ?!
    static::getApplicationLogger()->logMessage('Application finished successfully!');
  }

  protected static function _initAllDatabasesPDOHandler()
  {
    $laDBInfos = SettingsMng::getAllDatabaseConnectionSettings();
    foreach($laDBInfos as $lsDbID => $laDBInfo){
      DBMng::initDatabaseHandler($laDBInfo['dsn'],$laDBInfo['login'],$laDBInfo['password'],$lsDbID);
    }
  }//end _initAllDatabasesPDOHandler()

  //  static::$_aLoggers[$laDBConn['id']] = new Logger($laDBConn['id'],'./../../logs',true);
  protected static function _initAllApplicationLoggers()
  {
    $laLoggerInfos = SettingsMng::getAllLoggersSettings();
    foreach($laLoggerInfos as $lsDbID => $laLoggerInfo){
      LogsMng::addLogger($lsDbID,$laLoggerInfo['filepath']);
    }
  }//end _initAllApplicationLoggers()


  public static function getApplicationDBHandler(){
    return static::getApplicationDBHandlerByInternalID('DEFAULT');
  }

  /**
   * Returns Tasks Database Instance PDO DB handler.
   * @return \PDO
   */
  public static function getTasksMngDBHandler(){
    return static::getApplicationDBHandlerByInternalID('TASKS');
  }

  public static function getApplicationDBHandlerByInternalID($dbInternalID){
    return DBMng::getPDODatabaseHandler($dbInternalID);
  }

  public static function getApplicationLogger(){
    return static::getApplicationLoggerByInternalID('LOG_APP');
  }

  public static function getApplicationLoggerByInternalID($idLogger){
    return LogsMng::getLogger($idLogger);
  }


}

?>
