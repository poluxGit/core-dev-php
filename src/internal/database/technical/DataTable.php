<?php



namespace polux\CorePHP\Internal\Database\Technical;

/**
 * Classe 'DataTable' 
 * 
 * Représente une table de la base de données.
 * 
 * @author PoLuX
 *        
 */
class DataTable
{
    /**
     * Nom de la table
     * 
     * @var string
     */
    private $tableName;
    
    /**
     * Constructeur par défaut
     * 
     * @param string $tablename Nom de la table
     */
    public function __construct(string $tablename)
    {
        $this->tableName = $tablename;
    }//end __construct()
    
    /**
     * Retourne le nom de la table
     * @return string
     */
    public function getTablename(){
        return $this->tableName;
    }//end getTablename()
    
    

}//end class

