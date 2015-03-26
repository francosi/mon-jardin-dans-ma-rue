<?php
class Zend_View_Helper_FormatePrice
{
    function formatePrice($basePrice)
    {                  
        return number_format($basePrice, 2, ',', ' ');
    }  
    
}
