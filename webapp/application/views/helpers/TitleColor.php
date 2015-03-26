<?php
class Zend_View_Helper_TitleColor
{
    function titleColor()
    {
        $user = Zend_Auth::getInstance()->getIdentity();
        
        return $user->couleurtitre;
    }  
    
}
