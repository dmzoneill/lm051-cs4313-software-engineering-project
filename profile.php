<?php

require_once ( dirname( __FILE__ ) . "/conf.php" );

class Profile extends Controller
{   

    public function __construct ()
    {
        parent::__construct();        
        
        if($this->session->isLoggedOn() == false)
        {
            header('Location: login.php');
            exit;
        }
                
        
        if ( isset ( $this->registry->__GET[ 'ajax' ] ) )
        {
            if ( isset ( $this->registry->__GET[ 'oldpass' ] ) )
            {
                $this->ajaxCheckOldpass( $this->registry->__GET[ 'oldpass' ] );
            }       
        }
        else if( isset ( $this->registry->__POST[ 'oldpass' ] ) )
        {
            $this->doChangePassword();       
        }
        else if ( isset ( $this->registry->__POST[ 'changeAddress' ] ) )
        {
        	$this->doChangeAddress();
        }
        else if( isset ( $_FILES['uploadfile'] ) )
        {
            include("system/Uploader.php");
            $up = new Uploader("avatars"); 
        }
        else if( isset ( $this->registry->__GET[ 'messages' ] ) )
        {
            $this->messageCenter();       
        }
        else 
        {
            $this->index();
        }
        
    }
    
    
    private function ajaxCheckOldpass( $pass )
    {
        $user = $this->session->getCurrentUser();
        $pass = sha1( $pass );
        $oldpass = $this->db->query ( "select password from ou_users where username='$user'" , "one" );    
        
        if( $pass != $oldpass )
        {
            print "false";
            return;
        }
        else
        {
            print "true";  
            return; 
        }        
    }
    
    
	private function doChangeAddress()
    {    	
        if( !isset( $this->registry->__POST[ 'postcode' ] ) || !isset( $this->registry->__POST[ 'province' ] ) || !isset( $this->registry->__POST[ 'country' ] ) || !isset( $this->registry->__POST[ 'county' ] ) || !isset( $this->registry->__POST[ 'street' ] ) || !isset( $this->registry->__POST[ 'hnumber' ] ) || !isset( $this->registry->__POST[ 'sname' ] ) || !isset( $this->registry->__POST[ 'fname' ] ) )
        {
            print "false";
            exit;
        }
        
		$postcode = $this->registry->__POST[ 'postcode' ];
		$province = $this->registry->__POST[ 'province' ];
		$country = $this->registry->__POST[ 'country' ];
		$county = $this->registry->__POST[ 'county' ];
		$street = $this->registry->__POST[ 'street' ];
		$hnumber = $this->registry->__POST[ 'hnumber' ];
		$sname = $this->registry->__POST[ 'sname' ];
		$fname = $this->registry->__POST[ 'fname' ];  

		$user = $this->session->getCurrentUser();
		$userid = $this->session->getCurrentUserID();
		$update = $this->db->query( "update ou_users set fname='$fname', lname='$sname' where username='$user'" , "one" );   
		$addressExists = $this->db->query( "select count(*) from addresses where user_id='$userid'" , "one" );  	

		if( $addressExists == 0 )
		{
			$query = "insert into addresses values( null, '$userid', '$hnumber', '$street', '$county', '$province', '$country', '$postcode')";
			$update = $this->db->query( $query , "one" );
		}
		else
		{
			$query = "update addresses set hnumber='$hnumber', street='$street', county='$county', stateprovince='$province', country='$country', postcode='$postcode' where user_id='$userid'";
			$update = $this->db->query( $query , "one" );  			
			$query = "update ou_users set country='$country' where userid='$userid'";
			$update = $this->db->query( $query , "one" ); 
		}   

		print "true";
		exit;
    }
    
    
    private function doChangePassword()
    {
        if( !isset( $this->registry->__POST[ 'oldpass' ] ) || !isset( $this->registry->__POST[ 'pass1' ] ) || !isset( $this->registry->__POST[ 'pass2' ] ) )
        {
            print "false";
            exit;
        }
        
        $old = sha1($this->registry->__POST[ 'oldpass' ]);
        $pass1 = sha1($this->registry->__POST[ 'pass1' ]);
        $pass2 = sha1($this->registry->__POST[ 'pass2' ]);
                
        $user = $this->session->getCurrentUser();
        $oldpass = $this->db->query ( "select password from ou_users where username='$user'" , "one" );    
        
        if( $old != $oldpass)
        {
            print "false";
            exit;
        }
        else
        {        
	        if( $pass1 != $pass2 )
	        {
	            print "false";
	            return;
	        }
	        else
	        {
	            $this->db->query ( "update ou_users set password='$pass1' where username='$user'" , "one" );   
	            print "true";  
	            return; 
	        }
        }
    }

    
    public function messagebox_directive ( $match )
    {
        $user = $this->session->getCurrentUser();
        
        $messages =  $this->db->query ( "select * from messages a, ou_users b where a.to_id=b.userid and a.to_id='".$this->session->getCurrentUserID()."' order by msg_id DESC" , "array" );  
        
        $output = "";
        
        foreach( $messages as $message )
        {
            $data = $match;            
            
            $msg_id = $message[0];
            $fromid = $message[1];
            $from = $this->db->query ( "select username from ou_users where userid='$fromid'" , "one" );  
            $to = $message[2];
            $title = $message[3];
            $body = nl2br($message[4]);
            $receipt = $message[5];
            $status = $message[6];
            $avatar = (!file_exists(__SITE_ROOT ."/images/avatars/$fromid.png")) ? "default/chess.png" : $message[20];

            $data = preg_replace ( "/\\[id\\]/s" , $msg_id , $data );
            $data = preg_replace ( "/\\[fromid\\]/s" , $fromid , $data );
            $data = preg_replace ( "/\\[from\\]/s" , $from , $data );
            $data = preg_replace ( "/\\[title\\]/s" , $title , $data );
            $data = preg_replace ( "/\\[status\\]/s" , $status , $data );
            $data = preg_replace ( "/\\[body\\]/s" , $body , $data );
            $data = preg_replace ( "/\\[avatar\\]/s" , $avatar , $data );
            
            $output .= $data;     
        }
        
        return $output;
    }
    
    
	public function messageoutbox_directive ( $match )
    {
        $user = $this->session->getCurrentUser();
        
        $messages =  $this->db->query ( "select * from messages a, ou_users b where a.to_id = b.userid and a.from_id='".$this->session->getCurrentUserID()."' order by msg_id DESC" , "array" );  
        
        $output = "";
        
        foreach( $messages as $message )
        {
            $data = $match;            
            
            $msg_id = $message[0];
            $fromid = $message[1];
            $from = $this->db->query ( "select username from ou_users where userid='$fromid'" , "one" );  
            $toid = $message[2];
            $to = $this->db->query ( "select username from ou_users where userid='$toid'" , "one" ); 
            $title = $message[3];
            $body = nl2br($message[4]);
            $receipt = $message[5];
            $status = $message[6];
            $avatar = (!file_exists(__SITE_ROOT ."/images/avatars/$toid.png")) ? "default/chess" : $message[20];

            $data = preg_replace ( "/\\[id\\]/s" , $msg_id , $data );
            $data = preg_replace ( "/\\[toid\\]/s" , $toid , $data );
            $data = preg_replace ( "/\\[to\\]/s" , $to , $data );
            $data = preg_replace ( "/\\[title\\]/s" , $title , $data );
            $data = preg_replace ( "/\\[status\\]/s" , $status , $data );
            $data = preg_replace ( "/\\[body\\]/s" , $body , $data );
            $data = preg_replace ( "/\\[avatar\\]/s" , $avatar , $data );
            
            $output .= $data;     
        }
        
        return $output;
    }
    
    
    private function messageCenter()
    {
    	if( isset( $this->registry->__GET[ 'compose' ] ) && isset( $this->registry->__GET[ 'memberid' ] ) && isset( $this->registry->__GET[ 'auction_id' ] ) )
    	{
    		$userto = $this->db->query( "select username from ou_users where userid = '". $this->registry->__GET[ 'memberid' ] ."'" ,"one" );
    		$subject = $this->db->query( "select item_header_description from auction_items where auction_id = '". $this->registry->__GET[ 'auction_id' ] ."'" ,"one" );
    		
    		$items = array(
            	"title" => "%send% %message% %to% $userto",
    		    "userto" => "$userto",
    			"usertoid" => $this->registry->__GET[ 'memberid' ],
    			"subject" => "$subject",
    			"link" => "viewauction.php?viewid=" .  $this->registry->__GET[ 'auction_id' ],          
    			"userfrom" => $this->session->getCurrentUser()  	
        	);

        	$this->view = new View( "composemessage" );
        	$this->view->process( $items );
        	$this->view->output();     		
    	}    
    	else if( isset( $this->registry->__GET[ 'outbox' ] ) )
    	{
    		$items = array(
            	"title" => "%outbox%",
    			"messageoutbox_directive" => array ( "outboxMessages" , $this , "messageoutbox_directive" )	
        	);

        	$this->view = new View( "messageoutbox" );
        	$this->view->process( $items );
        	$this->view->output();   	    		
    	}	
    	else if( isset( $this->registry->__GET[ 'compose' ] ) && !isset( $this->registry->__GET[ 'memberid' ] ) )
    	{
    		$items = array(
            	"title" => "%send% %message%"	
        	);

        	$this->view = new View( "defaultcomposemessage" );
        	$this->view->process( $items );
        	$this->view->output();   	    		
    	}
    	else if( isset( $this->registry->__POST[ 'seller' ] ) && isset( $this->registry->__POST[ 'subject' ] ) && isset( $this->registry->__POST[ 'message' ] ) )
    	{
    		$user = $this->session->getCurrentUser();
    		$user = $this->session->getCurrentUserID();
    		$msg = strip_tags($this->registry->__POST[ 'message' ],'<p><h1><h2><h3><h4><h5><h6><font><br><ul><ol><li>');
    		$to = $this->registry->__POST[ 'seller' ];
    		$subject = $this->registry->__POST[ 'subject' ];    		
    		$insert = $this->db->query( "insert into messages values( null, '$user', '$to', '$subject', '$msg', '0', '0')" , "one" );
    		
    		header('Location: profile.php?messages=true');
    		exit;
    	}
    	else
    	{
    		$items = array(
            	"title" => "%inbox%",
            	"messagebox_directive" => array ( "inboxMessages" , $this , "messagebox_directive" )
        	);

        	$this->view = new View( "messageinbox" );
        	$this->view->process( $items );
        	$this->view->output();  
    	}        
    }
    
    
    public function index ()
    {
        $user = $this->session->getCurrentUser();
        
        $userInfo = $this->db->query ( "select * from ou_users where username='$user'" , "row" );   
        $useraddr = $this->db->query ( "select * from addresses where user_id='$userInfo[0]'" , "row" ); 

        $items = array(
            "title" => "%profile%",
            "reg-username" => $userInfo[2],
            "reg-email" => $userInfo[14],
            "reg-pass1" => "",
        	"reg-pass2" => "",
            "reg-fname" => $userInfo[5],
            "reg-sname" => $userInfo[6],
            "reg-dob" => "",
            "reg-hnumber" => $useraddr[2],
            "reg-street" => $useraddr[3],
            "reg-county" => $useraddr[4],
            "reg-province" => $useraddr[5],
            "reg-postcode" => $useraddr[7],
            "reg-avatar" => ($userInfo[13]=="") ? "images/avatars/default/butterfly.png" : "images/avatars/".$userInfo[13],
            "reg-username-error" => "",
            "reg-email-error" => "",
            "reg-pass1-error" => "",
        	"reg-pass2-error" => "",
            "reg-fname-error" => "",
            "reg-sname-error" => "",
            "reg-dob-error" => "",
            "reg-hnumber-error" => "",
            "reg-street-error" => "",
            "reg-county-error" => "",
            "reg-country-error" => "",
            "reg-province-error" => "",
            "reg-postcode-error" => ""
        );

        $this->view = new View( "profile" );
        $this->view->process( $items );
        $this->view->output();   

    }   

}

new Profile;


