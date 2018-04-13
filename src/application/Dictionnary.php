<?php

namespace polux\CorePHP\Application;

/**
 * Gestion centralisée des messages applicatifs
 * 
 * @author poluxGit
 */
use polux\CorePHP\Exceptions\DictionnaryException as DicoException;
use polux\CorePHP\Managers\FileSystemManager as FSManager;
use polux\CorePHP\Exceptions\DictionnaryException;

/**
 * Classe 'Dictionnary'
 * 
 * Classe principale de gestion et d'accès 
 * aux messages applicatifs paramètrés.
 * 
 */
class Dictionnary
{

    // ------------------------- STATIC ATTRIBUTES ----------------------------
    /**
     * Array of files currently loaded into memory
     * 
     * @static
     * @var array(string)   Array of filename.
     */
    protected static $filesLoaded = array();

    /**
     * Static Dictionnary attributes.
     * 
     * @static
     * @var array(mixed)   Dictionnary attribute values.
     */
    protected static $dictionnaryEntries = array();

    // ------------------------- STATIC METHODS --------------------------------    
    /**
     * Load a json file into dictionnary.
     * 
     * @param string $dicoFilepath  JSON file to load into dictionnary.
     * 
     */
    public static function loadJSONFileIntoDictionnary($dicoFilepath)
    {
        static::loadJSONFileIntoArray($dicoFilepath,static::$dictionnaryEntries);
        array_push(static::$filesLoaded,$dicoFilepath);
    } //end loadJSONFileIntoDictionnary()

    /**
     * Load a json file into array in parameters.
     * 
     * @access protected
     * @throws DictionnaryException   If file to load doesn't exists!
     * @throws DictionnaryException   If json file can't be loaded!
     * 
     * @param string $jsonFilepath  JSON file to load.
     * @param string $outputArray   Output Array.
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     * 
     */
    protected static function loadJSONFileIntoArray($jsonFilepath,&$outputArray)
    {
        // TC001 => Dico file must exists!
        //print_r($jsonFilepath);
        
        if(!FSManager::checkFileExistance($jsonFilepath))
        {
            echo "coucou";
            $lArrExceptionParam = array(
                'in' => $jsonFilepath
            );
            throw new DicoException('CORE-DICO_LOAD-SOURCE_FILE_NOT_FOUNDED',$lArrExceptionParam);
        }

        // JSON Format check!
        $jsonfileContent = file_get_contents($jsonFilepath);        
        $lArrDicoEntries = json_decode($jsonfileContent,true);

        // TC002 => Dico content must be well formed and fully loadable!
        if(is_null($lArrDicoEntries))
        {
            $lArrExceptionParam = array(
                'in'        => $jsonFilepath,
                'details'   => json_last_error()
            );
            throw new DicoException('CORE-DICO_LOAD-SOURCE_FILE_JSON_ERROR',$lArrExceptionParam);
        }
        
        // Merging new values into outpuArray!
        $outputArray = array_merge($outputArray,$lArrDicoEntries);
    } //end loadJSONFileIntoArray()

    /**
     * Check existance of a key into  static and shared Dictionnary.
     * 
     * Return TRUE if key exists into dictionnary.
     * 
     * @static
     * @return boolean
     */
    public static function isDictionnaryEntryKeyExists()
    {
        return array_key_exists(static::$dictionnaryEntries);
    }//end isDictionnaryEntryKeyExists()

    /**
     * Get value of an entry into dictionnary.
     * 
     * Return NULL if entry key does not exists.
     * 
     * @static
     * @param string $entryKey  Key of entry.
     * 
     * @return mixed    Entry value. NULL if not founded.
     */
    public static function getDictionnaryEntryValue($entryKey)
    {
        return (static::isDictionnaryEntryKeyExists($entryKey))?static::$dictionnaryEntries[$entryKey]:null;
    }//end getDictionnaryEntryValue()

    /**
     * Get array of filename.
     *
     * @return  array(string)
     */ 
    public static function getFilesLoaded()
    {
        return static::$filesLoaded;
    }//end getFilesLoaded()

    /**
     * Reset static dictionnary values.
     *
     * @static
     * @access protected
     */ 
    protected static function resetDictionnary()
    {
        static::$filesLoaded        = array();
        static::$dictionnaryEntries = array();
    }//end resetDictionnary()

}//end class