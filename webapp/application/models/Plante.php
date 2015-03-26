<?php

class Application_Model_Plante extends Zend_Db_Table      
{         
    protected $_name = 'PLANTES'; 
    protected $db;    
    
    function __construct() 
    {
        parent::__construct();
        
        $this->db = zend_db_table_abstract::getdefaultadapter();	  
        $this->db->query("SET NAMES 'utf8'");
    }    
    public function getPlanteByid($idplante)
    {
    	$select = $this->db->select();
    	$select->from(array('b' => 'PLANTES'))
    	->where('b.id = (?)', $idplante);
    
    	$return = $select->query()->fetchAll();
    
    	return $return;
    }
                       
}