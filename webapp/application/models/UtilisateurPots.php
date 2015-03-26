<?php

class Application_Model_UtilisateurPots extends Zend_Db_Table      
{         
    protected $_name = 'utilisateurpots'; 
    protected $db;    
    
    function __construct() 
    {
        parent::__construct();
        
        $this->db = zend_db_table_abstract::getdefaultadapter();	  
        $this->db->query("SET NAMES 'utf8'");
    }    

    public function getPotById($idpot,$iduser)
    {
    	$select = $this->db->select();
    	$select->from(array('b' => 'utilisateurpots'))
    	->where('b.utilisateur_id = (?)', $iduser)
    	->where('b.pot_id = (?)', $idpot);
    
    	$return = $select->query()->fetchAll();
    
    	return $return;
    } 

    public function getPotByUser($iduser)
    {
    	$select = $this->db->select();
    	$select->from(array('b' => 'utilisateurpots'))
    	->where('b.utilisateur_id = (?)', $iduser);
    
    	$return = $select->query()->fetchAll();
    
    	return $return;
    }
    
//     public function updatePotWithPlanUser($idpot,$idplante,$iduser,$lat,$long)
//     {
//     	$data = array(
// 		    'plante_id'      => $idplante,
// 		    'user_id'      => $iduser,
//     		'latitude'		=> $lat,
//     		'longitude'		=> $long
// 		);
    	
//     	$n = $this->db->update('pots', $data, 'id = "'.$idpot.'"');
//     }
    
                       
}