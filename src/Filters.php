<?php
namespace AMLO;


/**
 * A collection of custom filters to be used as callback with php data filtering functions
 * 	- to allow empty values  if the data is invalid filter MUST returns null
 * 	- to deny empty values if the data is invalid filter MUST returns false
 */
class Filters {

	
    public static function FILTER_SANITIZE_AS_CLASS_NAME($str)
	{
	    $str = \BOTK\Filters::FILTER_SANITIZE_ID($str) ;
	    return  str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    
    public static function FILTER_SANITIZE_AS_PROPERTY_NAME($str)
    {
        return lcfirst(self::FILTER_SANITIZE_AS_CLASS_NAME($str));
    }
	    

}