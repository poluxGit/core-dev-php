<?php

namespace polux\CorePHP\Exceptions;

/**
 * Database Exception
 * 
 * Exceptions relatives à la base de données
 */
class DatabaseException extends GenericApplicationException {

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

 ?>
