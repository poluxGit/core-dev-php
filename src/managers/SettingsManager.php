<?php
namespace polux\CorePHP\Managers;

/**
 * Settings Manager Static Class file definition
 *
 * @author polux
 * @package     Core
 * @subpackage  Settings
 */

/**
 * Settings Manager Static Class Definition
 * @static
 *
 */
class SettingsManager {

  /**
   * Databases connections settings
   * @var array(db settings)
   */
  private static $aDbConnections = [];

  /**
   * Loggers settings
   * @var array(Logger)
   */
  private static $aLoggers = [];
  
  /**
   * JSON Paramaters file content
   *  
   * @var string
   */
  private static $jsonSource = null;

  /**
   * loadSettingsFromJsonFile
   *
   * Load settings from a json file.
   * File existance checked.
   *
   * @param string $filepath Relative filepath to Json Settings  file to load.
   * @throws \Exception if file not exists
   * @throws \Exception if JSON DECODING FAILED
   * @throws \Exception if mandatory sub categories missed (databases,loggers)
   */
  public static function loadSettingsFromJsonFile($filepath){

    // File existance checks!
    if(!file_exists($filepath)){
      throw new \Exception(sprintf("Settings file can't be reached ! (filepath:'%s').",$filepath));
    }

    // File loading!
    $str = file_get_contents($filepath);  
    $json = json_decode($str, true);

    /**************************************************************************/
    /* Databases Settings Management                                          */
    /**************************************************************************/
    // Databases connections loading!
    if(!array_key_exists('databases',$json) || count($json['databases']) == 0)
    {
      throw new \Exception("No databases connections defined !");
    }

    // Adding Databases informations into static variable.
    foreach($json['databases'] as $laDBConn)
    {
      static::$aDbConnections[$laDBConn['id']] = $laDBConn;
    }

    /**************************************************************************/
    /* Logger Settings Management                                             */
    /**************************************************************************/
    // Databases connections loading!
    if(!array_key_exists('loggers',$json) || count($json['loggers']) == 0)
    {
      throw new \Exception("No Loggers defined !");
    }

    // Adding Databases informations into static variable.
    foreach($json['loggers'] as $laDBConn)
    {
      static::$aLoggers[$laDBConn['id']] = $laDBConn;
    }
  }//end loadSettingsFromJsonFile()

  /**
   * Returns all DB informations
   */
  public static function getAllDatabaseConnectionSettings()
  {
      return static::$aDbConnections;
  }//end getAllDatabaseConnectionSettings()

  /**
   * Returns all Loggers Settings
   */
  public static function getAllLoggersSettings()
  {
      return static::$aLoggers;
  }//end getAllLoggersSettings()
  
  /**
   * Return JSON Content as text 
   * 
   * @return string
   */
  protected static function getJSONContent()
  {
    return static::$jsonSource;    
  }//end getJSONContent()
  
  /**
   * Retourne un tableau de valeur de niveau 1 
   * 
   * @param string $subCategoryName     Nom de la catégorie de premier niveau du fichier JSON.
   * @throws \Exception
   */
  public static function getJSONSubArray($subCategoryName)
  {
      $lStrJSONSource = static::getJSONContent();
      // Pas de source JSON chargée ?
      if($lStrJSONSource == NULL)
      {
          throw new \Exception("No Settings loaded at this time !");
      }
        
      // Decodage JSON
      $json = json_decode($lStrJSONSource, true);
        
      // JSON decondig trouble ?
      if($json === NULL)
      {
        throw new \Exception("Application Settings can't be decoded (JSON decoding failed)!");
      }
      
      // Specific Category existance check!
      if(!array_key_exists($subCategoryName,$json) || count($json[$subCategoryName]) == 0)
      {
          $lsMessage = sprintf("JSON category '%' doesn't exists at level 1.",$subCategoryName);
          throw new \Exception($lsMessage);
      }
      
      return $json[$subCategoryName];
  }//end getJSONSubArray()

}//end class

?>
