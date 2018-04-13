<?php
namespace polux\CorePHP\Internal\Database\Generic;

/**
 * SpecificDBObject
 *
 * Objet spécifique des objets en BD de l'application
 * - Pas de définition de champs partagés
 */
abstract class SpecificDBObject extends DatabaseObject
{
    /**
     * Constructeur par défaut
     *
     * Initialise l'objet courant et lance le chargement de l'objet.
     *
     * @param string        $tablename          Nom de la table des objets de la classe.
     * @param array(string) $aLoadKeysValues    Tableau clé - valeur de l'objet à charger. (Optionel)
     *
     * @return self
     **/
    public function __construct($tablename, $aLoadKeysValues=null)
    {
        $this->setTablename($tablename);
        $this->initFields();

        // Chargement de l'objet !
        if (!is_null($aLoadKeysValues)) {
            $this->loadObject($aLoadKeysValues);
        }
    }//end __construct()

    // ---------------------------------------------------------------------------
    // Méthodes Abstraites - ABSTRACT
    // ---------------------------------------------------------------------------

    /**
     * Initialisation des attributs spécifiques à la classe
     * 
     * @internal $this->addNewField($fieldname);
     **/
    abstract protected function initFields();
    
}//end class
