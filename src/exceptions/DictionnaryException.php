<?php

namespace polux\CorePHP\Exceptions;

/**
 * Classe 'DictionnaryException'
 * 
 * Exceptions relatives au dictionnaire.
 */
class DictionnaryException extends GenericApplicationException 
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