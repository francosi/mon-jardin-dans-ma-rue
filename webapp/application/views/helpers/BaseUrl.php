<?php
class Zend_View_Helper_BaseUrl
{
    function baseUrl()
    {
        $fc = Zend_Controller_Front::getInstance();
        
        $url = $fc->getBaseUrl();
        if( empty($url) ) $url = 'http://' . $_SERVER['SERVER_NAME'];
        
        return $url;
    }  
    
}
