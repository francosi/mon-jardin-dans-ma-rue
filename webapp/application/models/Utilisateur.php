<?php

class Application_Model_Utilisateur extends Zend_Db_Table      
{         
    protected $_name = 'utilisateur'; 
    protected $db;    
    
    function __construct() 
    {
        parent::__construct();
        
        $this->db = zend_db_table_abstract::getdefaultadapter();	  
        $this->db->query("SET NAMES 'utf8'");
    }    
    public function getUserId($login,$mdp)
    {
    	$select = $this->db->select();
    	$select->from(array('b' => 'utilisateur'))
    	->where('b.mdp = (?)', $mdp)
    	->where('b.mail = (?)', $login);
    
    	$return = $select->query()->fetchAll();
    
    	return $return;
    }
    

    public function getUserMail($login)
    {
    	$select = $this->db->select();
    	$select->from(array('b' => 'utilisateur'))
    	->where('b.mail = (?)', $login);
    
    	$return = $select->query()->fetchAll();
    
    	return $return;
    }
    
    public function updateUserWithMail($mail,$mdp)
    {
    	$data = array(
    			'mdp'      => $mdp
    	);
    	 
    	$n = $this->db->update('utilisateur', $data, 'mail = "'.$mail.'"');
    }
                       
}