<?php 

class SessionManager implements Singleton
{ 
    private $registry;
    private $startTime = null;
    private $loggedon = false;  
    private static $instance = false;  


    private function __construct( $args = array() ) 
    { 
    	$this->registry = Registry::getInstance();
    	
    	session_start();         

        $username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "anonymous";
        
        if( $username != $this->registry->__DB->query( "select username from ou_users where userid='1'" , "one" ) )
        {
            $this->loggedon = true;
        }
        
        if( isset( $this->registry->__GET['lang'] ) )
        {
            $this->setLanguage( $this->registry->__GET['lang'] );           
        }  

        $this->initializeState();
    }  

    
    private function __clone() { }    
    public function __destruct() { }

    
    public function reconstruct()
    {
    	session_start();         

        $username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "anonymous";
        
        if( $username != $this->registry->__DB->query( "select username from ou_users where userid='1'" , "one" ) )
        {
            $this->loggedon = true;
        }
        
        if( isset( $this->registry->__GET['lang'] ) )
        {
            $this->setLanguage( $this->registry->__GET['lang'] );           
        }  

        $this->initializeState();    	
    }
    

    public static function getInstance( $args = array() )
    {
        if( ! self::$instance )
        {            
            self::$instance = new SessionManager();            
            return self::$instance;
        }
        else
        {
            return self::$instance;
        }   
    }


    public function loginUser( $user , $pass )
    {
        $pass = sha1( $pass );
        $userDetails = $this->registry->__DB->query( "select * from ou_users where username='$user'" , "row" );   
        
        if( ( $userDetails ) && ( $userDetails[3] == $pass ) )
        {
        	$_SESSION['userid'] = $userDetails[0];
            $_SESSION['username'] = $userDetails[2];
            $_SESSION['password'] = $userDetails[3];    
            $_SESSION['language'] = $userDetails[9];  
            $_SESSION['theme'] = $userDetails[10];       
  
            return true;
        }
        else
        {
            return false;
        }
    }


    public function getTheme()
    {
        return $_SESSION['theme'];
    }


    public function setTheme( $theme )
    {
        $_SESSION['theme'] = $theme;
        
        if( $this->loggedon == true )
        {
            $this->registry->__DB->query( "update ou_users set theme='$theme' where username='$_SESSION[username]'" , "one" ); 
        }
    }


    public function getLanguage()
    {
        return $_SESSION['language'];
    }


    public function setLanguage( $lang )
    {
        $_SESSION['language'] = $lang;
         
        if( $this->loggedon == true )
        {
            $this->registry->__DB->query( "update ou_users set locale='$lang' where username='$_SESSION[username]'" , "one" ); 
        }
    }


    public function isLoggedOn()
    {
        return $this->loggedon;
    }


    public function getCurrentUser()
    {
        return $_SESSION['username'];
    }
    
    
	public function getCurrentUserID()
    {
        return $_SESSION['userid'];
    }


    public function endSession() 
    { 
        $_SESSION = array(); 
        session_destroy(); 
    } 


    public function getAccessLevel()
    {
        return $this->registry->__DB->query( "select groupid from ou_users where username='$_SESSION[username]'" , "one" );   
    }


    private function initializeState()
    {
        if( $this->loggedon == false )
        {
          
            if( !isset( $_SESSION['username'] ) )
            {
                $_SESSION['username'] = $this->registry->__DB->query( "select username from ou_users where userid='1'" , "one" );
            }
        
            if( !isset( $_SESSION['startTime'] ) )
            {
                $_SESSION['startTime'] = time();
            }

            if( !isset( $_SESSION['language'] ) )
            {
                $_SESSION['language'] = 23; // english
            }

            if( !isset( $_SESSION['theme'] ) )
            {
                $_SESSION['theme'] = "smoothness";
            }
            
       		if( !isset( $_SESSION['userid'] ) )
            {
                $_SESSION['userid'] = 1;
            }
        }
        else
        {
            $this->validateCurrentUser();
        }
    }


    private function validateCurrentUser()
    {
        $currentUser = $_SESSION['username'];
        $userDetails = $this->registry->__DB->query( "select * from ou_users where username='$currentUser'" , "row" );  
        
        if( ( $userDetails ) && ( $userDetails[3] == $_SESSION['password'] ) )
        {
            $ts = time();
            $this->registry->__DB->query( "update ou_users set lastaccess=FROM_UNIXTIME($ts) where userid='$userDetails[0]'" , "one" );
            $this->loggedon = true;
        }
        else
        {            
            $this->loggedon = false;
            $this->initializeState();
        }
    }
    
    
    public function getKey( $key )
    {
    	return isset( $_SESSION[$key] ) ? $_SESSION[$key] : false;	
    }
    
    
	public function setKey( $key , $value )
    {
    	$_SESSION[$key] = $value;
    }
    
    
    public function dumpDebugLog()
    {
    	print "<br /><h3>Session</h3>";
    	
    	foreach( $_SESSION as $key => $value )
    	{
    		echo "$key => $value<br/>";  		
    	}
    }

}

