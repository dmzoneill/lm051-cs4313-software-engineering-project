<?php

class Index_test extends Plugin
{
    const PLUGIN_NAME = "Test Plugin";
    const PLUGIN_DEVELOPER = "David O Neill";
    const PLUGIN_EMAIL = "dave@feeditout.com";
    const PLUGIN_WEBISTE = "http:/www.feeditout.com";
    const PLUGIN_VERSION = 0.1;


    public function __construct()
    {
        parent::__construct();        
        
        $this->status = "hello";
        $this->notify();
           	
        $this->registry->__VIEW->registerHook( $this , 'Index_test01' , '{something}' , 'beginBold' );
        $this->registry->__VIEW->registerHook( $this , 'Index_test02' , '{something}' , 'endBold' );
        $this->registry->__VIEW->registerHook( $this , 'Index_test03' , '{time}' , 'timeFormat' );
    }

    
    public function announce()
    {
    	print self::PLUGIN_NAME . "<br />";
    	print self::PLUGIN_DEVELOPER . "<br />";
    	print self::PLUGIN_EMAIL . "<br />";
    	print self::PLUGIN_WEBISTE . "<br />";
    	print self::PLUGIN_VERSION . "<br />";
    }
    

    public function beginBold( $tag , $content )
    {
        return "<b>" . $tag;   
    }


    public function endBold( $tag , $content )
    {
        return $tag . "</b>";   
    }


    public function timeFormat( $tag , $content )
    {
        $format = "%word% " . date('l jS \of F Y h:i:s A');
        return $format;   
    }


    public function getLocale()
    {
        switch( $this->session->getLanguage() )
        {
            case "ch":

                $lang = array
                (
                    "word" => "字" 
                );
                return $lang;

            break;

            case "en":
            default:

                $lang = array
                (
                    "word" => "word" 
                );
                return $lang;

            break; 
        }
    } 
    
}
