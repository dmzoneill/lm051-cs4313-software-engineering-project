<?php

class DatabaseManager implements Singleton
{
	private static $instance;
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $connection;
    private $connected;
    private $queryCount;
    private $error;
    private $querylog = array();


    private function __construct( $args = array() ) 
    { 
    	$this->host = $args[0];
        $this->username = $args[1];
        $this->password = $args[2];
        $this->dbname = $args[3];
        $this->queryCount = 0;
        $this->connect();
    }   

    
    private function __clone() { }    
    public function __destruct() 
    {
    	$this->close();
    }
    

    public static function getInstance( $args = array() )
    {
    	if( ! self::$instance )
    	{                    
            self::$instance = new DatabaseManager( $args );            
            return self::$instance;
        }
        else
        {
            return self::$instance;
        } 	
    }

    
    public function query( $query , $struct ) 
    {
        try
        {
            $result = @mysql_query( $query , $this->connection );
            $this->querylog[] = $query;
            $this->queryCount++;

            if( ! $result )
            {
                Throw new Exception();
            }
            

            switch( $struct )
            {
                case "array" :

                	$row = @mysql_fetch_row( $result );
                	
                    while ( $row  )
                    {
                        $rows[] = $row;
                        $row = @mysql_fetch_row( $result );
                    }

                    return isset($rows) ? $rows : array();

                break;

                case "row" :

                    return @mysql_fetch_row( $result );

                break;

                case "one" :

                    return @mysql_result( $result , 0 , 0 );

                break;

                default :

                    $this->error = "Invalid reource identifier";

                    return false;

                break;
            }
        }
        catch( Exception $e )
        {
            $this->error = $this->getErrorDescription();

            return false;
        }		
    }


    public function getQueryCount()
    {
        return $this->queryCount;
    }
    
    
	public function dumpDebugLog()
    {
    	print "<br /><h3>Database</h3>";
    	echo $this->getQueryCount() . "<br />";
    	
        foreach ( $this->querylog as $query )
        {
        	print "$query <br />";
        }
    }


    public function getError()
    {
        return $this->error;
    }   


    private function getErrorDescription() 
    {
        return mysql_error();
    }


    public function isConnected()
    {
        return $this->connected;    
    }


    private function getErrorNumber() 
    {
        return mysql_errno();
    }


    private function connect()
    {
        try
        {			
            $this->connection = mysql_connect( $this->host , $this->username , $this->password , true );
            
            if ( $this->connection )
            {
                if( @mysql_select_db( $this->dbname , $this->connection ) )
                {
                    $this->connected = true;
                }
                else
                {
                    Throw new Exception("Db selection failed"); 
                }          	
            }
            else
            {            	
            	Throw new Exception("Mysql connection failed");
            }            
        }
        catch( Exception $e )
        {
            $this->error = $this->getErrorDescription();
            $this->error .= $e->getTrace();            
            $this->connected = false;
        }
    }


    private function close()
    {
        try
        {
            if( !mysql_close( $this->connection ) )
            {
                Throw new Exception("Unable to close connection to server");
            }
        }
        catch( Exception $e )
        {
            $this->error =  $e->getMessage();
        }
    }    
}

