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
use polux\CorePHP\Managers\SettingsManager as SettingsMng;
use polux\CorePHP\Managers\LogsManager as LogsMng;
use polux\CorePHP\Managers\DatabaseManager as DBMng;
use polux\CorePHP\Logs\Logger;

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
    
    /**
     * Description de l'application
     *
     * @var string
     */
    protected static $appDescription = null;
    
    /**
     * Version de l'application
     *
     * @var string
     */
    protected static $appVersion = null;
    
    // ------------------------- Abstract STATIC METHODS ----------------------
    /**
     * Initialization de l'application
     * 
     * @abstract
     */
    abstract  public static function initializeApplication(string $settingsFile);    
    
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
     * @throws \Exception   Fichier inexistant !
     * 
     * @return bool Return FALSE if trouble
     */
    protected static function loadApplicationSettings(string $settingsfile):bool
    {
        // Fichier inexistant !
        if(!file_exists($settingsfile))
        {
            $lStrMessage = sprintf(
                "ERREUR FATALE : Le fichier de paramètres de l'application n'as pu être trouvé ('%s').",
                $settingsfile
                );
            throw new \Exception($lStrMessage);
        }
        
        try {
            SettingsMng::loadSettingsFromJsonFile($settingsfile);
            
            // Chargement des informations sur l'application!
            $lArrAppInfo = SettingsMng::getJSONSubArray("application");
            
            // Titre de l'application
            static::$appTitle = (array_key_exists("title", $lArrAppInfo)?$lArrAppInfo["title"]:"Titre non défini");
            
            // Description de l'application
            static::$appDescription = (array_key_exists("description", $lArrAppInfo)?$lArrAppInfo["description"]:"Description non définie");
            
            // Code de l'application [MANDATORY]
            if(array_key_exists("code", $lArrAppInfo)){
                static::$appCode = $lArrAppInfo["code"];
            }
            else {
                throw new \Exception("Le code applicatif est obligatoire. Paramètre 'code' non trouvé.");
            }
            
            // Version de l'application
            static::$appVersion = (array_key_exists("version", $lArrAppInfo)?$lArrAppInfo["version"]:"Version non définie");
            
        }
        catch(\Exception $ex)
        {
            $lStrMessage = sprintf("Une erreur est survenu durant l'initialisation de l'application : %s.",$ex->getMessage());
            throw new \Exception($lStrMessage);    
        }
        
        return true;
    }//end loadApplicationSettings()
       
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
