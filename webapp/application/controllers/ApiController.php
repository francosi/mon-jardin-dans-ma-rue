<?php
error_reporting(-1);
ini_set("display_errors", 1);

class ApiController extends Zend_Controller_Action
{      
    protected $user;

    public function init()
    {     
         //Récupératation des infos de l'utiliateur
        //$this->user = Zend_Auth::getInstance()->getIdentity();
    }   

    public function preDispatch()
    {
         // Gestion de l'état de connexion de l'utilisateur
        /*if (!Zend_Auth::getInstance()->hasIdentity() ) {
            $this->_helper->redirector ( 'index', 'auth' );
        }*/
        
    } 
    
    public function sigfoxAction()
    { 
    	$this->view->test = $this->_request->getParam('data');
    	$status = $this->_request;
    	print_r ($status);
    }  
     
    


}

