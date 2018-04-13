<?php
namespace polux\CorePHP\Business;

/**
 * Classe générée par BusinessModelManager
 * @author : @polux_fr
 * @date : {$smarty.now|date_format:'%d-%m-%Y %H:%M:%S'}
 */
use polux\CorePHP\Database\Generic\ComplexGenericDBObject;
use polux\CorePHP\Database\Model\AdditionalAttributeValue;
use polux\CorePHP\Database\Interfaces\CommonObjectAttributes;
use polux\CorePHP\Database\Interfaces\ComplexObjectAttributes;

/**
 * {$nomClasse}
 * 
 * Objet versionné - {$descriptionClasse}
 */
class {$nomClasse} extends ComplexGenericDBObject {
    
    use CommonObjectAttributes,ComplexObjectAttributes;

    /**
     * Constructeur par défaut
     **/
    public function __construct(){
        parent::__construct('{$classeTablename}');        
        $this->setKeys(['tid']);
    }
    
    // ---------------------------------------------------------------------------------
    //  Getters And Setters Spécifiques
    // ---------------------------------------------------------------------------------
    {foreach from=$addAttributes item=attr}
    
    /**
     * Renvoi la valeur l'attribut {$attr.stitle} de l'objet
     *
     * @return mixed Valeur Attribut
     **/    
    public function get{$attr.stitle}()
    {
        $lObjAttribute = new AdditionalAttributeValue($this->getFieldValue('tid'),"{$attr.tid}");
        return $lObjAttribute->getAttributeValue();
    }//end get{$attr.stitle}()

    /**
     * Définie la valeur l'attribut {$attr.stitle} de l'objet
     *
     * @return mixed Valeur Attribut
     **/    
    public function set{$attr.stitle}($value)
    {
        $lObjAttribute = new AdditionalAttributeValue($this->getFieldValue('tid'),"{$attr.tid}");
        $lObjAttribute->setAttributeValue($value);

    }//end set{$attr.stitle}()

    {/foreach}
    {if is_array($liensPere) && count($liensPere) > 0}
    
    // ---------------------------------------------------------------------------------
    // Gestions des liens vers d'autres objets...
    // ---------------------------------------------------------------------------------
    {/if}
    {foreach from=$liensPere item=lnkp}
    
    /**
     * Lien '{$lnkp.stitle}' - {$lnkp.bid}
     * 
     * {$lnkp.obj_src_nom} vers {$lnkp.obj_dst_nom} 
     * 
     **/

    /**
     * Renvoi un tableau de TID des {$lnkp.obj_dst_nom} lié à l'objet
     * i.e lien '{$lnkp.stitle}'
     * @return array
     **/
    public function getAllLinkedTo{$lnkp.obj_dst_nom}TID():array
    {
        return $this->getLinkedTIDFromObjectByLinkType('{$lnkp.tid}');
    }//end getAllLinked{$lnkp.obj_dst_nom}TID()

    /**
     * Ajout d'un nouveau lien {$lnkp.obj_src_nom} vers {$lnkp.obj_dst_nom} 
     * 
     * @param {$lnkp.obj_dst_nom} $pObj{$lnkp.obj_dst_nom}  {$lnkp.obj_dst_nom} à lier.
     * @return boolean      TRUE si le lien a pu être instancié
     **/
    public function addNewLinkTo{$lnkp.obj_dst_nom}($pStr{$lnkp.obj_dst_nom}TID):boolean
    {
        return $this->linkCurrentObjectToObject('{$lnkp.tid}',$pStr{$lnkp.obj_dst_nom}TID);
    }//end getAllLinked{$lnkp.obj_dst_nom}TID()
    {/foreach}
    {if is_array($liensFils) && count($liensFils) > 0}// ---------------------------------------------------------------------------------
    // Gestions des liens depuis d'autres objets...
    // ---------------------------------------------------------------------------------
    {/if}
{foreach from=$liensFils item=lnkf}

    /**
     * Lien '{$lnkf.stitle}' - {$lnkf.bid}
     * 
     * {$lnkf.obj_src_nom} vers {$lnkf.obj_dst_nom} 
     * 
     **/

    /**
     * Renvoi un tableau de TID des {$lnkf.obj_src_nom} lié à l'objet
     * i.e lien '{$lnkf.stitle}'
     * @return array
     **/
    public function getAllLinkedFrom{$lnkf.obj_src_nom}TID():array
    {
        return $this->getLinkedTIDToObjectByLinkType('{$lnkf.tid}');
    }//end getAllLinked{$lnkf.obj_src_nom}TID()
{/foreach}
    
}//end class