<?php

abstract class Controller extends Base
{
	protected $view = false;	
	    
    
    public function __construct()
    {               
        parent::__construct();        
        $this->registry->__CONTROLLER = $this;
    }
        
    
    abstract public function index();
}
