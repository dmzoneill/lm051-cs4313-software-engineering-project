<?php

class test implements Observer
{
	
	const OBSERVER_NAME = "Test OBSERVER";
    const OBSERVER_DEVELOPER = "David O Neill";
    const OBSERVER_EMAIL = "dave@feeditout.com";
    const OBSERVER_WEBISTE = "http:/www.feeditout.com";
    const OBSERVER_VERSION = 0.1;
	
    
    public function __construct()
    {
        
    }

    
    public function update( Observable $subject , $status )
    {
         //echo get_class( $subject ) . " " . $status . "</br>";
    }
    
    public function announce()
    {
    	print self::OBSERVER_NAME . "<br />";
    	print self::OBSERVER_DEVELOPER . "<br />";
    	print self::OBSERVER_EMAIL . "<br />";
    	print self::OBSERVER_WEBISTE . "<br />";
    	print self::OBSERVER_VERSION . "<br />";    	
    }
}
