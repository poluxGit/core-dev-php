<?php

namespace polux\CorePHP\Managers;

/**
 * Gestionnaire centralisé des opérations sur système de fichier.
 *
 * @author poluxGit 
 */
use polux\CorePHP\Internal\Exceptions\FileSystemException;
use polux\CorePHP\Internal\Interfaces\IManager;
use polux\CorePHP\Internal\Exceptions\FileSystemException as FSException;

/**
 * Classe 'FileSystemManager'
 * 
 * Classe Statique de gestion des opérations sur système de fichier.
 * 
 */
class FileSystemManager implements IManager // extends AnotherClass implements Interface
{
    // ------------------------- STATIC ATTRIBUTES ------------------------------
    /**
     * Default directories separator
     * 
     * @static
     * @var string
     */
    protected static $defaultDirSeparator = '/';

    /**
     * Initialize manager
     *
     * @static
     */
    public static function initManager(){
        return null;
    }//end initManager()

    // ------------------------- STATIC METHODS ------------------------------
    /**
     * Return full path from parameters.
     * 
     * Concatenate parameters with directories separator checks.
     * 
     * @static
     * @param string $path      Absolute or relative path.
     * @param string $filename  Complete filename.
     * 
     * @return string   Full path of the file.
     */
    public static function getCompleteFilePath($path, $filename)
    {
        $strCompleteFilepath = $path;
        $regexpPattern = "\\".static::getDefaultDirSeparator().'/';
        
        if(!preg_grep($regexpPattern,$path))
        {
            $strCompleteFilepath .= static::getDefaultDirSeparator();
        }

        $strCompleteFilepath .=  $filename;
        
        return $strCompleteFilepath;
    }//end getCompleteFilePath()   

    /**
     * Checks file existance
     * 
     * Return TRUE if file in parameter exists, else FALSE
     * 
     * @static
     * @param string $filepath  Filepath to check.
     * 
     * @return boolean
     */
    public static function checkFileExistance($filepath)
    {
        return file_exists($filepath);
    }//end checkFileExistance()

    /**
     * Copy file from a source to a destination (file)
     * 
     * @static
     * @throws FileSystemException    En cas d'erreur lors de la copie de fichier.
     * 
     * @param string $inFilepath    Source Filepath to copy.
     * @param string $outFilePath   Destination Filepath.
     */
    public static function copyFileToFile($inFilepath,$outFilePath)
    {
        // TC001 => Source file must exists!
        if(!self::checkFileExistance($inFilepath))
        {
            $lArrExceptionParam = array(
                'in' => $inFilepath,
                'out' => $outDirectoryPath
            );
            throw new FSException('CORE-FS_FILECOPY-SOURCE_FILE_NOT_FOUNDED',$lArrExceptionParam);
        }

        // TC002 => Destination file  must not exists!
        if(self::checkFileExistance($outFilePath))
        {
            $lArrExceptionParam = array(
                'in' => $inFilepath,
                'out' => $outDirectoryPath
            );
            throw new FSException('CORE-FS_FILECOPY-DESTINATION_FILE_ALREADY_EXISTS',$lArrExceptionParam);
        }
        
         copy($inFilepath,$outDirectoryPath);

    }//end copyFileToFile()

    /**
     * Copy file from a source to a destination (directory)
     * 
     * Manage a file copy, by default directory is created if not exists.
     * 
     * @static
     * @throws FileSystemException    En cas d'erreur lors de la copie de fichier.
     * 
     * @param string $inFilepath    Source Filepath to copy.
     * @param string $outFilePath   Destination directory.
     * @param string $mode          Behaviour about destination directory (CREATE => Create Output directory if not exists | Others Values => Exception about Output directory in case does not exists).
     */
    public static function copyFileToDirectory($inFilepath, $outDirectoryPath, $mode="CREATE")
    {
        // TC001 => Source file must exists!
        if(!self::checkFileExistance($inFilepath))
        {
            $lArrExceptionParam = array(
                'in' => $inFilepath,
                'out' => $outDirectoryPath
            );
            throw new FSException('CORE-FS_FILECOPY-SOURCEFILE_NOT_FOUNDED',$lArrExceptionParam);
        }

        // TC002 => Directory doesn't exists, so create it!
        if(!is_dir($outDirectoryPath))
        {
            if ($mode == 'CREATE') {
                mkdir($outDirectoryPath);
            }
            else
                throw new FSException();
        }
        
        // Copy file to directory!
        copy($inFilepath,$outDirectoryPath);

    }//end copyFileToFile()

    // --------------------- STATIC GETTERS & SETTERS ---------------------------
    /**
     * Get default directories separator
     *
     * @static
     * @return  string
     */ 
    public static function getDefaultDirSeparator()
    {
        return static::$defaultDirSeparator;
    }//end getDefaultDirSeparator()

    /**
     * Set default directories separator
     *
     * @static
     * @param  string  $defaultDirSeparator  Default directories separator.
     */ 
    public static function setDefaultDirSeparator(string $defaultDirSeparator)
    {
        static::$defaultDirSeparator = $defaultDirSeparator;
    }//end setDefaultDirSeparator()

}//end class
