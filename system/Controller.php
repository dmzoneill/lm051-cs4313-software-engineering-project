<?php
// phpcs:ignoreFile

/** @psalm-suppress UndefinedClass */ /** @psalm-suppress UndefinedClass */ abstract class Controller extends Base
{
	/** @psalm-suppress MissingPropertyType */ /** @psalm-suppress MissingPropertyType */ protected $view = false;	
	    
    
    public function __construct()
    {               
        parent::__construct();        
        $this->registry->__CONTROLLER = $this;
    }
        
    
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ abstract public function index();
}
