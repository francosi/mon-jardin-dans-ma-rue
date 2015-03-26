<?php

class Application_Form_Mdploose extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
 
        $this->addElement(
            'text', 'keyword', array(
                'label' => 'Email:',
                'required' => true,
                'filters'    => array('StringTrim'),    
                'filters'    => array('StripTags'),
            ));       
 
       $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Envoyer',
            ));
    }


}

