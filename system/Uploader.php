<?php

class Uploader
{
    private $upDir = null;
    private $session = null;
    private $db = null;
    private $registry = null;
    
    public function Uploader($updir) 
    {
        $this->registry = Registry::getInstance( array() );
        
        if( $updir == "avatars" )
        {
            $this->upDir = __SITE_ROOT . "/images/avatars/";
            $this->avatarUpload();
        }
        else
        {
            $this->upDir = __SITE_ROOT . "/images/auction/temp/";
            $this->auctionUpload();
        }        
        
    }
    
    
    private function avatarUpload()
    {                
        $user = $this->registry->__SESSION->getCurrentUser();
        $id = $this->registry->__SESSION->getCurrentUserID();
        
		$uploaddir = $this->upDir;		
		$file = $uploaddir . $id .".png"; 
		
		if($id == "")
		{
		    print "error "; exit;		    
		}
		 
		if ( move_uploaded_file( $_FILES['uploadfile']['tmp_name'] , $file ) ) 
		{ 
		    echo $id .".png"; 
		    $id = $this->registry->__DB->query( "update ou_users set avatar='$id.png' where username='$user'" , "one" );
		} 
		else 
		{
			echo "error";
		}          
    }
    
    
    private function auctionUpload()
    {                
        //$user = $this->registry->__SESSION->getCurrentUser();
        //print $user;
        //$id = $this->db->query( "select id from ou_users where username='$user'" , "one" );
        
		$uploaddir = $this->upDir;	
		$mic = microtime();	
		$file = $uploaddir  ."$mic.png";
		 
		if ( move_uploaded_file( $_FILES['uploadfile']['tmp_name'] , $file ) ) 
		{ 
		    echo "$mic.png"; 
		} 
		else 
		{
			echo "error";
		}          
    }
}

