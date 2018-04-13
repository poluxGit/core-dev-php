<?php

namespace polux\CorePHP\Internal\Database\Interfaces;

/**
 * Attributs communs aux objets
 */
trait CommonObjectAttributes
{
    /**
     * Renvoi le TID de l'objet.
     * 
     * @return string
     */
    public function getTID():string 
    {
        return $this->getFieldValue('tid');
    }//end getTID()

 
    /**
     * Renvoi le BID de l'objet.
     * 
     * @return string
     */
    public function getBID():string 
    {
        return $this->getFieldValue('bid');
    }//end getBID()

    /**
     * Définie le BID de l'objet.
     * 
     * @param string $value     Nouvelle valeur du BID
     */
    public function setBID($value):void 
    {
        $this->setFieldValue('bid',$value);
    }//end setBID()

    /**
     * Renvoi le Titre court de l'objet.
     * 
     * @return string
     */
    public function getShortTitle():string 
    {
        return $this->getFieldValue('stitle');
    }//end getShortTitle()

    /**
     * Définie le Titre court de l'objet.
     * 
     * @param string $value     Nouvelle valeur du Titre court
     */
    public function setShortTitle($value):void 
    {
        $this->setFieldValue('stitle',$value);
    }//end setShortTitle()

    /**
     * Renvoi le Titre long de l'objet.
     * 
     * @return string
     */
    public function getLongTitle():string 
    {
        return $this->getFieldValue('ltitle');
    }//end getLongTitle()

    /**
     * Définie le Titre long de l'objet.
     * 
     * @param string $value     Nouvelle valeur du Titre long
     */
    public function setLongTitle($value):void 
    {
        $this->setFieldValue('ltitle',$value);
    }//end setLongTitle()

    /**
     * Renvoi le Commentaire de l'objet.
     * 
     * @return string
     */
    public function getComment():string 
    {
        return $this->getFieldValue('comment');
    }//end getComment()

    /**
     * Définie le Commentaire de l'objet.
     * 
     * @param string $value     Nouvelle valeur du Commentaire
     */
    public function setComment($value):void 
    {
        $this->setFieldValue('comment',$value);
    }//end setComment()

    /**
     * Renvoi le Créateur de l'objet.
     * 
     * @return string   UID du compte utilisateur.
     */
    public function getCreator():string 
    {
        return $this->getFieldValue('cuser');
    }//end getCreator()

    /**
     * Renvoi le dernier updater de l'objet.
     * 
     * @return string   UID du compte utilisateur.
     */
    public function getUpdater():string 
    {
        return $this->getFieldValue('uuser');
    }//end getUpdater()

    /**
     * Renvoi la date de création de l'objet.
     * 
     * @return string  Date/Heure de création de l'objet
     */
    public function getCreationDate():string
    {
        return $this->getFieldValue('ctime');
    }//end getCreationDate()

    /**
     * Renvoi la date de dernière maj de l'objet.
     * 
     * @return string  Date/Heure de maj de l'objet
     */
    public function getLastUpdateDate():string
    {
        return $this->getFieldValue('utime');
    }//end getLastUpdateDate()

    /**
     * Renvoi le statut actif de l'objet
     * 
     * @return bool TRUE si l'objet est actif
     */
    public function isActive():bool
    {
        return ($this->getFieldValue('isActive')==1);
    }//end isActive()
    
}//end trait
