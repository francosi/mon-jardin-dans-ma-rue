<?php
class Zend_View_Helper_HeaderColor
{
    function headerColor()
    {
        $user = Zend_Auth::getInstance()->getIdentity();
        
        return $user->couleurentete;
    }  
    
}
