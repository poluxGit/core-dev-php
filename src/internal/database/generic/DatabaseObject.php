<?php
namespace polux\CorePHP\Internal\Database\Generic;

use polux\CorePHP\Internal\Database\Technical\SQLQueryBuilder;
use polux\CorePHP\Internal\Database\Interfaces\IDatabaseObject;
use polux\CorePHP\Internal\Exceptions\DatabaseException;

use polux\CorePHP\Managers\DatabaseManager;

/**
 * DatabaseObject
 * 
 * Abstraction des objets persistés
 * @abstract
 * 
 * @todo Ajouter la gestion des attributs complementaire dans le processus de mise à jour / création 
 * 
 */
abstract class DatabaseObject implements IDatabaseObject
{
    /**
     * Clé(s) de la table
     * 
     * @var array(string)
     * @access private
     */
    private $keys = [];
    
     /**
     * Valeur des clés pour l'objet courant
     * 
     * @var array(string)
     * @access private
     */
    private $keysValues = [];
    /**
     * Nom de la table
     * 
     * @var string
     * @access private
     */
    private $tablename = null;
    /**
     * Attributs de l'objet
     * 
     * @var array(string)
     * @access private
     */
    private $fields = [];
    /**
     * Valeurs des attributs de l'objet
     * 
     * @var array(string=>string)
     * @access private
     */
    private $fieldsValues = [];
    /**
     * Valeurs "courantes" des attributs de l'objet
     * 
     * @var array(string=>string)
     * @access private
     */
    private $fieldsCurrentValues = [];   

    /**
     * Constructeur par défaut 
     *
     * @param string $tablename     Nom de la table
     **/
    public function __construct($tablename)
    {
        $this->setTablename($tablename);
    }

    // ------------------------------------------------------------------
    // Getters & Setters 
    // ------------------------------------------------------------------
    
    /**
     * Get nom de la table
     *
     * @return  string
     */ 
    public function getTablename(){
        return $this->tablename;
    }//end getTablename()

    /**
     * Set nom de la table
     *
     * @param  string  $tablename  Nom de la table
     *
     */ 
    public function setTablename(string $tablename){
        $this->tablename = $tablename;
    }//end setTablename()

    /**
     * Get attributs de l'objet
     *
     * @return  array(string)
     */ 
    public function getFields(){
        return $this->fields;
    }//end getFields()

    /**
     * addNewField
     * 
     * Ajoute un nouvel attribut à l'objet
     * 
     * @throws \Exception Si l'attribut est déjà défini
     * 
     * @param  string        $fieldname         Attributs de l'objet
     * @param  array(mixed)  $aFieldDefinition  Paramètre de l'attribut
     *
     */ 
    public function addNewField($fieldname,$aFieldDefinition=null){
        if($this->isFieldDefined($fieldname))
        {
            throw new \Exception(get_class(self).": '$fieldname' Attribut déjà défini!");
        }
        $this->fields[$fieldname]               = $aFieldDefinition; 
        $this->fieldsValues[$fieldname]         = null;
        $this->fieldsCurrentValues[$fieldname]  = null;       
    }//end addNewField()

    /**
     * isFieldDefined
     * 
     * Retourne TRUE si l'attribut est défini.
     * 
     * @param string    $fieldname          Nom de l'attribut
     * @param boolean   $bThrowsException   Si défini à TRUE, lance un exception si l'attribut n'est pas défini (Optionel)
     * @return boolean  True si l'attribut est défini, false si il n'existe pas
     */
    public function isFieldDefined($fieldname)
    {
        return array_key_exists($fieldname,$this->fields);
        
    }//end isFieldDefined()

    /**
     * doesFieldHaveInitialValue
     *
     * Retourne TRUE si l'attribut possède une valeur "initiale"
     * i.e : chargée depuis la base ou non enregistrée (cas de création)
     *
     * @param string $fieldname     Nom de l'attribut
     * @return boolean
     **/
    protected function doesFieldHaveInitialValue($fieldname)
    {
        return array_key_exists($fieldname,$this->fieldsValues);
    }//end doesFieldHaveInitialValue()

