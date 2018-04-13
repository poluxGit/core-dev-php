<?php

/**
 * Logger TestCase instance
 * 
 * @author polux
 */
use PHPUnit\Framework\TestCase;
use polux\CorePHP\Logs\Logger;

/**
 * LoggerTest Class
 * 
 * @author PoLuX
 */
class LoggerTest extends TestCase
{

    /**
     *
     * @var Logger
     */
    private $logger;
    
    /**
     * Log Path
     * 
     * @var string
     */
    private $logPath ;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->logPath = dirname(__FILE__)."\\..\\logs\\";        
        if(dir($this->logPath) === NULL)
        {
            mkdir($this->logPath);
        }
        $this->logger = new Logger("DEFAULT",$this->logPath);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->logger = null;    
        
        // Supprime le répertoire de logs
        if(dir($this->logPath) !== NULL)
        {
            
            $files = array_diff(scandir($this->logPath), array('.','..'));
            foreach ($files as $file) {
                (is_dir("$this->logPath/$file")) ? delTree("$this->logPath/$file") : unlink("$this->logPath/$file");
            }
            //rmdir($this->logPath);
        }
       
        parent::tearDown();
    }//end tearDown()


    /**
     * Tests Logger->logMessage()
     */
    public function testLogMessage_1()
    {
        $this->logger->logMessage("Message 'loggé'");        
        $lObjDir = dir($this->logPath);        
        $this->assertNotNull($lObjDir);
        $lObjSon = $lObjDir->read();
        closedir($lObjDir->handle);
        $this->assertTrue(!empty($lObjDir));
    }
}//end class

