<?php

namespace polux\CorePHP\Internal\Exceptions;

/**
 * Application Exception Classe definition
 *
 * @link app-exceptions.json
 */
class GenericApplicationException extends \Exception {

  private static $fileExceptionDefinitions = null;

  private $exceptionCode = null;

  private $exceptionMessage = null;

  private $exceptionParameters = [];


  /**
   * Default constructor
   *
   * @param string        $pStrAppExUniqueCode  Unique code of exceptions
   * @param array(mixed)  $pArrParameters       Values to inject into Exception message
   */
  public function __construct($pStrAppExUniqueCode,$pArrParameters=null)
  {
      $this->exceptionCode = $pStrAppExUniqueCode;
      $this->exceptionParameters = $pArrParameters;

      $lStrExceptionMessage = static::getExceptionMessageFromCode($pStrAppExUniqueCode);
      
      if(!is_null($lStrExceptionMessage))
      {
        if(!is_null($pArrParameters) && count($pArrParameters) > 0)
        {
          $this->exceptionMessage = vsprintf($lStrExceptionMessage,$pArrParameters);
        }
        else {
          $this->exceptionMessage = $lStrExceptionMessage;
        }
      }
      else {
        $this->exceptionMessage = sprintf(
          "An Exception with an unknow code '%s' was throwed ! (Parameters:'%s').",
          $pStrAppExUniqueCode,
          implode(', ',$pArrParameters)
        );
      }
      parent::__construct($this->exceptionMessage);
  }//end __construct()

  /**
   * Defines Exception Messages defintion file
   *
   * @param string $pStrJSONFilePath  Filepath of JSON Exception Messages defintion file.
   * @throws \Exception
   */
  static function setExceptionDefinitionFile($pStrJSONFilePath)
  {
    var_dump($pStrJSONFilePath);
     if(file_exists($pStrJSONFilePath))
     {
       self::$fileExceptionDefinitions = $pStrJSONFilePath;
     }
     else {
       throw new \Exception("FATAL ERROR - Internal Exception Messages definition file can't be load ! (file:'%s'). Please contact your administrator.");
     }
  }//end setExceptionDefinitionFile()

  /**
   * Returns Exception Message Definition
   *
   * @static
   * @param string $pStrExUID   Unique internal code of exception
   *
   * @return string NULL if not found.
   */
  static function getExceptionMessageFromCode($pStrExUID)
  {
    // Exception Messages file defined ?
    if(is_null(self::$fileExceptionDefinitions))
    {
      throw new \Exception("FATAL ERROR - Exception Messages file not defined !.");
    }

    $lArrMessages = json_decode(file_get_contents(self::$fileExceptionDefinitions),true);
    $lStrResult   = "";

    //echo ((Application::isDebugMode())?sprintf("DEBUG => Exception code reader '%s'.",$pStrExUID):"")."<BR/>";
    if(array_key_exists($pStrExUID,$lArrMessages))
    {
      $lStrResult = strval($lArrMessages[$pStrExUID]);
    }
    return ($lStrResult==""?null:$lStrResult);
  }//end getExceptionMessageFromCode()

}//end class

?>



