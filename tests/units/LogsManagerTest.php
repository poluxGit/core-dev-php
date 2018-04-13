<?php

/**
 * LogsManager test case.
 */
use PHPUnit\Framework\TestCase;
use polux\CorePHP\Managers\LogsManager;
use polux\CorePHP\Internal\Logs\Logger;

class LogsManagerTest extends TestCase
{
    /**
     * Log Path of loggers
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
        LogsManager::initManager();
        $this->logPath = dirname(__FILE__)."\\..\\logs\\";
        if(dir($this->logPath) === NULL)
        {
            mkdir($this->logPath);
        }
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
        
        // Vide le répertoire de logs
        if(dir($this->logPath) !== NULL)
        {
            
//             $files = array_diff(scandir($this->logPath), array('.','..'));
//             foreach ($files as $file) {
//                 (is_dir("$this->logPath/$file")) ? delTree("$this->logPath/$file") : unlink("$this->logPath/$file");
//             }
//             //rmdir($this->logPath);
        }
        
    }

  
    

    /**
     * Tests LogsManager::addLogger()
     */
    public function testAddLoggerAndGetThem()
    {
        LogsManager::addLogger('DEFAULT',dirname(__FILE__)."\\..\\logs\\");
        LogsManager::addLogger('LOGGER_1',dirname(__FILE__)."\\..\\logs\\");
        $this->assertEquals(1,1);
        $lObj = LogsManager::getLogger('LOGGER_1');
        $this->assertInstanceOf(Logger::class,$lObj);
        $lObj =  LogsManager::getLogger('DEFAULT');
        $this->assertInstanceOf(Logger::class,$lObj);
    }
}

