<?php

namespace polux\CorePHP\Internal\Exceptions;

/**
 * Classe 'CoreModelException'
 * 
 * Exceptions relatives au CoreModel
 */
class CoreModelException extends GenericApplicationException {
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