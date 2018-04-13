<?php

namespace polux\CorePHP\Managers;

/**
 * Gestionnaire centralisé des Exceptions applicatives.
 * 
 * @author poluxGit
 */
use polux\CorePHP\Internal\Interfaces\IManager;
use polux\CorePHP\Internal\Exceptions\GenericApplicationException;

/**
 * Classe 'ExceptionManager'
 * 
 * Classe Statique de gestion des Exceptions applicatives.
 * 
 */
class ExceptionManager implements IManager // extends AnotherClass implements Interface
{
    /**
     * Initialise le Manager d'exception
     *
     * @static
     */
    public static function initManager(){
        return null;
    }//end initManager()

    /**
     * Initialisation du fichier de définition des messages des exceptions
     * 
     * @param string $pStrFileJson  Path du fichier de configuration
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function initializeExceptionMessageFile($pStrFileJson)
    {
        GenericApplicationException::setExceptionDefinitionFile($pStrFileJson);
    }
}//end class
