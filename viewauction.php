<?php

require_once ( dirname( __FILE__ ) . "/conf.php" );

class ViewAuctions extends Controller
{
 
    public function __construct ()
    {
        parent::__construct ();        

    	if( isset( $this->registry->__GET['ajax'] ) )
        {
        	if( isset( $this->registry->__GET['checklogon'] ) )
        	{
        		$this->checkLoggedIn();
        	}   
        	exit; 	
        }
        else if( isset( $this->registry->__GET['viewid'] ) )
        {
        	$this->viewAuction();        	
        }
        else
        {        
        	
        	if( $this->session->getKey( "sortby" ) == false || isset( $this->registry->__GET['sortby'] ) )
        	{
        		if ( isset($this->registry->__GET['sortby']) )
        		{
        			if( $this->registry->__GET['sortby'] != "bidding_endtime" && $this->registry->__GET['sortby'] != "bid_history" && $this->registry->__GET['sortby'] != "price" && $this->registry->__GET['sortby'] != "postage" )
        			{
        				$this->registry->__GET['sortby'] = "bidding_endtime";
        			}
        		}
        		
    			$this->session->setKey( "sortby" , isset( $this->registry->__GET['sortby'] ) ? $this->registry->__GET['sortby'] : "bidding_endtime" );
        	}
    		
    		if( $this->session->getKey( "sortorder" ) == false || isset( $this->registry->__GET['sortorder'] ) )
    		{
    			if ( isset($this->registry->__GET['sortorder']) )
        		{
        			if( $this->registry->__GET['sortorder'] != "ASC" && $this->registry->__GET['sortorder'] != "DESC")
        			{
        				$this->registry->__GET['sortorder'] = "ASC";
        			}
        		}
    			
    			$this->session->setKey( "sortorder" , isset( $this->registry->__GET['sortorder'] ) ? $this->registry->__GET['sortorder'] : "ASC" );
    		}       

    		if( $this->session->getKey( "pictureHeight" ) == false || $this->session->getKey( "pictureWidth" ) == false || isset( $this->registry->__GET['pictureWidth']) || isset( $this->registry->__GET['pictureHeight'] ) )
    		{
    			if ( isset($this->registry->__GET['pictureWidth']) && isset($this->registry->__GET['pictureHeight']))
        		{
        			$width = $this->registry->__GET['pictureWidth'];
        			$height = $this->registry->__GET['pictureHeight'];
        			
        			if( $width != 80 && $width != 120 && $width != 160)
        			{
        				$this->registry->__GET['pictureWidth'] = 80;
        			}
        			if( $height != 50 && $height != 80 && $height != 110)
        			{
        				$this->registry->__GET['pictureHeight'] = 50;
        			}
        		}
    			
    			$this->session->setKey( "pictureHeight" , isset( $this->registry->__GET['pictureHeight'] ) ? $this->registry->__GET['pictureHeight'] : 50 );
    			$this->session->setKey( "pictureWidth" , isset( $this->registry->__GET['pictureWidth'] ) ? $this->registry->__GET['pictureWidth'] : 80 );
    		}     
        	
    		if( $this->session->getKey( "itemsperpage" ) == false || isset( $this->registry->__GET['itemsperpage'] ) )
    		{
    			if ( isset($this->registry->__GET['itemsperpage']) )
        		{
        			$itemsperpage = $this->registry->__GET['itemsperpage'];
        			
        			if( $itemsperpage != 10 && $itemsperpage != 15 && $itemsperpage != 20 && $itemsperpage != 25)
        			{
        				$this->registry->__GET['itemsperpage'] = 10;
        			}
        		}
    			
    			$this->session->setKey( "itemsperpage" , isset( $this->registry->__GET['itemsperpage'] ) ? $this->registry->__GET['itemsperpage'] : 10 );
    		}  
    		
        	$this->index ();
        }
    }
    
    
    private function checkLoggedIn()
    {
    	echo $this->session->isLoggedOn();    	
    }
    
    
	private function convert_datetime($str) 
	{	
		list($date, $time) = explode(' ', $str);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);		
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
				
