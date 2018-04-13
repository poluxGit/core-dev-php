<?php

/**
 * Logger Class Definition file
 * 
 * @package Core
 * @subpackage Logs
 */

namespace polux\CorePHP\Logs;

/**
 * Logger Class Definition
 */
class Logger {

    /**
     * Internal ID
     * @var string
     */
    protected $loggerID = null;

    /**
     * Filename where message have to be written
     * @var string
     */
    protected $filename = null;

    /**
     * Filepath of the file
     * @var string
     */
    protected $filepath = null;

    /**
     * File Handler
     * @var resource
     */
    private $filehandler = null;

    /**
     * Default Constructor of a Logger Object
     *
     * @throws \Exception If file open failed
     * @return Logger
     */
    public function __construct($loggerID,$outputpath=null,$dateprefix=null)
    {
      $this->loggerID = $loggerID;

      if(!is_null($outputpath))
      {
        $this->filepath = $outputpath;
      }
     
      if(is_null($dateprefix))
      {
        $dateprefix = true;
      }

      $this->updateFilenameCompletePath($dateprefix);

      try{
        $this->filehandler = fopen($this->filename,'a');
      }
      catch(\Exception $ex){
        throw new \Exception("An error occured during Logger initialization ! : "+$ex->getMessage());
      }
    }//end __construct()

    /**
     * Log a message into Log Handler
     * 
     * @param string $message
     * @throws \Exception
     */
    public function logMessage($message)
    {
      if(is_null($this->filehandler))
      {
        throw new \Exception("Message can't be log because the logger isn't correctly initiliazed.");
      }

      fwrite($this->filehandler,$this->getMessageFormatedToLog($message));
    }//end logMessage()

    /**
     * Return a formatted message
     * 
     * @param string $message
     * @return string
     */
    protected function getMessageFormatedToLog($message)
    {
      return '[ '.date('Ymd-H:i:s').' ] - '.$message.PHP_EOL;
    }

    /**
     * updateFilenameCompletePath
     *
     * Update complete filename to used.
     *
     * @param boolean $dateprefix if TRUE - DateHourMinute will prefix the fiename returs.
     *
     */
    private function updateFilenameCompletePath($dateprefix)
    {
      $lFilename =  $this->filepath.'/';
      if($dateprefix==true)
      {
        $lFilename .= date('Ymd-H')."_";
      }
      $lFilename .=  strtolower($this->loggerID).'.log';
      $this->filename = $lFilename;
    }//end updateFilenameCompletePath()


    /**
     * Destructor of a Logger Object
     */
    public function __destruct(){
      if(!is_null($this->filehandler))
      {
        fclose($this->filehandler);
      }
    }//end __construct()

}//end class
?>