    /**
     * doesFieldHaveCurrentValue
     *
     * Retourne TRUE si l'attribut possède une valeur "courante"
     * i.e : Mise à jour mais non enregistrée en base
     *
     * @param string $fieldname     Nom de l'attribut
     * @return boolean
     **/
    protected function doesFieldHaveCurrentValue($fieldname)
    {
        return array_key_exists($fieldname,$this->fieldsValues);
    }//end doesFieldHaveCurrentValue()

    /**
     * getFieldInitialValue
     * 
     * Renvoi la valeur "initiale" de l'attribut.   
     * @throws \Exception Si l'attribut n'est pas défini
     * 
     * @param   string   $fieldname  Attributs de l'objet
     * @return  mixed    Valeur "initiale" de l'attribut (null si non trouvée)
     */ 
    protected function getFieldInitialValue($fieldname)
    {
        $this->isFieldDefined($fieldname,true);        
        return array_key_exists($fieldname,$this->fieldsValues)?$this->fieldsValues[$fieldname]:null;
    }//end getFieldInitialValue()

    /**
     * getFieldCurrentValue
     * 
     * Renvoi la valeur "courante" de l'attribut.   
     * @throws \Exception Si l'attribut n'est pas défini
     * 
     * @param   string  $fieldname  Attributs de l'objet
     * @return  mixed   Valeur de l'attribut
     */ 
    public function getFieldCurrentValue($fieldname)
    {
        $this->isFieldDefined($fieldname,true);        
        return array_key_exists($fieldname,$this->fieldsCurrentValues)?$this->fieldsCurrentValues[$fieldname]:null;
        
    }//end getFieldCurrentValue()

    /**
     * getFieldValue
     * 
     * Renvoi la valeur de l'attribut.   
     * @throws \Exception Si l'attribut n'est pas défini
     * 
     * @param   string  $fieldname  Attributs de l'objet
     * @return  mixed   Valeur de l'attribut (null si non trouvé)
     */ 
    public function getFieldValue($fieldname):string
    {
        $this->isFieldDefined($fieldname,true);        
        if($this->doesFieldHaveCurrentValue($fieldname)){   return $this->getFieldCurrentValue($fieldname); } 
        elseif ($this->doesFieldHaveInitialValue($fieldname)) { return $this->getFieldInitialValue($fieldname); }
         return null; 
        
    }//end getFieldValue()

    /**
     * setFieldValue
     * 
     * Définie la valeur d'un attribut déclaré.
     * 
     * @throws \Exception Si l'attribut n'est pas déclaré.
     * @param  string  $fieldname   Nom de l'attribut
     * @param  mixed   $mixedValue  Vameur de l'attribut
     */ 
    public function setFieldValue($fieldname,$mixedValue)
    {
        $this->fieldsCurrentValues[$fieldname] = $mixedValue;
    }//end setFieldValue()

    /**
     * isFieldNeedAnUpdate
     * 
     * Retourne TRUE si l'attribut nécessite une mise à jour
     * 
     * @param string    $fieldname     Nom de l'attribut
     * 
     * @return boolean TRUE si l'attribut nécessite une mise à jour
     */
    public function isFieldNeedAnUpdate($fieldname)
    {
        if($this->doesFieldHaveInitialValue($fieldname) && $this->doesFieldHaveCurrentValue($fieldname)) {
            return $this->getFieldInitialValue($fieldname)!=$this->getFieldCurrentValue($fieldname);
        }
        elseif (!$this->doesFieldHaveInitialValue($fieldname) && $this->doesFieldHaveCurrentValue($fieldname)) {
            return true;
        }
        else
            return false;
    }//end isFieldNeedAnUpdate()

    /**
     * isObjectNeedAnUpdate
     * 
     * Retourne TRUE si au moins un attribut 
     * nécessite un enregistrement en base.
     * 
     * @return boolean
     */
    public function isObjectNeedAnUpdate()
    {
        $bResultat = false;
        foreach ($this->getFields() as $fieldname ) {
            if($bResultat){ break;}
            $bResultat = $this->isFieldNeedAnUpdate($fieldname);
        }

        return $bResultat;
    }//end isObjectNeedAnUpdate()

