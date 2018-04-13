<?php

namespace polux\CorePHP\Internal\Exceptions;

/**
 * Classe 'FileSystemException'
 * 
 * Exceptions relatives au système de fichier.
 */
class FileSystemException extends GenericApplicationException 
{
    /**
     * Constructeur par défaut
     * 
     * @param string $pStrCode  Code de l'erreur
     * @param string $pArrParam Valeur paramètrées de l'exception.
     * 
     */
    public function __construct($pStrCode,$pArrParam)
    {
        parent::__construct($pStrCode,$pArrParam);
    }//end __construct()
}//end class