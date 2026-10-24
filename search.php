<?php
// phpcs:ignoreFile
// jscpd:ignore-start

require_once ( dirname( __FILE__ ) . "/conf.php" );

/** @psalm-suppress UndefinedClass */ /** @psalm-suppress UndefinedClass */ class Search extends Controller
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
        	/* @phpstan-ignore-line */ /** @psalm-suppress UnresolvableInclude */ /** @psalm-suppress UnresolvableInclude */ include_once(__SITE_ROOT . "/system/locale/$lang.php");
        	$count = $this->db->query( "select count(*) from auction_items where item_header_description like '%$searchterm%' and bidding_endtime > '$now' order by bidding_endtime DESC ", "array" );
        	
        	if( !( $count > 0) )
        	{
        		/* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ echo "0 $locale[resultsfound]";
        		exit;
        	}
        	
        	$items = array (
	            "suggest_directive" => array ( "suggestions" , $this , "suggest_directive" ) 
	        );
	
	        /* @phpstan-ignore-line */ $this->view = new View ( "searchsuggest" );
	        /* @phpstan-ignore-line */ $this->view->process ( $items );
	        /* @phpstan-ignore-line */ $this->view->output ();             	

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
    
    
	/** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function pageination_directive( $match )
    {    	  	
    	$term = $this->registry->__GET['term']; 
    	$now = date('Y-m-d H:i:s', time());
    	$count = $this->db->query( "select count(*) from auction_items where item_header_description like '%$term%' and bidding_endtime  > '$now'" , "one" );
    	
    	$itemsperpage = $this->session->getKey( "itemsperpage" );
    	/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $pages = ceil( $count / $itemsperpage );    	
    	
    	$page = isset($this->registry->__GET['page']) ? $this->registry->__GET['page'] : 1;
    	
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $page) || $page<0)
    		$page = 1;
    		
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $itemsperpage))
    	{
    		$itemsperpage = 10;
    	}
    	
    	$previous = ($page == 1) ? 1 : $page - 1;
    	$next = ($page == 1) ? 2 : $page + 1;
    	$current = $page;    	
    	/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $secondlast = floor( $count / $itemsperpage ) - 1;
    	/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $last = $secondlast + 1;
    	
    	$match = preg_replace ( "/\\[term\\]/s" , $term , $match );
    	
    	/** @psalm-suppress PossiblyInvalidArgument */ /** @psalm-suppress PossiblyInvalidArgument */ $pagination = explode( "|" , $match );
    	$pagePrevious = $pagination[0];
    	$pageCurrrent = $pagination[1];
    	$pageNext = $pagination[2];
    	$pageSecondLast = $pagination[3];
    	$pageLast = $pagination[4];
    	$pageNextNext = $pagination[5];
    	
    	/* @phpstan-ignore-line */ /** @psalm-suppress PossiblyInvalidArgument */ /** @psalm-suppress PossiblyInvalidArgument */ $output = ( $page == 1 ) ? "" : preg_replace ( "/\\[previous\\]/s" , $previous , $pagePrevious );
    	/** @psalm-suppress PossiblyInvalidArgument */ /** @psalm-suppress PossiblyInvalidArgument */ $output .= preg_replace ( "/\\[current\\]/s" , $current , $pageCurrrent );
    	/* @phpstan-ignore-line */ /** @psalm-suppress PossiblyInvalidArgument */ /** @psalm-suppress PossiblyInvalidArgument */ $output .= ( ($current * $itemsperpage) < $count ) ? preg_replace ( "/\\[next\\]/s" , $next , $pageNext ) : "";
    	/* @phpstan-ignore-line */ /** @psalm-suppress InvalidOperand, InvalidArgument */ /** @psalm-suppress InvalidOperand, InvalidArgument */ $output .= ( (($secondlast * $itemsperpage) < $count) && (($secondlast * $itemsperpage) > $next * $itemsperpage) ) ? preg_replace ( "/\\[secondlast\\]/s" , $secondlast , $pageSecondLast ) : "";
    	/* @phpstan-ignore-line */ /** @psalm-suppress InvalidOperand, InvalidArgument */ /** @psalm-suppress InvalidOperand, InvalidArgument */ $output .= ( (($last * $itemsperpage) < $count) && (($last * $itemsperpage) > $next * $itemsperpage) ) ? preg_replace ( "/\\[last\\]/s" , $last , $pageLast ) : "";
		/* @phpstan-ignore-line */ /** @psalm-suppress PossiblyInvalidArgument */ /** @psalm-suppress PossiblyInvalidArgument */ $output .= ( ($current * $itemsperpage) < $count ) ? preg_replace ( "/\\[next\\]/s" , $next , $pageNextNext ) : "";
		
    	return $output;
    }
    
    
	/** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function searchList_directive ( $match )
    {
    	$term = $this->registry->__GET['term']; 
    	$itemsperpage = $this->session->getKey( "itemsperpage" );
    	$page = isset( $this->registry->__GET['page'] ) ? (($this->registry->__GET['page'] -1) * $itemsperpage) : 0; 	
    	
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $page) || $page<0)
    		$page = 0;
    		
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $itemsperpage))
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
            /** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $startprice = ($item[8]==0) ? "0.00" : $item[8];
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
			/* @phpstan-ignore-line */ /** @psalm-suppress InvalidScalarArgument */ /** @psalm-suppress InvalidScalarArgument */ $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
			/** @psalm-suppress PossiblyFalseOperand */ /** @psalm-suppress PossiblyFalseOperand */ $timeleft = $timestamp -  time();
			
			/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $days = floor( $timeleft / 60 / 60 / 24 );
			/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $hours = $timeleft / 60 / 60 % 24;
			/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $mins = $timeleft / 60 % 60;
			
			/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $sleft = "";
			
			/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $sleft = ($days > 0) ? $days = $days . " %days%, " : "";
			$sleft .= $hours ." %hours%, ";
			$sleft .= $mins ." %minutes% ";
			
			$pence = substr($maxbid, strlen($maxbid) -2, 2);
    		$pounds = substr($maxbid, 0, strlen($maxbid) -2);	
    		$maxbid = "&#36;". $pounds .".$pence";
    		
    		$pence = substr($postage, strlen($postage) -2, 2);
    		$pounds = substr($postage, 0, strlen($postage) -2);	
    		$postage = "&#36;". $pounds .".$pence";
			
    		$data = preg_replace ( "/\\[categoryid\\]/s" , $item[12] , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[subcategoryid\\]/s" , $item[13] , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[height\\]/s" , $height , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[width\\]/s" , $width , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[auctid\\]/s" , $auctionid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[img\\]/s" , $img , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[itemShort\\]/s" , $itemdesc , $data );
            /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[bids\\]/s" , $bids , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[price\\]/s" , $maxbid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[postage\\]/s" , $postage , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[timeleft\\]/s" , $sleft , $data );
            /* @phpstan-ignore-line */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[iter\\]/s" , $c , $data );
            
            /** @psalm-suppress PossiblyInvalidOperand */ /** @psalm-suppress PossiblyInvalidOperand */ $output.= $data;
            $c++;
        }
                
        return $output;
    }
    
    
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function standardSearch()
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
        	/* @phpstan-ignore-line */ $this->view = new View ( "standardSearch" );
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
        	/* @phpstan-ignore-line */ $this->view = new View ( "viewauctionsempty" );	
    	} 
    	  
    	/* @phpstan-ignore-line */ $this->view->process ( $items );
        /* @phpstan-ignore-line */ $this->view->output ();	
    }
    
    
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function advancedSearchWarning()
    {
    	print "error";
    	exit;
    }
    
    
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function advancedSearch()
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
        /* @phpstan-ignore-line */ /** @psalm-suppress UnresolvableInclude */ /** @psalm-suppress UnresolvableInclude */ include_once(__SITE_ROOT . "/system/locale/$lang.php");
        
        
        for ( $h = 0 ; $h < count( $fields ); $h++ )
        {
        	$temp = "";
        	
        	$compound = $compounds[$h];
        	$field = $fields[$h];  
        	$condition = $conditions[$h];
        	/** @psalm-suppress PossiblyInvalidArrayOffset */ /** @psalm-suppress PossiblyInvalidArrayOffset */ $value = $values[$h];
        	
        	if( $h == 0 ) // no AND or OR (first expression)
        	{
        		$temp .= ( $field > 0 && $field < 13 ) ? " {$col[$field]} " : $this->advancedSearchWarning();
        		$temp .= ( $condition > 0 && $condition < 6 ) ? " {$cons[$condition]} " : $this->advancedSearchWarning();
        		if( $condition == 1)
        		{
       				/** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidArrayOffset, PossiblyInvalidCast */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidArrayOffset, PossiblyInvalidCast */ $temp .= ( strlen($value) > 0 ) ? " '%{$values[$h]}%' " : $this->advancedSearchWarning();
        		}
        		else 
        		{
        			if ( $field == 3 ) // date field
        			{
        				/** @psalm-suppress PossiblyInvalidArrayOffset, PossiblyInvalidArgument */ /** @psalm-suppress PossiblyInvalidArrayOffset, PossiblyInvalidArgument */ $dob = explode("-",$values[$h]);
            			$year = $dob[2];
            			$month = $dob[1];
            			$day = $dob[0];
        				/** @psalm-suppress PossiblyInvalidArgument */ /** @psalm-suppress PossiblyInvalidArgument */ $temp .= ( strlen($value) > 0 ) ? " '$year-$month-$day 00:00:00' " : $this->advancedSearchWarning();
        			}
        			else if( $field == 2 || $field == 8 || $field == 9 ) // money fields
        			{
        				/** @psalm-suppress PossiblyInvalidArrayOffset, PossiblyNullArgument */ /** @psalm-suppress PossiblyInvalidArrayOffset, PossiblyNullArgument */ $values[$h] = preg_replace( '/[\$,.]/' , "" , $values[$h] );
        				/** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidCast */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidCast */ $temp .= ( strlen($value) > 0 ) ? " '{$values[$h]}' " : $this->advancedSearchWarning();
        			}
        			else
        			{
        				/** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidArrayOffset, PossiblyInvalidCast */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidArrayOffset, PossiblyInvalidCast */ $temp .= ( strlen($value) > 0 ) ? " '{$values[$h]}' " : $this->advancedSearchWarning();
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
        			/** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidArrayOffset, PossiblyInvalidCast */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidArrayOffset, PossiblyInvalidCast */ $temp .= ( strlen($value) > 0 ) ? " '%{$values[$h]}%' " : $this->advancedSearchWarning();
        		}
        		else 
        		{
        			if ( $field == 3 ) // date field
        			{
        				/** @psalm-suppress PossiblyInvalidArrayOffset, PossiblyInvalidArgument */ /** @psalm-suppress PossiblyInvalidArrayOffset, PossiblyInvalidArgument */ $dob = explode("-",$values[$h]);
            			$year = $dob[2];
            			$month = $dob[1];
            			$day = $dob[0];
        				/** @psalm-suppress PossiblyInvalidArgument */ /** @psalm-suppress PossiblyInvalidArgument */ $temp .= ( strlen($value) > 0 ) ? " '$year-$month-$day 00:00:00' " : $this->advancedSearchWarning();
        			}
        			else if( $field == 2 || $field == 8 || $field == 9 ) // money fields
        			{
        				/** @psalm-suppress PossiblyInvalidArrayOffset, PossiblyNullArgument */ /** @psalm-suppress PossiblyInvalidArrayOffset, PossiblyNullArgument */ $values[$h] = preg_replace( '/[\$,.]/' , "" , $values[$h] );
        				/** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidCast */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidCast */ $temp .= ( strlen($value) > 0 ) ? " '{$values[$h]}' " : $this->advancedSearchWarning();
        			}
        			else
        			{
        				/** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidArrayOffset, PossiblyInvalidCast */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyInvalidArrayOffset, PossiblyInvalidCast */ $temp .= ( strlen($value) > 0 ) ? " '{$values[$h]}' " : $this->advancedSearchWarning();
        			}          			
        		}
        	}    

        	$query .= $temp;
        }   
        
        $now = date('Y-m-d H:i:s', time());

        $results = $this->db->query( "$query and bidding_endtime > '$now'" , "array");
              
        $output = "";
        
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols = ",{$locale['description']},{$locale['end']} {$locale['time']},{$locale['bids']}";
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols .= (in_array(2,$fields)) ? ",{$locale['postage']}" : "";
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols .= (in_array(4,$fields)) ? ",{$locale['country']}" : "";
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols .= (in_array(5,$fields)) ? ",{$locale['seller']}" : "";
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols .= (in_array(6,$fields)) ? ",{$locale['condition']}" : "";
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols .= (in_array(8,$fields)) ? ",{$locale['starting']} {$locale['bid']}" : "";
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols .= (in_array(9,$fields)) ? ",{$locale['reserve']}" : "";
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols .= (in_array(10,$fields)) ? ",{$locale['category']}" : "";
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols .= (in_array(11,$fields)) ? ",{$locale['sub']} {$locale['category']}" : "";
        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $cols .= (in_array(12,$fields)) ? ",{$locale['status']}" : "";
        $cols .= "|";
        
        $output .= $cols;
        
        foreach( $results as $result )
        {
    		$auction_id = $result[0];    		
    		/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $price = $result[1];	
    		 	
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
    		
    		/* @phpstan-ignore-line */ /** @psalm-suppress UndefinedFunction */ /** @psalm-suppress UndefinedFunction */ $item_header_description = eregi_replace(",","",$result[10]);	 	
    		$cat_id = $result[12];	 	
    		$subcat_id = $result[13];	 	
    		$status = $result[14];	
    		$img = $this->db->query( "select pic_url from auction_pictures where auction_id='$auction_id' order by pic_id ASC limit 0,1 " , "one" );	
    		
    		$row = "";
    		
    		$img = ($img == "") ? "../blanksmall.jpg" : $img;
    		
    		$row .= "<img src='images/auction/$img' width='50' height='50'>,<a href='viewauction.php?category=$cat_id&subcategory=$subcat_id&viewid=$auction_id'>$item_header_description</a>,$bidding_endtime, $bid_history";
    		$row .= (in_array(2,$fields)) ? ",$postage" : "";
	        /* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable */ /** @psalm-suppress UndefinedVariable */ $row .= (in_array(4,$fields)) ? ",{$locale[$country_from]}" : "";
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
    
    
    /** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function suggest_directive( $match )
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
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[item_header_description\\]/s" , $item_header_description , $data );
        	      	
        	$count++;	        	
        	if( $count == 10 )
        	{
        		break;
        	}
        	
        	/** @psalm-suppress PossiblyInvalidOperand */ /** @psalm-suppress PossiblyInvalidOperand */ $output .= $data;
        }
        
        return $output;
    }
    

    /** @psalm-suppress MissingOverrideAttribute, MissingReturnType */ /** @psalm-suppress MissingOverrideAttribute, MissingReturnType */ #[\Override] public function index()
    {    	
   		$items = array (
	        "title" => "%search%" ,
	        "username" => "" ,
	        "loginfailed" => ""
	    );
	
        /* @phpstan-ignore-line */ $this->view = new View ( "advancedSearch" );
        /* @phpstan-ignore-line */ $this->view->process ( $items );
        /* @phpstan-ignore-line */ $this->view->output ();
    }
}

/** @psalm-suppress MissingDependency */ /** @psalm-suppress MissingDependency */ new Search;
// jscpd:ignore-end