		return $timestamp;
	}
    
    
    public function viewAuction()
    {
    	$auctionID = $this->registry->__GET['viewid'];

    	$item = $this->db->query( "select * from auction_items where auction_id='$auctionID'" , "array" );    	
   	
    	if( count($item) > 0)
    	{    		
    		$item = $item[0];
    		$auction_id = $item[0];	
    		$price = $item[1];	 	
    		// postage
    		$postage = ($item[2] == 0) ? "000" : $item[2];
			$pence = substr($postage, strlen($postage) -2, 2);
    		$pounds = substr($postage, 0, strlen($postage) -2);    		
    		$postage = "&#36;". $pounds .".$pence";
    		
    		$bidding_endtime = $item[3];	 	
    		$country_from = $item[4];	 	
    		$sellerid = $item[5];	 	
    		$condition = $item[6];	 	
    		$bid_history = $item[7];	 	
    		$starting_bid = $item[8];	 	
    		$reserve = $item[9];	 	
    		$item_header_description = $item[10];	 	
    		$item_full_description = $item[11];
    		$item_full_description = str_replace(array("\r\n", "\r", "\n", "\t"), '<br />', $item_full_description);	 	
    		$item_full_description = str_replace(array("\\r\\n", "\\r", "\\n", "\\t"), '<br />', $item_full_description);
    		$cat_id = $item[12];	 	
    		$subcat_id = $item[13];	 	
    		$status = $item[14];	
    		$img = $this->db->query( "select pic_url from auction_pictures where auction_id='$auction_id' order by pic_id ASC limit 0,1 " , "one" );
    		
    		$userid = $this->session->getCurrentUserID();
    		$watchingval = $this->db->query( "select count(*) from item_watching where auction_id = '$auction_id' and user_id='$userid'" , "one" );
    		$watching = ( $watchingval < 1 ) ? "%watch% %item%" : "%watching%";
    		$watchinglink = ( $watchingval < 1 ) ? "auction.php?watchitem=$auction_id" : "auction.php?watching=all";
    		
    		$idlang = $this->session->getLanguage();
    		$langCode = $this->db->query( "select code from locale where id='$idlang'" , "one" );
    		  		    		
    		$currentbid = $this->db->query( "select MAX(amount) from auction_bids where auction_id='$auction_id'" , "one" );
    		
    		if( $currentbid == "" )
    		{
    			$currentbid = "\\$0.00";
    			$nextbid = $starting_bid;
    		}
    		else 
    		{    		
    			$pence = substr($currentbid, strlen($currentbid) -2, 2);
    			$pounds = substr($currentbid, 0, strlen($currentbid) -2);	    			

    			$nextbid = $starting_bid + $currentbid;
    			$currentbid = "\\$$pounds.$pence";
    		}

    		
    		$pence = substr($nextbid, strlen($nextbid) -2, 2);
    		$pounds = substr($nextbid, 0, strlen($nextbid) -2);	
    		$nextbid = "\\$$pounds.$pence";
    		
    		$pence = substr($starting_bid, strlen($starting_bid) -2, 2);
    		$pounds = substr($starting_bid, 0, strlen($starting_bid) -2);	
    		$starting_bid = "\\$$pounds.$pence";
    		
    		list($date, $time) = explode(' ', $bidding_endtime);
			list($year, $month, $day) = explode('-', $date);
			list($hour, $minute, $second) = explode(':', $time);
			$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
			$timeleft = $timestamp -  time();
			
			$days = floor( $timeleft / 60 / 60 / 24 );
			$hours = $timeleft / 60 / 60 % 24;
			$mins = $timeleft / 60 % 60;
			
			$sleft = "";
			
			$sleft = ($days > 0) ? $days = $days . " %days%, " : "";
			$sleft .= $hours ." %hours%, ";
			$sleft .= $mins ." %minutes% ";
    		
			$sellerdetails = $this->db->query( "select username, avatar from ou_users where userid='$sellerid'" , "row" );
			
			$bidcount = $this->db->query( "select count(*) from auction_bids where auction_id='$auction_id'" , "one" );
    		
			if ( isset( $this->registry->__GET['biderror'] ) && isset( $this->registry->__GET['biderror'] ) )
			{
				$biderror = "%bid% > $currentbid";
			}
			else
			{
				$biderror = "";
			}	

			$winner = $this->db->query( "select user_id from auction_bids where amount=(select MAX(amount) from auction_bids where auction_id='$auctionID')" , "one" );
			
			if( $winner != "")
			{			
				$winnerDetails = $this->db->query( "select username, avatar from ou_users where userid='$winner'" , "row" );
				$winnerDetails[1] = ($winnerDetails[1]=="") ? "images/avatars/default/butterfly.png" : "images/avatars/".$winnerDetails[1];
			}
			else
			{
				$winnerDetails = array("0 %bids%","images/nosold.jpg");
			}
			
    		$items = array (
        		"imageList_directive" => array ( "images" , $this , "imageList_directive" ) ,
    			"bidhistory_directive" => array ( "bids" , $this , "bidhistory_directive" ) ,
            	"title" => $item_header_description,
    			"img" => "$img",
    			"item_header_description" => "$item_header_description",
    			"condition" => "$condition",
    			"timeleft" => "$sleft",	
    			"bidcount" => $bidcount,
    			"startingbid" => "$starting_bid",
    			"postage" => "$postage",
    			"currentBid" => "$currentbid",
    			"winnername" => $winnerDetails[0],
    			"winneravatar" => $winnerDetails[1],
    			"nextbid" => "$nextbid",
    			"seller" => "$sellerid",
    			"sellername" => $sellerdetails[0],
    			"avatar" => ( $sellerdetails[1] == "" ) ? "images/avatars/default/chess.png" : $sellerdetails[1],
    			"description" => nl2br($item_full_description),
    			"auctionid" => $auction_id,
    			"langCode" => $langCode,
    			"bidError" => $biderror,
    			"watching" => "$watching",
    			"watchinglink" => $watchinglink
        	);
    		
        	if( $this->convert_datetime( $bidding_endtime ) <= time() )
        	{
        		$this->view = new View ( "viewauctionfinished" );
        		$this->view->process ( $items );
        		$this->view->output ();
        	}
        	else
        	{        	
    			$this->view = new View ( "viewauction" );
        		$this->view->process ( $items );
        		$this->view->output ();
        	}
    		
    	}
    	else
    	{
    		$this->index();
    	}    	
    }
    
    
	public function bidhistory_directive ( $match )
    {
    	$auctionID = $this->registry->__GET['viewid'];
    	$bids = $this->db->query( "select * from auction_bids where auction_id='$auctionID' order by time DESC" , "array" ); 
    	
    	$output = "";
    	
        foreach ( $bids as $bid )
        {
            $data = $match;
            $bidderid = $bid[1];
            $amount = $bid[2];
            $bidtime = $bid[4];           
            
            $pence = substr($amount, strlen($amount) -2, 2);
    		$pounds = substr($amount, 0, strlen($amount) -2);	
    		$amount = "&#36;". $pounds .".$pence";
    		
    		$userdt = $this->db->query( "select username, avatar from ou_users where userid='$bidderid'" , "row" ); 
    		$username = $userdt[0];
    		$avatar = $userdt[1];
            
            $data = preg_replace ( "/\\[avatar\\]/s" , "$avatar" , $data );
            $data = preg_replace ( "/\\[bidderid\\]/s" , "$bidderid" , $data );
            $data = preg_replace ( "/\\[bidder\\]/s" , "$username" , $data );
            $data = preg_replace ( "/\\[amount\\]/s" , "$amount" , $data );
            $data = preg_replace ( "/\\[time\\]/s" , "$bidtime" , $data );
            
            $output.= $data;            
        }
        
        $output = substr($output, 0, strlen($output) -1);
                
        return $output;
    }
    
    
    public function imageList_directive ( $match )
    {
    	$auctionID = $this->registry->__GET['viewid'];
    	$imgs = $this->db->query( "select * from auction_pictures where auction_id='$auctionID'" , "array" );
    	
    	$output = "";
    	
        foreach ( $imgs as $img )
        {
            $data = $match;
            $image = $img[2];
            $data = preg_replace ( "/\\[img\\]/s" , $image , $data );
            $output.= $data;            
        }
        
        $output = substr($output, 0, strlen($output) -1);
                
        return $output;
    }
    
    
    public function auctionList_directive ( $match )
    {
    	$itemsperpage = $this->session->getKey( "itemsperpage" );
    	$page = isset( $this->registry->__GET['page'] ) ? (($this->registry->__GET['page'] -1) * $itemsperpage) : 0; 	
    	
    	if( !is_int( (int) $page) || $page<0)
    		$page = 0;
    		
    	if( !is_int( (int) $itemsperpage))
    		$itemsperpage = 10;
    	
    	if( !isset( $this->registry->__GET['category'] ) )
    	{
    		$cat = $this->db->query( "select MIN(cat_id) from auction_categories" , "one" );
    	}
    	else
    	{
    		$cat = $this->registry->__GET['category'];    		
    	}    	
  	
    	$now = date('Y-m-d H:i:s', time());
    	
    	$order = $this->session->getKey( "sortorder" );
    	$sortby = $this->session->getKey( "sortby" );
    	
    	if( !isset( $this->registry->__GET['subcategory'] ))
    	{
    		if( $sortby == "price" )
    		{
    			// DIRTY HACKKKKK
    			
    			$items = $this->db->query( "SELECT * FROM auction_items a, auction_bids b WHERE a.auction_id = b.auction_id	AND a.cat_id = '$cat' AND a.bidding_endtime >  '$now' AND b.amount = ( SELECT MAX( amount ) FROM auction_bids WHERE auction_id = a.auction_id ) ORDER BY b.amount $order limit $page,$itemsperpage" , "array" );
    			$titems = $items;
    			$rest = $this->db->query( "select * from auction_items where cat_id='$cat' and bidding_endtime  > '$now' ORDER BY starting_bid $order limit $page,$itemsperpage " , "array" );    			
    			foreach ( $rest as $and)   
    			{ 		
					$contains = false;
    				
	    			foreach ( $titems as $item)   
	    			{ 			
	    				if( $item[0] == $and[0] )
	    				{
	    					$contains = true;
	    					break;
	    				}   					
	    			}
	    			
	    			if($contains == false)
	    				$items[] = $and;
    			}
    		}
    		else
    		{
    			$items = $this->db->query( "select * from auction_items where cat_id='$cat' and bidding_endtime  > '$now' ORDER BY $sortby $order limit $page,$itemsperpage " , "array" );
    		} 	
    	}
    	else
    	{
    		$subcat = $this->registry->__GET['subcategory'];
    		if( $sortby == "price" )
    		{
    			// DIRTY HACKKKKK
    			
    			$items = $this->db->query( "SELECT * FROM auction_items a, auction_bids b WHERE a.auction_id = b.auction_id	AND a.cat_id = '$cat' and subcat_id='$subcat' AND a.bidding_endtime >  '$now' AND b.amount = ( SELECT MAX( amount ) FROM auction_bids WHERE auction_id = a.auction_id ) ORDER BY b.amount $order limit $page,$itemsperpage" , "array" );
    			$titems = $items;
    			$rest = $this->db->query( "select * from auction_items where cat_id='$cat' and subcat_id='$subcat' and bidding_endtime  > '$now' ORDER BY starting_bid $order limit $page,$itemsperpage" , "array" );
    			foreach ( $rest as $and)   
    			{ 		
					$contains = false;
    				
	    			foreach ( $titems as $item)   
	    			{ 			
	    				if( $item[0] == $and[0] )
	    				{
	    					$contains = true;
	    					break;
	    				}   					
	    			}
	    			
	    			if($contains == false)
	    				$items[] = $and;
    			}
    		}
    		else
    		{
    			$items = $this->db->query( "select * from auction_items where cat_id='$cat' and subcat_id='$subcat' and bidding_endtime  > '$now' ORDER BY $sortby $order limit $page,$itemsperpage" , "array" );
    		} 	
    	}
    	
    	
    	$width = $this->session->getKey( "pictureWidth" );
        $height = $this->session->getKey( "pictureHeight" );
    	
    	$output = "";
    	
    	$c = 1;
    	
        foreach ( $items as $item )
        {
            $data = $match;
            $auctionid = $item[0];
            $postage = ($item[2]==0) ? "000" : $item[2];
            $endtime = $item[3];
            $startprice = ($item[8]==0) ? "0.00" : $item[8];
            $itemdesc = $item[10];
         
            $bids = $this->db->query( "select count(*) from auction_bids where auction_id='$auctionid'", "one" );
            $bids = ($bids == "") ? 0: $bids;            
            $maxbid = $this->db->query( "select MAX(amount) from auction_bids where auction_id='$auctionid'", "one" );
            $maxbid = ($maxbid == "") ? "000" : $maxbid;
            $img = $this->db->query( "select MAX(pic_url) from auction_pictures where auction_id='$auctionid'", "one" );
            $img = ($img == "") ? "../blanksmall.jpg" : $img;
            
            list($date, $time) = explode(' ', $endtime);
			list($year, $month, $day) = explode('-', $date);
			list($hour, $minute, $second) = explode(':', $time);
			$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
			$timeleft = $timestamp -  time();
			
			$days = floor( $timeleft / 60 / 60 / 24 );
			$hours = $timeleft / 60 / 60 % 24;
			$mins = $timeleft / 60 % 60;
			
			$sleft = "";
			
			$sleft = ($days > 0) ? $days = $days . " %days%, " : "";
			$sleft .= $hours ." %hours%, ";
			$sleft .= $mins ." %minutes% ";
			
			$pence = substr($maxbid, strlen($maxbid) -2, 2);
    		$pounds = substr($maxbid, 0, strlen($maxbid) -2);	
    		$maxbid = "&#36;". $pounds .".$pence";
    		
    		$pence = substr($postage, strlen($postage) -2, 2);
    		$pounds = substr($postage, 0, strlen($postage) -2);	
    		$postage = "&#36;". $pounds .".$pence";    		
    		
			$data = preg_replace ( "/\\[categoryid\\]/s" , $item[12] , $data );
            $data = preg_replace ( "/\\[subcategoryid\\]/s" , $item[13] , $data );
			$data = preg_replace ( "/\\[height\\]/s" , $height , $data );
            $data = preg_replace ( "/\\[width\\]/s" , $width , $data );
			$data = preg_replace ( "/\\[auctid\\]/s" , $auctionid , $data );
            $data = preg_replace ( "/\\[img\\]/s" , $img , $data );
            $data = preg_replace ( "/\\[itemShort\\]/s" , $itemdesc , $data );
            $data = preg_replace ( "/\\[bids\\]/s" , $bids , $data );
            $data = preg_replace ( "/\\[price\\]/s" , $maxbid , $data );
            $data = preg_replace ( "/\\[postage\\]/s" , $postage , $data );
            $data = preg_replace ( "/\\[timeleft\\]/s" , $sleft , $data );
            $data = preg_replace ( "/\\[iter\\]/s" , $c , $data );
            
            $output.= $data;
            $c++;
        }
                
        return $output;
    }
    
    
    public function pageination_directive( $match )
    {
    	if( !isset( $this->registry->__GET['category'] ) )
    	{
    		$cat = $this->db->query( "select MIN(cat_id) from auction_categories" , "one" );
    	}
    	else
    	{
    		$cat = $this->registry->__GET['category'];    		
    	}  
    	
    	$now = date('Y-m-d H:i:s', time());
    	
    	if( isset( $this->registry->__GET['subcategory'] ) )
    	{    		
    		$subcat = $this->registry->__GET['subcategory'];
    	}
    	
    	if( isset($cat) && isset($subcat) )
    	{
    		$count = $this->db->query( "select count(*) from auction_items where cat_id='$cat' and subcat_id='$subcat' and bidding_endtime  > '$now'" , "one" );
    	}
    	else
    	{
    		$count = $this->db->query( "select count(*) from auction_items where cat_id='$cat' and bidding_endtime  > '$now'" , "one" );
    	}
    	
    	$itemsperpage = $this->session->getKey( "itemsperpage" );
    	$pages = ceil( $count / $itemsperpage );    	
    	
    	$page = isset($this->registry->__GET['page']) ? $this->registry->__GET['page'] : 1;
    	
    	if( !is_int( (int) $page) || $page<0)
    		$page = 1;
    		
    	if( !is_int( (int) $itemsperpage))
    	{
    		$itemsperpage = 10;
    	}
    	
    	$previous = ($page == 1) ? 1 : $page - 1;
    	$next = ($page == 1) ? 2 : $page + 1;
    	$current = $page;    	
    	$secondlast = floor( $count / $itemsperpage ) - 1;
    	$last = $secondlast + 1;
    	
    	$match = preg_replace ( "/\\[categoryid\\]/s" , $cat , $match );
    	$match = preg_replace ( "/\\[subcategoryid\\]/s" , isset( $this->registry->__GET['subcategory'] ) ? "&subcategoryid=$subcat" : "" , $match );
    	
    	$pagination = explode( "|" , $match );
    	$pagePrevious = $pagination[0];
    	$pageCurrrent = $pagination[1];
    	$pageNext = $pagination[2];
    	$pageSecondLast = $pagination[3];
    	$pageLast = $pagination[4];
    	$pageNextNext = $pagination[5];
    	
    	$output = ( $page == 1 ) ? "" : preg_replace ( "/\\[previous\\]/s" , $previous , $pagePrevious );
    	$output .= preg_replace ( "/\\[current\\]/s" , $current , $pageCurrrent );
    	$output .= ( ($current * $itemsperpage) < $count ) ? preg_replace ( "/\\[next\\]/s" , $next , $pageNext ) : "";
    	$output .= ( (($secondlast * $itemsperpage) < $count) && (($secondlast * $itemsperpage) > $next * $itemsperpage) ) ? preg_replace ( "/\\[secondlast\\]/s" , $secondlast , $pageSecondLast ) : "";
    	$output .= ( (($last * $itemsperpage) < $count) && (($last * $itemsperpage) > $next * $itemsperpage) ) ? preg_replace ( "/\\[last\\]/s" , $last , $pageLast ) : "";
		$output .= ( ($current * $itemsperpage) < $count ) ? preg_replace ( "/\\[next\\]/s" , $next , $pageNextNext ) : "";
		
    	return $output;
    }
    

    public function index()
    {   
    	if( !isset( $this->registry->__GET['category'] ) )
    	{
    		$cat = $this->db->query( "select MIN(cat_id) from auction_categories" , "one" );
    	}
    	else
    	{
    		$cat = $this->registry->__GET['category'];    		
    	}    	   			
    	
    	$now = date('Y-m-d H:i:s', time());

    	$catname = $this->db->query( "select name from auction_categories where cat_id='$cat'" , "one" );
    	$subcatname = "";
    	
    	if( isset( $this->registry->__GET['subcategory'] ))
    	{    		
    		$subcat = $this->registry->__GET['subcategory'];
    		$subcatname = $this->db->query( "select name from auction_sub_categories where subcat_id='$subcat'" , "one" );
    	}
    	
    	$title = "%auction% %listing% $catname ";
    	$title .= ($subcatname != "") ? "$subcatname" : "";       	
    	
    	if( !isset( $this->registry->__GET['subcategory'] ))
    	{
    		$items = $this->db->query( "select count(*) from auction_items where cat_id='$cat' and bidding_endtime  > '$now'" , "one" );    		
    	}
    	else
    	{
    		$subcat = $this->registry->__GET['subcategory'];
    		$items = $this->db->query( "select count(*) from auction_items where cat_id='$cat' and subcat_id='$subcat' and bidding_endtime  > '$now'" , "one" );   		
    	}   	
    	
    	    	
    	if( $items > 0)
    	{
    		$items = array (
	        	"auctionList_directive" => array ( "auction" , $this , "auctionList_directive" ) ,
	        	"pageination_directive" => array ( "pagination" , $this , "pageination_directive" ) ,
	            "title" => "$title",
	        	"categoryid" => $cat,
    			"order" => ( $this->session->getKey( "sortorder" ) == "ASC" ) ? "DESC" : "ASC",
	        	"subcategoryid" => isset($subcat) ? "&subcategory=$subcat" : "",
    			"itemsperpage" => $this->session->getKey( "itemsperpage" ),
    			"picsize" => $this->session->getKey( "pictureWidth" ) . $this->session->getKey( "pictureHeight" ),
    			"sortOrder" => $this->session->getKey( "sortorder" ), 
    			"sortBy" => $this->session->getKey( "sortby" )
        	);
        	$this->view = new View ( "viewauctions" );
    	}
    	else 
    	{
    		$items = array (
	        	"pageination_directive" => array ( "pagination" , $this , "pageination_directive" ) ,
	            "title" => "$title",
	        	"categoryid" => $cat,
    			"order" => ( $this->session->getKey( "sortorder" ) == "ASC" ) ? "DESC" : "ASC",
	        	"subcategoryid" =>  $this->session->getKey( "itemsperpage" ),
    			"picsize" => $this->session->getKey( "pictureWidth" ) . $this->session->getKey( "pictureHeight" ),
    			"sortOrder" => $this->session->getKey( "sortorder" ), 
    			"sortBy" => $this->session->getKey( "sortby" )     			
        	);    	
        	$this->view = new View ( "viewauctionsempty" );	
    	}   	

        $this->view->process ( $items );
        $this->view->output ();
    }
}

new ViewAuctions;
