<?php

require_once ( dirname( __FILE__ ) . "/conf.php" );

class Search extends Controller
{
 
    public function __construct ()
    {
        parent::__construct ();  

        if( isset( $this->registry->__GET['ajaxsearch'] ))
        {        	
        	$searchterm = $this->registry->__GET['ajaxsearch'];
        	$now = date('Y-m-d H:i:s', time());
        	
        	$lang = $this->session->getLanguage();
        	$lang = $this->db->query( "select code from locale where id='$lang'" , "one" );
        	include_once(__SITE_ROOT . "/system/locale/$lang.php");
        	$count = $this->db->query( "select count(*) from auction_items where item_header_description like '%$searchterm%' and bidding_endtime > '$now' order by bidding_endtime DESC ", "array" );
        	
        	if( !( $count > 0) )
        	{
        		echo "0 $locale[resultsfound]";
        		exit;
        	}
        	
        	$items = array (
	            "suggest_directive" => array ( "suggestions" , $this , "suggest_directive" ) 
	        );
	
	        $this->view = new View ( "searchsuggest" );
	        $this->view->process ( $items );
	        $this->view->output ();             	

        	exit;        	
        }
        else if( isset ( $this->registry->__POST['columns'] ) && isset ( $this->registry->__POST['condition'] ) && isset ( $this->registry->__POST['vals'] ) && isset ( $this->registry->__POST['exp'] ))
        {
        	$this->advancedSearch();   
        	exit;    	
        }
        else if( isset ( $this->registry->__GET['term'] ) )
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
        	
        	$this->standardSearch();        	
        }
        else
        {        
        	$this->index ();
        }
    }
    
    
	public function pageination_directive( $match )
    {    	  	
    	$term = $this->registry->__GET['term']; 
    	$now = date('Y-m-d H:i:s', time());
    	$count = $this->db->query( "select count(*) from auction_items where item_header_description like '%$term%' and bidding_endtime  > '$now'" , "one" );
    	
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
    	
    	$match = preg_replace ( "/\\[term\\]/s" , $term , $match );
    	
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
    
    
	public function searchList_directive ( $match )
    {
    	$term = $this->registry->__GET['term']; 
    	$itemsperpage = $this->session->getKey( "itemsperpage" );
    	$page = isset( $this->registry->__GET['page'] ) ? (($this->registry->__GET['page'] -1) * $itemsperpage) : 0; 	
    	
    	if( !is_int( (int) $page) || $page<0)
    		$page = 0;
    		
    	if( !is_int( (int) $itemsperpage))
    		$itemsperpage = 10;
  	
    	$now = date('Y-m-d H:i:s', time());
    	
    	$order = $this->session->getKey( "sortorder" );
    	$sortby = $this->session->getKey( "sortby" );    	

    	if( $sortby == "price" )
    	{
    		// DIRTY HACKKKKK
    		
    		$items = $this->db->query( "SELECT * FROM auction_items a, auction_bids b WHERE a.auction_id = b.auction_id	AND a.item_header_description like '%$term%' AND a.bidding_endtime >  '$now' AND b.amount = ( SELECT MAX( amount ) FROM auction_bids WHERE auction_id = a.auction_id ) ORDER BY b.amount $order limit $page,$itemsperpage" , "array" );
    		$titems = $items;
    		$rest = $this->db->query( "select * from auction_items where item_header_description like '%$term%' and bidding_endtime  > '$now' ORDER BY starting_bid $order limit $page,$itemsperpage " , "array" );    			
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
    		$items = $this->db->query( "select * from auction_items where item_header_description like '%$term%' and bidding_endtime  > '$now' ORDER BY $sortby $order limit $page,$itemsperpage " , "array" );
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
    
    
    private function standardSearch()
    {
    	$term = $this->registry->__GET['term'];    	
    	$now = date('Y-m-d H:i:s', time());
    	
    	$items = $this->db->query( "select count(*) from auction_items where item_header_description like '%$term%' and bidding_endtime  > '$now'" , "one" );   	
    		    	    	
    	if( $items > 0)
    	{
    		$items = array (
	        	"searchList_directive" => array ( "search" , $this , "searchList_directive" ) ,
	        	"pageination_directive" => array ( "pagination" , $this , "pageination_directive" ) ,
	            "title" => "%search% %results% $term",
    			"order" => ( $this->session->getKey( "sortorder" ) == "ASC" ) ? "DESC" : "ASC",
    			"itemsperpage" => $this->session->getKey( "itemsperpage" ),
    			"picsize" => $this->session->getKey( "pictureWidth" ) . $this->session->getKey( "pictureHeight" ),
    			"sortOrder" => $this->session->getKey( "sortorder" ), 
    			"sortBy" => $this->session->getKey( "sortby" ),
    			"term" => $term
        	);
        	$this->view = new View ( "standardSearch" );
    	}
    	else 
    	{
    		$items = array (
	        	"pageination_directive" => array ( "pagination" , $this , "pageination_directive" ) ,
	            "title" => "%search% $term",
    			"order" => ( $this->session->getKey( "sortorder" ) == "ASC" ) ? "DESC" : "ASC",
    			"picsize" => $this->session->getKey( "pictureWidth" ) . $this->session->getKey( "pictureHeight" ),
    			"sortOrder" => $this->session->getKey( "sortorder" ), 
    			"sortBy" => $this->session->getKey( "sortby" ),
    			"term" => $term    			
        	);    	
        	$this->view = new View ( "viewauctionsempty" );	
    	} 
    	  
    	$this->view->process ( $items );
        $this->view->output ();	
    }
    
    
    private function advancedSearchWarning()
    {
    	print "error";
    	exit;
    }
    
    
    private function advancedSearch()
    {
    	$fields = $this->registry->__POST['columns'];
        $conditions = $this->registry->__POST['condition'];
        $values = $this->registry->__POST['vals'];
        $compounds = $this->registry->__POST['exp'];

        $query = "SELECT * FROM auction_items WHERE ";

        $comp = array();
        $comp[1] = "AND";
        $comp[2] = "OR";
        
        $col = array();
        $col[1] = "item_header_description";
        $col[2] = "postage";
        $col[3] = "bidding_endtime";
        $col[4] = "country_from";
        $col[5] = "sellerid";
        $col[6] = "condition";
        $col[7] = "bid_history";
        $col[8] = "starting_bid";
        $col[9] = "reserve";
        $col[10] = "cat_id";
        $col[11] = "subcat_id";
        $col[12] = "status";
        
        $cons = array();
        $cons[1] = "LIKE";
        $cons[2] = ">";
        $cons[3] = "<";
        $cons[4] = "=";
        $cons[5] = "!=";
        
        $lang = $this->session->getLanguage();
        $lang = $this->db->query( "select code from locale where id='$lang'" , "one" );
        include_once(__SITE_ROOT . "/system/locale/$lang.php");
        
        
        for ( $h = 0 ; $h < count( $fields ); $h++ )
        {
        	$temp = "";
        	
        	$compound = $compounds[$h];
        	$field = $fields[$h];  
        	$condition = $conditions[$h];
        	$value = $values[$h];
        	
        	if( $h == 0 ) // no AND or OR (first expression)
        	{
        		$temp .= ( $field > 0 && $field < 13 ) ? " {$col[$field]} " : $this->advancedSearchWarning();
        		$temp .= ( $condition > 0 && $condition < 6 ) ? " {$cons[$condition]} " : $this->advancedSearchWarning();
        		if( $condition == 1)
        		{
       				$temp .= ( strlen($value) > 0 ) ? " '%{$values[$h]}%' " : $this->advancedSearchWarning();
        		}
        		else 
        		{
        			if ( $field == 3 ) // date field
        			{
        				$dob = explode("-",$values[$h]);
            			$year = $dob[2];
            			$month = $dob[1];
            			$day = $dob[0];
        				$temp .= ( strlen($value) > 0 ) ? " '$year-$month-$day 00:00:00' " : $this->advancedSearchWarning();
        			}
        			else if( $field == 2 || $field == 8 || $field == 9 ) // money fields
        			{
        				$values[$h] = preg_replace( '/[\$,.]/' , "" , $values[$h] );
        				$temp .= ( strlen($value) > 0 ) ? " '{$values[$h]}' " : $this->advancedSearchWarning();
        			}
        			else
        			{
        				$temp .= ( strlen($value) > 0 ) ? " '{$values[$h]}' " : $this->advancedSearchWarning();
        			}        			
        		}
        	}
        	else // AND or OR (first expression)
        	{
        		$temp .= ( $compound == 1 || $compound == 2 ) ? " {$comp[$compound]} " : $this->advancedSearchWarning();
        		$temp .= ( $field > 0 && $field < 13 ) ? " {$col[$field]} " : $this->advancedSearchWarning();
        		$temp .= ( $condition > 0 && $condition < 6 ) ? " {$cons[$condition]} " : $this->advancedSearchWarning();
        		if( $condition == 1)
        		{
        			$temp .= ( strlen($value) > 0 ) ? " '%{$values[$h]}%' " : $this->advancedSearchWarning();
        		}
        		else 
        		{
        			if ( $field == 3 ) // date field
        			{
        				$dob = explode("-",$values[$h]);
            			$year = $dob[2];
            			$month = $dob[1];
            			$day = $dob[0];
        				$temp .= ( strlen($value) > 0 ) ? " '$year-$month-$day 00:00:00' " : $this->advancedSearchWarning();
        			}
        			else if( $field == 2 || $field == 8 || $field == 9 ) // money fields
        			{
        				$values[$h] = preg_replace( '/[\$,.]/' , "" , $values[$h] );
        				$temp .= ( strlen($value) > 0 ) ? " '{$values[$h]}' " : $this->advancedSearchWarning();
        			}
        			else
        			{
        				$temp .= ( strlen($value) > 0 ) ? " '{$values[$h]}' " : $this->advancedSearchWarning();
        			}          			
        		}
        	}    

        	$query .= $temp;
        }   
        
        $now = date('Y-m-d H:i:s', time());

        $results = $this->db->query( "$query and bidding_endtime > '$now'" , "array");
              
        $output = "";
        
        $cols = ",{$locale['description']},{$locale['end']} {$locale['time']},{$locale['bids']}";
        $cols .= (in_array(2,$fields)) ? ",{$locale['postage']}" : "";
        $cols .= (in_array(4,$fields)) ? ",{$locale['country']}" : "";
        $cols .= (in_array(5,$fields)) ? ",{$locale['seller']}" : "";
        $cols .= (in_array(6,$fields)) ? ",{$locale['condition']}" : "";
        $cols .= (in_array(8,$fields)) ? ",{$locale['starting']} {$locale['bid']}" : "";
        $cols .= (in_array(9,$fields)) ? ",{$locale['reserve']}" : "";
        $cols .= (in_array(10,$fields)) ? ",{$locale['category']}" : "";
        $cols .= (in_array(11,$fields)) ? ",{$locale['sub']} {$locale['category']}" : "";
        $cols .= (in_array(12,$fields)) ? ",{$locale['status']}" : "";
        $cols .= "|";
        
        $output .= $cols;
        
        foreach( $results as $result )
        {
    		$auction_id = $result[0];    		
    		$price = $result[1];	
    		 	
    		// postage
    		$postage = $result[2];
    		$pence = substr($postage, strlen($postage) -2, 2);
    		$pounds = substr($postage, 0, strlen($postage) -2);	    			
			$postage = "\$$pounds.$pence";	 
    			
    		$bidding_endtime = $result[3];	 	
    		$country_from = $this->db->query( "select country from countries where id='{$result[4]}'" , "one");	 	
    		$sellerid = $this->db->query( "select username from ou_users where userid='{$result[5]}'" , "one");	 	
    		$condition = $result[6];	 	
    		$bid_history = $result[7];	 

    		// starting bid
    		$starting_bid = $result[8];	 
    		$pence = substr($starting_bid, strlen($starting_bid) -2, 2);
    		$pounds = substr($starting_bid, 0, strlen($starting_bid) -2);	    			
			$starting_bid = "\$$pounds.$pence";	

    		//reserve
    		$reserve = $result[9];	 
			$pence = substr($reserve, strlen($reserve) -2, 2);
    		$pounds = substr($reserve, 0, strlen($reserve) -2);	    			
			$reserve = "\$$pounds.$pence";
    		
    		$item_header_description = eregi_replace(",","",$result[10]);	 	
    		$cat_id = $result[12];	 	
    		$subcat_id = $result[13];	 	
    		$status = $result[14];	
    		$img = $this->db->query( "select pic_url from auction_pictures where auction_id='$auction_id' order by pic_id ASC limit 0,1 " , "one" );	
    		
    		$row = "";
    		
    		$img = ($img == "") ? "../blanksmall.jpg" : $img;
    		
    		$row .= "<img src='images/auction/$img' width='50' height='50'>,<a href='viewauction.php?category=$cat_id&subcategory=$subcat_id&viewid=$auction_id'>$item_header_description</a>,$bidding_endtime, $bid_history";
    		$row .= (in_array(2,$fields)) ? ",$postage" : "";
	        $row .= (in_array(4,$fields)) ? ",{$locale[$country_from]}" : "";
	        $row .= (in_array(5,$fields)) ? ",$sellerid" : "";
	        $row .= (in_array(6,$fields)) ? ",$condition" : "";
	        $row .= (in_array(8,$fields)) ? ",$starting_bid" : "";
	        $row .= (in_array(9,$fields)) ? ",$reserve" : "";
	        $row .= (in_array(10,$fields)) ? ",$cat_id" : "";
	        $row .= (in_array(11,$fields)) ? ",$subcat_id" : "";
	        $row .= (in_array(12,$fields)) ? ",$status" : "";
        	$row .= "|";
        	        	
        	$output .= $row;        	    		
        }
        
        print $output;
        exit;
    }
    
    
    public function suggest_directive( $match )
    {
    	$searchterm = $this->registry->__GET['ajaxsearch'];
        $now = date('Y-m-d H:i:s', time());
        	
        $items = $this->db->query( "select auction_id, item_header_description from auction_items where item_header_description like '%$searchterm%' and bidding_endtime > '$now' order by bidding_endtime DESC ", "array" );
        $count = 0;
    	
    	$output = "";
    	
    	foreach( $items as $item )
        {
        	$data = $match;
        	$auctionid = $item[0];
        	$item_header_description = $item[1];
        	
        	$img = $this->db->query( "select MAX(pic_url) from auction_pictures where auction_id='$auctionid'", "one" );
            $img = ($img == "") ? "../blanksmall.jpg" : $img;
        	
            $data = preg_replace ( "/\\[img\\]/s" , $img , $data );
            $data = preg_replace ( "/\\[item_header_description\\]/s" , $item_header_description , $data );
        	      	
        	$count++;	        	
        	if( $count == 10 )
        	{
        		break;
        	}
        	
        	$output .= $data;
        }
        
        return $output;
    }
    

    public function index()
    {    	
   		$items = array (
	        "title" => "%search%" ,
	        "username" => "" ,
	        "loginfailed" => ""
	    );
	
        $this->view = new View ( "advancedSearch" );
        $this->view->process ( $items );
        $this->view->output ();
    }
}

new Search;
