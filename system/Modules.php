<?php

class Modules implements Singleton
{	
	private static $instance = false;
	
		
    private function __construct( $args = array() ) 
    { 
    	$this->loadModules( $args[0] );
    }

    
    private function __clone() { }    
    public function __destruct() { }
	
	
    public static function getInstance( $args = array() )
    {
    	if( ! self::$instance )
    	{            
            self::$instance = new Modules( $args );            
            return self::$instance;
    	}
    	else 
    	{
    		return self::$instance;
    	}
    }


    private function loadModules( Controller $observable )
    {
        if( ! $observable instanceof Observable )
        {
            return;
        }

        $modules_dir = __SITE_ROOT . "/system/observers/GLOBAL/";
        
        $dh = @opendir( $modules_dir );
        
        if( $dh  )
        {
            while( ( $file = readdir( $dh ) ) !== false )
            {
                if( !is_dir( $file ) && !stristr( $file , "~" ) )
                {
                    require_once( __SITE_ROOT . "/system/observers/GLOBAL/" . $file );
                    $class = explode( "." , $file ); 
                    $obs = new $class[0];
                    $observable->attach( $obs );
                }
            }   
        } 

        $modules_dir = __SITE_ROOT . "/system/observers/" . get_class( $observable ) . "/";
        
        $dh = @opendir( $modules_dir );
        
        if( $dh )
        {
            while( ( $file = readdir( $dh ) ) !== false )
            {
                if( !is_dir( $file ) && !stristr( $file , "~" ) )
                {
                    require_once( __SITE_ROOT . "/system/observers/" . get_class( $observable ) . "/" . $file );
                    $class = explode( "." , $file ); 
                    $obs = new $class[0];
                    $observable->attach( $obs );
                }
            }   
        } 
        
        // once modules are loaded destruct
        
        $this->__destruct();
    }
}
