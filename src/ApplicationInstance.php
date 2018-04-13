<?php

namespace polux\CorePHP;

/**
 * ApplicationInstance - Représente une application.
 * 
 * Initialisation et gestion des modules applicatifs : 
 * - Gestion de l'accès aux données
 * - Gestion des Exceptions d'execution
 * - Gestion et centralisation des opérations 
 * ...
 *
 * @author poluxGit
 */

// Dépendances locales...
use polux\CorePHP\Managers\SettingsManager as SettingsMng;
use polux\CorePHP\Managers\FileSystemManager as FSMng;
use polux\CorePHP\Managers\LogsManager as LogsMng;
use polux\CorePHP\Managers\DatabaseManager as DBMng;
use polux\CorePHP\Managers\ExceptionManager as ExceptMng;
use polux\CorePHP\Internal\Logs\Logger;
use polux\CorePHP\Internal\Exceptions\GenericApplicationException;

/**
 * ApplicationInstance 
 * 
 * Classe principale représentant une application métier/instance technique.
 * 
 */
abstract class ApplicationInstance  
{
    // ------------------------- STATIC ATTRIBUTES ----------------------------
    /**
     * Titre de l'application
     * 
     * @var string
     */
    protected static $appTitle = null;
    
    /**
     * Code de l'application
     *
     * @var string
     */
    protected static $appCode = null;
    
    /**
     * Répertoire racine de l'application
     * 
     * @var \Directory
     */
    protected static $appRootPath = null;
    
    // ------------------------- Abstract STATIC METHODS ----------------------
    /**
     * Initialization de l'application
     * 
     * @abstract
     */
    abstract public static function initializeApplication();    
    /**
     * Initialisation des modules additionnels
     * 
     * @abstract
     */
    abstract public static function initializeAdditionalModules();
    
    // ------------------------- STATIC METHODS ------------------------------
    /**
     * Chargement des paramètres applicatifs 
     * 
     * @param string $settingsfile  Fichier de paramètres au format JSON.
     * @throws GenericApplicationException
     * 
     * @return bool Return FALSE if trouble
     */
    protected static function loadApplicationSettings(string $settingsfile):bool
    {
        try {
            SettingsMng::loadSettingsFromJsonFile($settingsfile);
//             // Load Framework internal dictionnary!
//             $phpCoreDicFilePath = '/app/phpcore/internal/phpcore-dico.json';
//             Dictionnary::loadJSONFileIntoDictionnary($settingsfile);
        }
        catch(\Exception $ex)
        {
            throw new GenericApplicationException("toto");    
        }
        
        return true;
    }//end loadApplicationSettings()
    
   

    /**
     * initApplication - Initialisation de l'application
     *
     * @static
     * @access protected
     * 
     * @param string $appSetJSONFilepath  Settings to load - JSON file
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected static function initApplication($appSetJSONFilepath)
    {
        // Settings file existance checks !
        FSMng::checkFileExistance($appSetJSONFilepath);

        // Load internal Dictionnary!
       
        // Settings loading !
        

        // Loggers initilization !
        static::initAllApplicationLoggers();
        static::getApplicationLogger()->logMessage('Application init - Loggers OK.');

        // Database handler init !
        static::initAllDatabasesPDOHandler();
        static::getApplicationLogger()->logMessage('Application init - DBHandler(s) OK.');
        
        // Exception Manager init ! 
        ExceptMng::initializeExceptionMessageFile("/app/settings/exceptions-messages.json");
        static::getApplicationLogger()->logMessage('Application init - Exception Manager(s) OK.');
        
        // TODO Chargement de modules tiers ... A Voir ?!
        static::getApplicationLogger()->logMessage('Application finished successfully!');
    }//end initApplication()

    /**
     * Initialisation des Handlers de DB
     *
     * @static
     * @access protected
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected static function initAllDatabasesPDOHandler()
    {
        $laDBInfos = SettingsMng::getAllDatabaseConnectionSettings();
        foreach ($laDBInfos as $lsDbID => $laDBInfo) {
            DBMng::initDatabaseHandler($laDBInfo['dsn'], $laDBInfo['login'], $laDBInfo['password'], $lsDbID);
        }
        DBMng::setDefaultLogger(static::getApplicationLoggerByInternalID('LOG_DB-SQL'));
    }//end initAllDatabasesPDOHandler()

    /**
     * Initialisation des Loggers applicatifs
     *
     * @static
     * @access protected
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected static function initAllApplicationLoggers()
    {
        $laLoggerInfos = SettingsMng::getAllLoggersSettings();
        foreach ($laLoggerInfos as $lsDbID => $laLoggerInfo) {
            LogsMng::addLogger($lsDbID, $laLoggerInfo['filepath']);
        }
    }//end initAllApplicationLoggers()

    /**
     * Retourne le principal DB Handler de l'applicatiion
     *
     * @internal DH Handlers nommé DEFAULT
     * @static
     * @return \PDO
     */
    public static function getApplicationDBHandler()
    {
        return static::getApplicationDBHandlerByInternalID('DEFAULT');
    }

    /**
     * Retourne le  DB Handler par son identifiant
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @static
     * @param string $dbInternalID  Identifiant unique du DB Hanlder.
     * @return \PDO
     */
    public static function getApplicationDBHandlerByInternalID($dbInternalID)
    {
        return DBMng::getPDODatabaseHandler($dbInternalID);
    }

    /**
     * Retourne le principal Logger de l'applicatiion
     *
     * @internal Logger nommé LOG_APP
     * @static
     * @return Logger
     */
    public static function getApplicationLogger()
    {
        return static::getApplicationLoggerByInternalID('LOG_APP');
    }//end getApplicationLogger();

    /**
     * Retourne le  Logger par son identifiant
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @static
     * @param string $idLogger  Identifiant unique du Logger.
     * @return Logger
     */
    public static function getApplicationLoggerByInternalID($idLogger)
    {
        return LogsMng::getLogger($idLogger);
    }//end getApplicationLoggerByInternalID()
}//end class
