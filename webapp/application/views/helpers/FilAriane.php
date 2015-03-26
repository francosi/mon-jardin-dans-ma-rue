<?php
class Zend_View_Helper_FilAriane
{
    function filAriane($baseUrl)
    {        
        $array_ariane = array();  
        $fil_ariane = '
          <a class="accueil ombre" href ="'.$baseUrl.'">Accueil</a>           
        ';
        
        
         // Récupération de l'idsite     
        try{
           $idsite = (int)Zend_Controller_Front::getInstance()->getRequest()->getParam('idsite', 0);             
           if($idsite > 0) $array_ariane['Site'] = $baseUrl."/site/visualiser/idsite/".$idsite;  
              
        } catch (Exception $e) {                  
        }   
        
         // Récupération de l'idzone
        try{
           $idzone = (int)Zend_Controller_Front::getInstance()->getRequest()->getParam('idbatiment', 0);           
           if($idzone > 0) $array_ariane['Batiment'] = $baseUrl."/batiment/visualiser/idsite/".$idsite."/idbatiment/".$idzone;  
        } catch (Exception $e) {          
        }    
        
         // Récupération de la terrasse
        try{
           $idterrasse = (int)Zend_Controller_Front::getInstance()->getRequest()->getParam('identree', 0);           
           if($idterrasse > 0) $array_ariane['Entree'] = $baseUrl."/entree/visualiser/idsite/".$idsite."/idbatiment/".$idzone."/identree/".$idterrasse;  
        } catch (Exception $e) {          
        } 
        
        foreach ($array_ariane as $key => $ariane){    
            $fil_ariane .= '<a class="item  ombre" href ="'.$ariane.'">'.$key.'</a>';
        }  
        
        return $fil_ariane;
    }  
    
}
