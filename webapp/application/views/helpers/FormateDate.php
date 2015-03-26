<?php
class Zend_View_Helper_FormateDate
{
    function formateDate($baseDate)
    {
        $date = $baseDate;
        $explodedDate = explode('-', $baseDate );
        
        if(count($explodedDate) == 3) $date = $explodedDate[2].'/'.$explodedDate[1].'/'.$explodedDate[0];
        
        return $date;
    }  
    
}
