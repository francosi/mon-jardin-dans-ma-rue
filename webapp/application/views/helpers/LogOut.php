<?php
class Zend_View_Helper_LogOut
{
    function logOut($baseUrl)
    {
        $divLogOut = '';
        
         // Gestion de l'état de connexion de l'utilisateur
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $divLogOut = '
                <div id="deconnexion">
                  <a class="ombre" href="'.$baseUrl.'/auth/logout">D&eacute;connexion</a>
                </div>
            ';
        } 
        
        return $divLogOut;
    }
}
          
      