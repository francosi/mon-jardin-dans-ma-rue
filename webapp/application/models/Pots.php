<?php

class Application_Model_Pots extends Zend_Db_Table      
{         
    protected $_name = 'pots'; 
    protected $db;    
    
    function __construct() 
    {
        parent::__construct();
        
        $this->db = zend_db_table_abstract::getdefaultadapter();	  
        $this->db->query("SET NAMES 'utf8'");
    }    

    public function getPotById($idpot)
    {
    	$select = $this->db->select();
    	$select->from(array('b' => 'pots'))
    	->where('b.pot_id = (?)', $idpot);
    
    	$return = $select->query()->fetchAll();
    
    	return $return;
    } 

    public function getPotByUser($iduser)
    {
    	$select = $this->db->select();
    	$select->from(array('b' => 'pots'))
    	->where('b.user_id = (?)', $iduser);
    
    	$return = $select->query()->fetchAll();
    
    	return $return;
    }
    
    public function updatePotWithPlanUser($idpot,$idplante,$lat,$long)
    {
    	$data = array(
		    'plante_id'      => $idplante,
    		'latitude'		=> $lat,
    		'longitude'		=> $long
		);
    	
    	$n = $this->db->update('pots', $data, 'pot_id = "'.$idpot.'"');
    }
    
                       
}