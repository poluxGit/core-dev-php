<?php

namespace polux\CorePHP\Database\Interfaces;

/**
 * Attributs des objets complexes
 */
trait ComplexObjectAttributes
{
    /**
     * Renvoi la version de l'objet.
     * 
     * @return string
     */
    public function getVersion():string 
    {
        return $this->getFieldValue('version');
    }//end getVersion()

    /**
     * Renvoi la révision de l'objet.
     * 
     * @return string
     */
    public function getRevision():string 
    {
        return $this->getFieldValue('revision');
    }//end getRevision()
 
}//end trait
