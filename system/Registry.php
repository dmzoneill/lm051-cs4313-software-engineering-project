<?php

class Registry implements Singleton
{
	private static $instance = false;	
		
	private $__VARS = array ();
	
	public $__SITE_ROOT = __SITE_ROOT;
	
    public $__POST = array ();
    public $__GET = array ();   
    public $__SERVER = array ();           
    
    public $__DB = false;
    public $__MODULES = false;
    public $__SESSION = false;
    public $__COMMON = false;
    public $__REGISTRY = false;
    
    public $__CONTROLLER = false;
    public $__VIEW = false;     
    public $__OBSERVERS = array ();
    public $__PLUGINS = array ();    
    

    private function __construct ( $args = array () ) 
    { 
    	$this->__REGISTRY = $this;
    	$this->escapeInputData ();
    }  

    
    private function __clone () { }    
    public function __destruct () { }
    
    
    public function __set ( $index , $value )
    {
        $this->__VARS[ $index ] = $value;
    }


    public function __get ( $index )
    {
        return $this->__VARS[ $index ];
    }


    public static function getInstance ( $args = array() )
    {
        if ( ! self::$instance )
        {
            self::$instance = new Registry ();           
            return self::$instance;
        }
        else
        {
            return self::$instance;
        }
    }    


    private function escapeInputData ()
    {    	
        foreach ( $_POST as $key => $val )
        {
            if ( is_array( $val ) )
            {
                foreach ( $val as $keya => $valb )
                {
                    $this->__POST[ $key ][ $keya ] = mysql_real_escape_string ( stripslashes ( $valb ) );
                }
                unset ( $_POST[ $key ] );
            }	
            else 
            {
                $this->__POST[ $key ] = mysql_real_escape_string ( stripslashes ( $val ) );
                unset ( $_POST[ $key ] );
            }	             
        } 

         
        foreach ( $_GET as $key => $val )
        {
            if ( is_array( $val ) )
            {
                foreach ( $val as $keya => $valb )
                {
                    $this->__GET[ $key ][ $keya ] = mysql_real_escape_string ( stripslashes ( $valb ) );
                }
                unset ( $_GET[ $key ] );
            }	
            else 
            {
                $this->__GET[ $key ] = mysql_real_escape_string ( stripslashes ( $val ) );
                unset ( $_GET[ $key ] );
            }	             
        }
         
         
        foreach ( $_SERVER as $key => $val )
        {
            if ( is_array( $val ) )
            {
                foreach ( $val as $keya => $valb )
                {
                    $this->__SERVER[ $key ][ $keya ] = mysql_real_escape_string ( stripslashes ( $valb ) );
                }
                unset( $_SERVER[ $key ] );
            }	
            else 
            {
                $this->__SERVER[ $key ] = mysql_real_escape_string ( stripslashes ( $val ) );
                unset ( $_SERVER[ $key ] );
            }
        }         
    }   
    
    
	public function dumpDebugLog()
    {
    	print "<br /><h3>Registry</h3>";
    	
    	print $this->__SITE_ROOT . "<br />";
    	
    	print "<h5>VARS</h5>";    	
    	foreach( $this->__VARS as $key => $value )
    	{
    		echo "$key => $value<br/>";  		
    	}
    	
    	print "<h5>POST</h5>";    	
    	foreach( $this->__POST as $key => $value )
    	{
    		echo "$key => $value<br/>";  		
    	}
    	
    	print "<h5>GET</h5>";    	
    	foreach( $this->__GET as $key => $value )
    	{
    		echo "$key => $value<br/>";  		
    	}
    	
    	print "<h5>SERVER</h5>";    	
    	foreach( $this->__SERVER as $key => $value )
    	{
    		echo "$key => $value<br/>";  		
    	}
    	
    	print "<h5>PLUGINS</h5>";    	
    	foreach( $this->__PLUGINS as $key => $value )
    	{
    		echo "<h5>$key</h5>";
    		echo $value->announce() ."<br/>";		
    	}
    	
    	print "<h5>OBSERVERS</h5>";    	
    	foreach( $this->__OBSERVERS as $key => $value )
    	{
    		echo "<h5>$key</h5>";
    		echo $value->announce() ."<br/>";
    	}
    }
}
