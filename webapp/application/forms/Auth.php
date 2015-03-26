<?php

class Application_Form_Auth extends Zend_Form
{

    public function init()
    {        
        $this->setMethod('post');
 
        $this->addElement(
            'text', 'login', array(
                'label' => 'Login:',
                'required' => true,
                'filters'    => array('StringTrim'),    
                'filters'    => array('StripTags'),
            ));   
 
        $this->addElement('password', 'password', array(
            'label' => 'Password:',
            'required' => true,
            ));       
 
       $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Connexion',
            ));
    }


}