    /**
     * resetInternalFieldsValues
     *
     * Undocumented function long description
     **/
    protected function resetInternalFieldsValues()
    {
        $this->fieldsCurrentValues = [];
        $this->fieldsValues = [];

        foreach($this->getFields() as $fieldname )
        {
            $this->fieldsCurrentValues[$fieldname] = null;
            $this->fieldsValues[$fieldname] = null;
        }
    }//end resetInternalFieldsValues()   

    /**
     * Get clé(s) de la table
     *
     * @return  array(string)
     */ 
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Set clé(s) de la table
     *
     * @param  array(string)  $keys  Clé(s) de la table
     */ 
    public function setKeys($keys)
    {
        $this->keys = $keys;
    }

    /**
     * Get valeur des clés pour l'objet courant
     *
     * @return  array(string)
     */ 
    public function getKeysValues()
    {
        return $this->keysValues;
    }//end getKeysValues()

    /**
     * Set valeur des clés pour l'objet courant
     *
     * @param  array(string)  $keysValues  Valeur des clés pour l'objet courant
     *
     * @return  self
     */ 
    public function setKeysValues($key,$value)
    {
        $this->keysValues[$key] = $value;
    }//end setKeysValues()
 
    /**
     * Chargement de l'objet depuis la base de données 
     * 
     * @param array  $arrKeyValues   Tableaux de clés/valeurs de l'objet à charger.
     * @throws DatabaseException 
     * @return boolean TRUE si OK, FALSE SI non trouvé
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function loadObject($arrKeyValues,\PDO $pObjDBHandler=null)
    {
        try {
             // SQL Select Order Building and Execution!           
            $lStrSQLSelect = SQLQueryBuilder::generateSQLSelectOrderFromObj($this,$arrKeyValues);
            $lObjSth = DatabaseManager::queryDBStatement($lStrSQLSelect, $pObjDBHandler);

            if($lObjSth === false)
            {
                $lArrParam = [
                    $lStrSQLSelect
                ];
                throw new DatabaseException('DB-LOADOBJECT_ERROR',$lArrParam);
            }

            if($lObjSth->rowcount()!=1)
            {
                $lArrParam = [
                    $lObjSth->rowcount(),
                    $lStrSQLSelect
                ];
                throw new DatabaseException('DB-LOADOBJECT_ROWCOUNT-INVALID',$lArrParam);
            }           
            
            $lIntIDXField = 1;
            $this->resetInternalFieldsValues();
     
            foreach (array_keys($this->getFields()) as $lStrFieldname) {
                $lObjSth->bindcolumn($lIntIDXField, $this->fieldsValues[$lStrFieldname]);
                $lIntIDXField++;
            }     
            // Fetch SQL result to object!
            $lArrResultObject = $lObjSth->fetch(\PDO::FETCH_BOUND);
            
            return $lArrResultObject;
        }
        catch(\Exception $e)
        {            
            throw new \Exception($e->getMessage());
        }        
    }//end loadObject()

    /**
     * Creation de l'objet en base de données sur l'objet courant.
     * 
     * @param \PDO  $pObjDBHandler   (Optione) DB Handler
     * @return boolean TRUE si OK, FALSE SI non nécessaire, Exceptions sinon
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function createObject(\PDO $pObjDBHandler=null)
    {
        try{
            // SQL Select Order Building and Execution!           
            $lStrSQLSelect = SQLQueryBuilder::generateSQLInsertOrderFromObj($this);
            $lIntNbRowsImpacted = DatabaseManager::execDB($lStrSQLSelect, $pObjDBHandler);            
            // 1 row returned ?
            // => CREATION OK 
            if ($lIntNbRowsImpacted == 1) {
                $lStrSQLTIDQuery = "SELECT MAX(tid) as tidm FROM ".$this->tablename;
                $lArrResults = DatabaseManager::queryDB($lStrSQLTIDQuery, $pObjDBHandler);
                $lStrTID = null;
                
                if( count($lArrResults) > 0 )
                {
                    $lStrTID = array_shift($lArrResults)['tidm'];
                }

                if(is_null($lStrTID))
                {
                    throw new DatabaseException("DB-CREATE-OBJET_ERROR",null);
                }
                $this->loadObject(["tid"=>$lStrTID],$pObjDBHandler);
            }
            elseif ($lIntNbRowsImpacted != 1){
                throw new \Exception(sprintf("Errors during Obj. Creation into DB : %s (query:'%s').",implode(" ",$pObjDBHandler->errorInfo()),$lStrSQLSelect));  
            }
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }        
    } //end createObject()

    /**
     * saveObject
     * 
     * Enregistre les mises à jours sur l'objet courant.
     * 
     * @param \PDO  $pObjDBHandler   (Optionel) DB Handler. 
    
     * @return boolean TRUE si OK, FALSE SI non nécessaire, Exceptions sinon
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function saveObject(\PDO $pObjDBHandler=null):bool
    {
        try {
            // Cas Création <=> TID == null
            if(is_null($this->getFieldValue("tid")))
            {
                // MODE CREATION
                $this->createObject($pObjDBHandler);
            }
            elseif($this->isObjectNeedAnUpdate()) 
            {
                // Mode UDPATE
                $lStrSQLUpdate = SQLQueryBuilder::generateSQLUpdateOrderFromObj($this);
                $lIntNbRowsImpacted = DatabaseManager::execDB($lStrSQLUpdate, $pObjDBHandler);  
            
                // 1 row returned ?
                // => CREATION OK 
                if ($lIntNbRowsImpacted == 1) {
                    $lArrKey = ["tid" => $this->getFieldValue("tid")];
                    $this->loadObject($lArrKey,$pObjDBHandler);
                }
                elseif ($lIntNbRowsImpacted != 1){
                    throw new \Exception(sprintf("Errors during Obj. Creation into DB : %s (query:'%s').",implode(" ",$pObjDBHandler->errorInfo()),$lStrSQLUpdate));  
                }                
            }
            return true;
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }        
    }//end saveObject()

    /**
     * Retourne tous les objets
     *
     * Retourne un tableau avec les informations de tous les objets
     *
     * @param array $pArrWhereConditions    Tableau contenant les conditions SQL (séparées par des AND)
     * @param \PDO  $pObjDBHandler          (Optionel) DB Handler
     * 
     * @return array    Tous les champs de tous les objets
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     **/
    public function getAllData($pArrWhereConditions=null,\PDO $pObjDBHandler=null):array
    {
        try {
            // SQL Query Building!   
            $lArrWhereConditions = $pArrWhereConditions ?? ["isActive = 1"];
            $lStrSQLQueryAll = SQLQueryBuilder::buildSQLSelectOrder($this->getTablename(), array_keys($this->getFields()), $lArrWhereConditions);
            return DatabaseManager::queryDB($lStrSQLQueryAll, $pObjDBHandler);
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQueryAll,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-ALLDATA",$lArrParams);
        }   
    }//end 

    /**
     * Retourne le nombre d'objet 
     *
     * Retourne le nombre d'objet total
     *
     * @param \PDO  $pObjDBHandler  (Optionel) DB Handler
     * 
     * @return int  Nombre d'objet en base de données
     * 
     * @SuppressWarnings(PHPMD.StaticAccess)
     **/
    public function getDataCount(\PDO $pObjDBHandler=null):int
    {
        try {
            $lStrSQLQueryAll = SQLQueryBuilder::buildSQLSelectOrder($this->getTablename(), ["count(*) as nb"], null);
            $lArrResult = DatabaseManager::queryDB($lStrSQLQueryAll, $pObjDBHandler);
            return $lArrResult[0]['nb'];
        }
        catch(\Exception $e)
        {
            $lArrParams = [
                $lStrSQLQueryAll,
                $e->getMessage()
            ];
            throw new DatabaseException("DB-LOAD-ALLDATA",$lArrParams);
        }   
    }//end getDataCount()
}//end class

?>