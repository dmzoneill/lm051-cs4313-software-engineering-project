<?php

abstract class Plugin extends Base 
{	
    public function __construct()
    {    
    	parent::__construct();  
        $this->registry->__PLUGINS[ get_class( $this ) ] = $this;
    }	   
   
    
    abstract public function announce();
    
    abstract public function getLocale();
}