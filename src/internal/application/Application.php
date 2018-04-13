<?php

namespace polux\CorePHP\Internal\Application;

/**
 * Application - Classe générique 'Application'.
 * 
 * Implémente tout les mécanismes 'globaux' à l'execution 
 * de l'application.
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

/**
 * Application 
 * 
 * Classe principale représentant une application métier/instance technique.
 * 
 */
class Application
{
    // ------------------------- STATIC ATTRIBUTES ----------------------------
    /**
     * Titre de l'application 
     * 
     * @var string
     * @static
     */
    protected static $applicationTitle = 'ApplicationTest - Nom à Définir';

    // ------------------------- STATIC METHODS ------------------------------
    /**
     * Initialisation du framework PHPCore - nécessaire à l'application
     *
     * Initialisation du framework : 
     * - Initialisation & Chargement du Dictionnaire interne au framework
     * - Initialisation du Gestionnaire d'exceptions
     * - ... TO DEV
     * 
     * @static
     * @access protected
     * 
     * @param string $appSetJSONFilepath  Settings to load - JSON file
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected static function initFramework()
    {
        // Load Framework internal dictionnary!
        $phpCoreDicFilePath = '/app/phpcore/internal/phpcore-dico.json';
        Dictionnary::loadJSONFileIntoDictionnary($phpCoreDicFilePath);
    }//end initFramework()

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
        SettingsMng::loadSettingsFromJsonFile($appSetJSONFilepath);

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
