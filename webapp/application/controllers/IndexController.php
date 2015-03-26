<?php
error_reporting(-1);
ini_set("display_errors", 1);

class IndexController extends Zend_Controller_Action
{      
    protected $user;

    public function init()
    {     
         //Récupératation des infos de l'utiliateur
        $this->user = Zend_Auth::getInstance()->getIdentity();
        Zend_Layout::startMvc();
        Zend_Layout::getMvcInstance()->assign('user', $this->user);
    }   

    /*public function preDispatch()
    {
         // Gestion de l'état de connexion de l'utilisateur
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector ( 'index', 'auth' );
        } 
        
    }*/  

    public function preDispatch()
    {
         // Gestion de l'état de connexion de l'utilisateur
        if (!Zend_Auth::getInstance()->hasIdentity() ) {
            $this->_helper->redirector ( 'index', 'auth' );
        } 
        
    }

	public function indexAction()
    { 
      
//     	$plantesModel = new Application_Model_Plante();
//     	$plantes = $plantesModel->getPlanteByid(1);
//     	$this->view->plantes = $plantes;
		$this->view->user = $this->user;
        
    }   
    
    public function mapAction()
    { 
      
       $potUserModel = new Application_Model_UtilisateurPots();
      // echo'<script>alert("'.$this->user->idutilisateur.'");</script>';
       $pots = $potUserModel->getPotByUser($this->user->utilisateur_id);
       $this->view->pots = $pots;
      // echo $this->user->nom;
        
        
    }  
    
    public function profilAction()
    { 

    	$this->view->user = $this->user;
        
        
        
    }   
    
    
	public function albumAction()
    {
    
    
    
    
    }
    
    public function selfieAction()
    {
    	    
    }
    
    public function addAction()
    { 
      
        
        
        
    }  


}

