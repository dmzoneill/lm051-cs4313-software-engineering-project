<?php

require_once ( dirname( __FILE__ ) . "/conf.php" );

class Admin extends Controller
{
 
    public function __construct ()
    {
        parent::__construct ();  
        
    	if( $this->session->isLoggedOn() == false )
        {
            header('Location: login.php');
            exit;
        }
        
        $userid = $this->session->getCurrentUserID();
        $groupid = $this->db->query( "select groupid from ou_users where userid='$userid'" , "one" );
        
        if( $groupid != 5 )
        {
        	$this->session->endSession();
        	header('Location: login.php');
            exit;
        }
        
    	if( isset( $this->registry->__GET['ajaxfetch'] ) )
        {     

        	// get categories
        	if( $this->registry->__GET['ajaxfetch'] == "categories" )
        	{
        		
        	}   
        	    
        	
        	// get user details
        	else if( $this->registry->__GET['ajaxfetch'] == "users" )
        	{
        		$userid = $this->registry->__GET['ajaxfetchdata'];
        		$user = $this->db->query( "select * from ou_users where userid='$userid'" , "row" );
        		$output = "";
				$i = 0;
        		while ($i < count($user) )
        		{
        			if($i == 13)
        			{
        				$user[13] = ( strlen($user[13]) < 3) ? "default/chess.png" : $user[13];
        			}
        			$output .= $user[$i] . "|";
        			$i++;
        		}
        		print $output;        		
        	}
        	
        	
        	// get auctions
        	else if( $this->registry->__GET['ajaxfetch'] == "auctions" )
        	{
        		$userid = $this->registry->__GET['ajaxfetchdata'];
        		$auctions = $this->db->query( "select auction_id, item_header_description, bidding_endtime from auction_items where sellerid='$userid'" , "array" );
        		$output = "";
				$i = 0;
        		while ($i < count($auctions) )
        		{
        			$temp = $auctions[$i];
        			$h = 0;
        			$img = $this->db->query( "select MAX(pic_url) from auction_pictures where auction_id='{$temp[0]}'", "one" );
            		$img = ($img == "") ? "../blanksmall.jpg" : $img;
	        		while ($h < count($temp) )
	        		{
	        			if( $h == 2 )
	        			{
	        				$datetime = explode( " " , $temp[$h] );
	        				$date = explode( "-" , $datetime[0] );
	        				
	        				$output .= "{$date[2]}-{$date[1]}-{$date[0]} " . $datetime[1] . "|";
	        			}
	        			else
	        			{
	        				$output .= $temp[$h] . "|";
	        			}
	        			$h++;
	        		}    

	        		$output .= "$img|#";
	        		$i++;
        		}
        		print $output; 
        	} 	
        	
        	
        	// user messages
        	else if( $this->registry->__GET['ajaxfetch'] == "messages" )
        	{
        		$userid = $this->registry->__GET['ajaxfetchdata'];
        		$msgs = $this->db->query( "select msg_id, from_id, title, body from messages where to_id='$userid' or from_id='$userid'" , "array" );
        		$output = "";
				$i = 0;
        		while ($i < count($msgs) )
        		{
        			$temp = $msgs[$i];
        			$h = 0;
	        		while ($h < count($temp) )
	        		{	        			
	        			$output .= $temp[$h] . "|";
	        			$h++;
	        		}    

	        		$output .= "#";
	        		$i++;
        		}
        		print $output; 
        	}
        	
        	
        	// user feedback 
        	else if( $this->registry->__GET['ajaxfetch'] == "feedback" )
        	{
        		$userid = $this->registry->__GET['ajaxfetchdata'];
        		$feedback = $this->db->query( "select feed_id, rating_product, rating_promptness, rating_communication, rating_overall, rating_message from auction_feedback where buyer_id='$userid'" , "array" );
        		$output = "";
				$i = 0;
        		while ($i < count($feedback) )
        		{
        			$temp = $feedback[$i];
        			$h = 0;
	        		while ($h < count($temp) )
	        		{	        			
	        			$output .= $temp[$h] . "|";
	        			$h++;
	        		}    

	        		$output .= "#";
	        		$i++;
        		}
        		print $output; 
        	}
        	else if( $this->registry->__GET['ajaxfetch'] == "bids" )
        	{
        		
        	}
        	if( $this->registry->__GET['ajaxfetch'] == "watching" )
        	{
        		
        	}
        	else
        	{
        		exit;
        	}        	
        	  
        	exit;   	
        }
        else if( isset( $this->registry->__GET['ajaxedit'] ) )
        {        

        	
        	
        	
        	if( $this->registry->__GET['ajaxedit'] == "categories" )
        	{
        		
        	}     

        	
        	
        	
        	// ban delete use
        	else if( $this->registry->__GET['ajaxedit'] == "users" )
        	{
        		$userid = $this->registry->__GET['ajaxeditdata'];
        		
        		if( isset ($this->registry->__GET['ajaxdelete'] ) )
        		{        			
        			$this->db->query( "delete from addresses where user_id='$userid'" ,"one" );
        			$this->db->query( "delete from auction_bids where user_id='$userid'" ,"one" );
        			$this->db->query( "delete from auction_feedback where buyer_id='$userid' or seller_id='$userid'" ,"one" ); 
        			$this->db->query( "delete from item_watching where user_id='$userid'" ,"one" );      
        			$this->db->query( "delete from messages where from_id='$userid' or to_id='$userid'" ,"one" ); 			
        			$auctions = $this->db->query("select auction_id from auction_items where sellerid='$userid'","array");        			
        			foreach($auctions as $auction)
        			{
        				$this->db->query( "delete from auction_pictures where auction_id='$auction[0]'" ,"one" );
        			}        			
        			$this->db->query( "delete from auction_items where sellerid='$userid'" ,"one" );
        			$this->db->query( "delete from ou_users where userid='$userid'" ,"one" );
        			
        			print "Deleted user ";
        		}
        		else if ( isset ($this->registry->__GET['ajaxban'] ) )
        		{
        			$this->db->query( "update ou_users set groupid='6' where userid='$userid'" ,"one" );
        			print "banned user ";	
        		}   
        		else
        		{
        			$this->db->query( "update ou_users set groupid='3' where userid='$userid'" ,"one" );
        			print "unbanned user ";	
        		}        		
        	}
        	
        	
        	
        	
        	// edit auction date time
        	else if( $this->registry->__GET['ajaxedit'] == "auctions" )
        	{
        		if( isset($this->registry->__GET['ajaxdelete']) )
        		{
        			$auction_id = $this->registry->__GET['ajaxeditdata'];
        			$this->db->query( "delete from auction_bids where auction_id='$auction_id'" ,"one" );
        			$this->db->query( "delete from auction_feedback where auction_id='$auction_id'" ,"one" ); 
        			$this->db->query( "delete from auction_pictures where auction_id='$auction_id'" ,"one" );     
        			$this->db->query( "delete from item_watching where auction_id='$auction_id'" ,"one" ); 
        			$this->db->query( "delete from auction_items where auction_id='$auction_id'" ,"one" ); 
        			        			
        			print "Auction deleted";
        		}
        		else
        		{        		
	        		$auctiondata = explode("|",$this->registry->__GET['ajaxeditdata']);
	        		$id = $auctiondata[0];
	        		
	        		$p = explode(":",$auctiondata[2]);
	        		$time = (count($p) == 3) ? $auctiondata[2] : $auctiondata[2] .":00";
	        		
	        		$newdate = explode("-",$auctiondata[1]);
	            	$year = $newdate[2];
	            	$month = $newdate[1];
	            	$day = $newdate[0];
	            	
	            	$this->db->query( "update auction_items set bidding_endtime='$year-$month-$day $time' where auction_id='$id'" , "one" );
	            	
	            	print "Auction finishing time updated";
        		}            	
        	} 

        	
        	
        	
        	else if( $this->registry->__GET['ajaxedit'] == "messages" )
        	{
        		
        	}
        	
        	
        	
        	
        	else if( $this->registry->__GET['ajaxedit'] == "feedback" )
        	{
        		
        	}
        	
        	
        	
        	
        	else if( $this->registry->__GET['ajaxedit'] == "bids" )
        	{
        		
        	}
        	
        	
        	
        	
        	if( $this->registry->__GET['ajaxedit'] == "watching" )
        	{
        		
        	}
        	
        	
        	
        	
        	else
        	{
        		exit;
        	} 
        	  
        	exit;   	
        }
        else
        {          
       		$this->index ();
        }
    }    

    public function index()
    {    	
   		$items = array (
	        "title" => "admin" 
	    );
	
        $this->view = new View ( "admin" );
        $this->view->process ( $items );
        $this->view->output ();
    }
}

new Admin;
