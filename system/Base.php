<?php 

abstract class Base implements Observable
{
	protected $registry = false;
	protected $db = false;
	protected $modules = false;
	protected $session = false;
	protected $common = false;
	protected $status = null;		
	
	
	public function __construct()
	{		
		$this->db = DatabaseManager::getInstance( array( __DB_HOST , __DB_USER , __DB_PASS , __DB_NAME ) );			
		$this->registry = Registry::getInstance();		
		$this->registry->__DB = $this->db;	
		
        $this->session = SessionManager::getInstance();
        $this->registry->__SESSION = $this->session;
        
        $this->modules = Modules::getInstance( array( $this ) );
        $this->registry->__MODULES = $this->modules;
        
        $this->common = ViewCommon::getInstance();
        $this->registry->__COMMON = $this->common;        
	}	
	
	
    public function attach( Observer $observer )
    {
        if( ! isset( $this->registry->__OBSERVERS[ get_class( $observer ) ] ) )
        {
            $this->registry->__OBSERVERS[ get_class( $observer ) ] = $observer;   
        }
    }

    
    public function detatch( Observer $observer )
    {
        unset( $this->registry->__OBSERVERS[ get_class($observer) ] );
    }


    public function notify()
    {
        foreach( $this->registry->__OBSERVERS as $key => $observer )
        {
            $observer->update( $this , $this->status );
        }
    }

    
    public function status()
    {
        return $this->status;
    }
}