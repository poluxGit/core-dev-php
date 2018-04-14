<?php


/**
 * SettingsManager test case.
 */
use PHPUnit\Framework\TestCase;
use polux\CorePHP\Managers\SettingsManager;

class SettingsManagerTest extends TestCase
{

    /**
     *
     * @var SettingsManager
     */
    private $settingsManager;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
   
    /**
     * Tests SettingsManager::loadSettingsFromJsonFile()
     */
    public function testLoadSettingsFromJsonFile_LoadOK()
    {
        $lStrDir = dirname(__FILE__)."//../datasets/settings/app-settings_DS01-LoadOK.json";
        SettingsManager::loadSettingsFromJsonFile($lStrDir);
        
        $lArrayDB = SettingsManager::getAllDatabaseConnectionSettings();
        $this->assertGreaterThan(0, count($lArrayDB));
    }
    
    /**
     * Tests SettingsManager::loadSettingsFromJsonFile()
     * 
     * @expectedException        \Exception
     * @expectedExceptionMessage filepath
     */
    public function testLoadSettingsFromJsonFile_FileNotExists()
    {
        $lStrDir = dirname(__FILE__)."/../datasets/settings/no-file.json";
        SettingsManager::loadSettingsFromJsonFile($lStrDir);
    }
    
    /**
     * Tests SettingsManager::loadSettingsFromJsonFile()
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Loggers
     */
    public function testLoadSettingsFromJsonFile_LoadNOK_NoLoggersDefined()
    {
        $lStrDir = dirname(__FILE__)."/../datasets/settings/app-settings_DS02-LoadNOK_NoLoggersDefined.json";
        SettingsManager::loadSettingsFromJsonFile($lStrDir);
    }
    
    /**
     * Tests SettingsManager::loadSettingsFromJsonFile()
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage databases
     */
    public function testLoadSettingsFromJsonFile_LoadNOK_NoDBDefined()
    {
        $lStrDir = dirname(__FILE__)."/../datasets/settings/app-settings_DS02-LoadNOK_NoDBDefined.json";
        SettingsManager::loadSettingsFromJsonFile($lStrDir);
    }

    /**
     * Tests SettingsManager::getAllDatabaseConnectionSettings()
     */
    public function testGetAllDatabaseConnectionSettings()
    {
        SettingsManager::resetStaticObjects();
        
        $lStrDir = dirname(__FILE__)."/../datasets/settings/app-settings_DS01-LoadOK.json";
        SettingsManager::loadSettingsFromJsonFile($lStrDir);
        
        $lArrayDB = SettingsManager::getAllDatabaseConnectionSettings();
        $this->assertGreaterThan(0, count($lArrayDB));
    }

    /**
     * Tests SettingsManager::getAllLoggersSettings()
     */
    public function testGetAllLoggersSettings()
    {
        SettingsManager::resetStaticObjects();
        
        $lStrDir = dirname(__FILE__)."/../datasets/settings/app-settings_DS01-LoadOK.json";
        SettingsManager::loadSettingsFromJsonFile($lStrDir);
       
        $lArrayLoggers = SettingsManager::getAllLoggersSettings();
        $this->assertGreaterThan(0, count($lArrayLoggers));
    }

    /**
     * Tests SettingsManager::getJSONSubArray()
     */
    public function testGetJSONSubArray()
    {
        SettingsManager::resetStaticObjects();
        
        $lStrDir = dirname(__FILE__)."/../datasets/settings/app-settings_DS01-LoadOK.json";
        SettingsManager::loadSettingsFromJsonFile($lStrDir);
        
        $lArrAttr = SettingsManager::getJSONSubArray("application");
        
        $this->assertArrayHasKey("code", $lArrAttr);
        $this->assertArrayHasKey("title", $lArrAttr);
        $this->assertArrayHasKey("version", $lArrAttr);
        $this->assertArrayHasKey("description", $lArrAttr);
        
        
        
    }
}

