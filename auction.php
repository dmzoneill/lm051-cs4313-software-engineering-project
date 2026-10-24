<?php
// phpcs:ignoreFile
// jscpd:ignore-start

require_once ( dirname( __FILE__ ) . "/conf.php" );

/** @psalm-suppress UndefinedClass */ /** @psalm-suppress UndefinedClass */ class Auction extends Controller
{
 
    public function __construct ()
    {
        parent::__construct ();  
        
        
        if( $this->session->getKey( "sortby" ) == false || isset( $this->registry->__GET['sortby'] ) )
        {
        	if ( isset($this->registry->__GET['sortby']) )
        	{
        		if( $this->registry->__GET['sortby'] != "bidding_endtime" && $this->registry->__GET['sortby'] != "bid_history" && $this->registry->__GET['sortby'] != "price" && $this->registry->__GET['sortby'] != "reserve" && $this->registry->__GET['sortby'] != "amount" )
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

        if($this->session->isLoggedOn() == false)
        {
            header('Location: login.php');
            exit;
        }
        
        if( isset($this->registry->__GET['ajax']) )
        {
	        if( isset($this->registry->__GET['categoryid']) )
	        {
	        	$this->getAjaxSubCategories();
	        }
	        exit;
        }        
    	else if( isset ( $_FILES['uploadfile'] ) )
        {
            include("system/Uploader.php");
            /* @phpstan-ignore-line */ /** @psalm-suppress UnusedVariable, TooManyArguments */ /** @psalm-suppress UnusedVariable, TooManyArguments */ $up = new Uploader("auction"); 
            exit;
        }
    	else if( isset($this->registry->__GET['placebid']) && isset($this->registry->__GET['auctionid']) && isset($this->registry->__GET['bid']) )
        {
        	$this->attemptBid();
            exit;
        }
    	else if( isset($this->registry->__GET['agreement']) && isset($this->registry->__GET['submit']) )
        {
            $this->doAuction();
        }
        else if( isset($this->registry->__GET['agreement']) )
        {
            $this->showAuctionForm();
        }
    	else if( isset($this->registry->__GET['watchitem']) )
        {
            $this->doWatchItem();
        }
    	else if( isset($this->registry->__GET['watching']) )
        {
        	if(	isset( $this->registry->__GET['stop'] ))
        	{
        		$this->stopWatchItem();
        	}
            $this->showWatching();
        }
    	else if( isset($this->registry->__GET['mybids']) )
        {        	
            $this->showUserBids();
        }
    	else if( isset($this->registry->__GET['show']) )
        {
            $this->showUserAuctions();
        }
	    else if( isset($this->registry->__GET['feedback']) )
        {
            $this->feedback();
        }
        else
        {
            $this->index ();
        }
    }
    
    
	/** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ private function convert_datetime($str) 
	{	
		list($date, $time) = explode(' ', $str);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);		
		/* @phpstan-ignore-line */ /** @psalm-suppress InvalidScalarArgument */ /** @psalm-suppress InvalidScalarArgument */ $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
				
		return $timestamp;
	}
    
    
	/** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function showUserBids()
    {
    	
    	$title = "%my% %bids% " . $this->session->getCurrentUser() ." ";
    	
    	$items = array (
        	"bidList_directive" => array ( "auction" , $this , "bidList_directive" ) ,
        	"userbidsPageination_directive" => array ( "pagination" , $this , "userbidsPageination_directive" ) ,
            "title" => "$title",        	
    		"order" => ( $this->session->getKey( "sortorder" ) == "ASC" ) ? "DESC" : "ASC",
    		"itemsperpage" => $this->session->getKey( "itemsperpage" ),
    		"picsize" => $this->session->getKey( "pictureWidth" ) . $this->session->getKey( "pictureHeight" ),
    		"sortOrder" => $this->session->getKey( "sortorder" ), 
    		"sortBy" => $this->session->getKey( "sortby" )
        );
        /* @phpstan-ignore-line */ $this->view = new View ( "mybids" );   	
        /* @phpstan-ignore-line */ $this->view->process ( $items );
        /* @phpstan-ignore-line */ $this->view->output ();
        
        exit;
    }
        
    
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function showUserAuctions()
    {
    	
    	$title = "%auction% %listing% " . $this->session->getCurrentUser() ." ";
    	
    	$items = array (
        	"auctionList_directive" => array ( "auction" , $this , "auctionList_directive" ) ,
        	"pageination_directive" => array ( "pagination" , $this , "pageination_directive" ) ,
            "title" => "$title",        	
    		"order" => ( $this->session->getKey( "sortorder" ) == "ASC" ) ? "DESC" : "ASC",
    		"itemsperpage" => $this->session->getKey( "itemsperpage" ),
    		"picsize" => $this->session->getKey( "pictureWidth" ) . $this->session->getKey( "pictureHeight" ),
    		"sortOrder" => $this->session->getKey( "sortorder" ), 
    		"sortBy" => $this->session->getKey( "sortby" )
        );
        /* @phpstan-ignore-line */ $this->view = new View ( "myauctionlist" );   	
        /* @phpstan-ignore-line */ $this->view->process ( $items );
        /* @phpstan-ignore-line */ $this->view->output ();
        
        exit;
    }
    
    
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function getAjaxSubCategories()
    {
    	$subcats = $this->db->query( "select * from auction_sub_categories where cat_id = '".$this->registry->__GET['categoryid']."' order by name ASC" , "array" );

    	$lang = $this->session->getLanguage();
        $lang = $this->db->query( "select code from locale where id='$lang'" , "one" );
        /* @phpstan-ignore-line */ /** @psalm-suppress UnresolvableInclude */ /** @psalm-suppress UnresolvableInclude */ include_once(__SITE_ROOT . "/system/locale/$lang.php");
    	
    	$cats = "";   	   	
    	
    	
    	foreach($subcats as $subcat)
    	{
    		$key = preg_replace("/%/","",$subcat[1]);
    		/* @phpstan-ignore-line */ /** @psalm-suppress UndefinedVariable, PossiblyNullArrayOffset */ /** @psalm-suppress UndefinedVariable, PossiblyNullArrayOffset */ $catname = preg_replace( "/$subcat[1]/" , $locale[$key] , $subcat[1] );
    		/** @psalm-suppress PossiblyInvalidOperand */ /** @psalm-suppress PossiblyInvalidOperand */ $cats .= $subcat[0] . "|" . $catname . ",";	
    	}
    	
    	echo $cats;
    }
        
    
	/** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function doAuction()
    {
    	if( !isset( $this->registry->__POST['category'] ) || $this->registry->__POST['category'] ==-1 )
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-category'] = "%select%";
    		
    	if( !isset( $this->registry->__POST['subcategory'] )  || $this->registry->__POST['subcategory'] ==-1 )
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-subcategory'] = "%select%";
    		
    	if( !isset( $this->registry->__POST['itemname'] ) || strlen( $this->registry->__POST['itemname'] ) < 20 )
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-itemname'] = "%tooshort%";
    		
    	if( !isset( $this->registry->__POST['description'] ) || strlen( $this->registry->__POST['description'] ) < 200 )
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-description'] = "%tooshort%";
    		
    	/** @psalm-suppress RiskyTruthyFalsyComparison */ /** @psalm-suppress RiskyTruthyFalsyComparison */ if( !isset( $this->registry->__POST['reserve'] ) || !stristr( $this->registry->__POST['reserve'] , "$" ) ) 
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-reserve'] = "%tooshort%";
    	
    	/** @psalm-suppress RiskyTruthyFalsyComparison */ /** @psalm-suppress RiskyTruthyFalsyComparison */ if( !isset( $this->registry->__POST['startingbid'] )  || !stristr( $this->registry->__POST['startingbid'] , "$" ) )
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-startingbid'] = "%tooshort%";
    		
    	/** @psalm-suppress RiskyTruthyFalsyComparison */ /** @psalm-suppress RiskyTruthyFalsyComparison */ if( !isset( $this->registry->__POST['postage'] ) || !stristr( $this->registry->__POST['postage'] , "$" ) )
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-postage'] = "%tooshort%";
    		
    	if( !isset( $this->registry->__POST['datepick'] ) || strlen( $this->registry->__POST['datepick'] ) < 7 )
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-datepick'] = "%tooshort%";
    		
    	/** @psalm-suppress RiskyTruthyFalsyComparison */ /** @psalm-suppress RiskyTruthyFalsyComparison */ if( !isset( $this->registry->__POST['starttime'] ) || !stristr( $this->registry->__POST['starttime'] , ":" ) )
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-starttime'] = "%tooshort%";
    		
    	/** @psalm-suppress RiskyTruthyFalsyComparison */ /** @psalm-suppress RiskyTruthyFalsyComparison */ if( !isset( $this->registry->__POST['imagefiles'] ) || !stristr( $this->registry->__POST['imagefiles'] , "png" ) )
    		/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $error['auction-imagefiles'] = "%tooshort%";
    		
    	if( isset($error['auction-category']) || isset($error['auction-subcategory']) || isset($error['auction-itemname']) || isset($error['auction-description']) || isset($error['auction-reserve']) || isset($error['auction-startingbid']) || isset($error['auction-postage']) || isset($error['auction-datepick']) || isset($error['auction-starttime']) || isset($error['auction-imagefiles']))
    	{
    		 /** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $items = array (
	            "title" => "%auction% %item% %error%",
	        	"category" => "",
	        	"subcategory" => "",
	        	"itemname" => isset($this->registry->__POST['itemname']) ? $this->registry->__POST['itemname'] : "",
	        	"description" => isset($this->registry->__POST['description']) ? $this->registry->__POST['description'] : "",
	        	"reserve" => isset($this->registry->__POST['reserve']) ? $this->registry->__POST['reserve'] : "",
	        	"startingbid" => isset($this->registry->__POST['startingbid']) ? $this->registry->__POST['startingbid'] : "",
	        	"postage" => isset($this->registry->__POST['postage']) ? $this->registry->__POST['postage'] : "",
	        	"datepick" => isset($this->registry->__POST['datepick']) ? $this->registry->__POST['datepick'] : "",
	        	"starttime" => isset($this->registry->__POST['starttime']) ? $this->registry->__POST['starttime'] : "",
	        	"imagefiles" => isset($this->registry->__POST['imagefiles']) ? $this->registry->__POST['imagefiles'] : "",
	        	/* @phpstan-ignore-line */ "auction-category" => isset($error['auction-category']) ? $error['auction-category'] : "",
	        	"auction-subcategory" => isset($error['auction-subcategory']) ? $error['auction-subcategory'] : "",
	        	"auction-itemname" => isset($error['auction-itemname']) ? $error['auction-itemname'] : "",
	        	"auction-description" => isset($error['auction-description']) ? $error['auction-description'] : "",
	        	"auction-reserve" => isset($error['auction-reserve']) ? $error['auction-reserve'] : "",
	        	"auction-startingbid" => isset($error['auction-startingbid']) ? $error['auction-startingbid'] : "",
	        	"auction-postage" => isset($error['auction-postage']) ? $error['auction-postage'] : "",
	        	"auction-datepick" => isset($error['auction-datepick']) ? $error['auction-datepick'] : "",
	        	"auction-starttime" => isset($error['auction-starttime']) ? $error['auction-starttime'] : "",
	        	"auction-imagefiles" => isset($error['auction-imagefiles']) ? $error['auction-imagefiles'] : ""
        	);
        	
        	/* @phpstan-ignore-line */ $this->view = new View ( "addauction" );
        	/* @phpstan-ignore-line */ $this->view->process ( $items );
        	/* @phpstan-ignore-line */ $this->view->output ();      		
    	}
    	else
    	{
    		$postage = preg_replace( '/[\$,.]/' , "" , $this->registry->__POST['postage'] );    		
    		$reserve = preg_replace( '/[\$,.]/' , "" , $this->registry->__POST['reserve'] );
    		$startingbid = preg_replace( '/[\$,.]/' , "" , $this->registry->__POST['startingbid'] );
    		
    		$shortDesc = $this->registry->__POST['itemname'];
    		$longDesc = strip_tags( $this->registry->__POST['description'] , '<p><a><br><b><h1><h2><h3><h4><h5><h6><ul><ol><li>' );
    		$cat = $this->registry->__POST['category'];
    		$subcat = $this->registry->__POST['subcategory'];
    		
    		$dd = explode( "-" , $this->registry->__POST['datepick'] );
            $year = $dd[2];
            $month = $dd[1];
            $day = $dd[1];
            $tt = explode( ":" ,$this->registry->__POST['starttime'] );
            $endtime = "$year-$month-$day " . $tt[0] . ":" . $tt[1] . ":00";
    		
            $country = $this->db->query( "select country from ou_users where username='" . $this->session->getCurrentUser() . "'" , "one");
            $userid = $this->session->getCurrentUserID();		
            /** @psalm-suppress PossiblyInvalidCast */ /** @psalm-suppress PossiblyInvalidCast */ $query = "insert into auction_items values( null , '0' , '$postage' , '$endtime' , '$country' , '$userid' , '1' , '1' , '$startingbid' , '$reserve' , '$shortDesc' , '$longDesc' , '$cat' , '$subcat' , '1' )";
    		/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $insert_auction = $this->db->query( $query , "one" ); 
    		$id = $this->db->query( "select max(auction_id) from auction_items" , "one" );
    		   		
    		$imgs = explode( "," , $this->registry->__POST['imagefiles'] );
    	 	for($t = 0 ; $t < count($imgs) -1; $t++)
            {
            	$ff =  $this->db->query( "select max(pic_id) from auction_pictures" , "one" );
            	$imgid = ($ff == "") ? 1 : $ff  ;
            	/* @phpstan-ignore-line */ @copy ( __SITE_ROOT . "/images/auction/temp/" . $imgs[$t] , __SITE_ROOT . "/images/auction/" . $imgid .".png" );            	
            	/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $imgsrc = $this->db->query( "insert into auction_pictures values( null , '$id' , '$imgid.png' )" , "one");            	
            }              

            $id = $this->db->query("select max(auction_id) from auction_items","one");
            
			/** @psalm-suppress PossiblyUndefinedVariable */ /** @psalm-suppress PossiblyUndefinedVariable */ $items = array (
	            "title" => "%auction% %submitted%",	  
				/* @phpstan-ignore-line */ "img" => "$imgid.png",
				"itemtitle" => "$shortDesc",
				"id" => "$id"
        	);
			
			/* @phpstan-ignore-line */ $this->view = new View ( "auctionsubmitted" );
        	/* @phpstan-ignore-line */ $this->view->process ( $items );
        	/* @phpstan-ignore-line */ $this->view->output ();             
    	}          
    }
    
    
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function showAuctionForm()
    {
        $items = array (
            "title" => "%auction% %item%",
        	"category" => "",
        	"subcategory" => "",
        	"itemname" => "",
        	"description" => "",
        	"reserve" => "0.00",
        	"startingbid" => "0.00",
        	"postage" => "0.00",
        	"datepick" => "",
        	"starttime" => "",
        	"imagefiles" => "",
        	"auction-category" => "",
        	"auction-subcategory" => "",
        	"auction-itemname" => "",
        	"auction-description" => "",
        	"auction-reserve" => "",
        	"auction-startingbid" => "",
        	"auction-postage" => "",
        	"auction-datepick" => "",
        	"auction-starttime" => "",
        	"auction-imagefiles" => ""
        );

        /* @phpstan-ignore-line */ $this->view = new View ( "addauction" );
        /* @phpstan-ignore-line */ $this->view->process ( $items );
        /* @phpstan-ignore-line */ $this->view->output ();        
    }
    
    
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function feedback ()
    {
    	if( isset ( $this->registry->__GET['auctionid'] ) )
    	{
    		$actid = $this->registry->__GET['auctionid'];
    		$winner = $this->db->query( "select MAX(amount), user_id from auction_bids where auction_id='$actid'" , "row" );
    		
    		if( count( $winner ) < 1 )
    		{
    			// no bids for this item
    			header('Location:  auction.php?mybids=all');
    		}
    		else
    		{
    			$now = date('Y-m-d H:i:s', time());
    			$exists = $this->db->query( "select count(*) from auction_items where auction_id='$actid' and bidding_endtime < '$now'" , "one" );
    			
    			if( $exists > 0 )
    			{
    				// auction finished
					$user = $this->db->query( "select username from ou_users where userid='{$winner[1]}'" , "one" );
					
					if( $user != $this->session->getCurrentUser() )
					{
						// user did not win this item!
						header('Location:  auction.php?mybids=all');
					}
					
					$feedbackexists = $this->db->query( "select count(*) from auction_feedback where auction_id='$actid'" , "one" );
					
					if( $feedbackexists > 0 )
					{
						header('Location:  auction.php?mybids=all');
						exit;
					}
    				
    				if( isset( $this->registry->__POST['myfeedback'] ) )
		    		{
						// submit feedback
						$data = $this->registry->__POST['feedval'];
						$product = ($data[0] > 0 && $data[0] < 6) ? $data[0] : 3;
						$prompt = ($data[1] > 0 && $data[1] < 6) ? $data[1] : 3;
						$comm = ($data[2] > 0 && $data[2] < 6) ? $data[2] : 3;
						$overall = ($data[3] > 0 && $data[3] < 6) ? $data[3] : 3;
						$msg = isset( $this->registry->__POST['message'] ) ? $this->registry->__POST['message'] : "";
						
						$seller = $this->db->query( "select sellerid from auction_items where auction_id='$actid'" , "one" );
		    			/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $insert = $this->db->query( "insert into auction_feedback values( null, '$actid', '{$winner[1]}', '$seller', '$product', '$prompt', '$comm', '$overall', '$msg')" , "one" );
		    			
		    			print "true"; 
		    			exit;
		    		}
		    		else
		    		{    		
		    			$item = $this->db->query( "select item_header_description from auction_items where auction_id='$actid'" , "one" );
		    			
		    			// feedback form		    			
			    		$title = "%leavefeedback% ";			    	
			    		$items = array (
				        	"feedback_directive" => array ( "feedback" , $this , "feedback_directive" ) ,
				        	"feedbackPageination_directive" => array ( "pagination" , $this , "feedbackPageination_directive" ) ,
				            "title" => "$title",    
			    			"item" => $item,    
			    			"auctionid" => $actid,  	
				    		"order" => ( $this->session->getKey( "sortorder" ) == "ASC" ) ? "DESC" : "ASC",
				    		"itemsperpage" => $this->session->getKey( "itemsperpage" ),
				    		"picsize" => $this->session->getKey( "pictureWidth" ) . $this->session->getKey( "pictureHeight" ),
				    		"sortOrder" => $this->session->getKey( "sortorder" ), 
				    		"sortBy" => $this->session->getKey( "sortby" )
				        );
				        /* @phpstan-ignore-line */ $this->view = new View ( "feedbackform" );   	
				        /* @phpstan-ignore-line */ $this->view->process ( $items );
				        /* @phpstan-ignore-line */ $this->view->output ();
		    		}
    			}
    			else
    			{
    				// auction not finished
    				header('Location:  auction.php?mybids=all');
    			}
    		}    		
    	}
    	else
    	{    	
    		$title = "%feedback%";
    	
    		$items = array (
	        	"feedback_directive" => array ( "feedback" , $this , "feedback_directive" ) ,
	        	"feedbackPageination_directive" => array ( "pagination" , $this , "feedbackPageination_directive" ) ,
	            "title" => "$title",        	
	    		"order" => ( $this->session->getKey( "sortorder" ) == "ASC" ) ? "DESC" : "ASC",
	    		"itemsperpage" => $this->session->getKey( "itemsperpage" ),
	    		"picsize" => $this->session->getKey( "pictureWidth" ) . $this->session->getKey( "pictureHeight" ),
	    		"sortOrder" => $this->session->getKey( "sortorder" ), 
	    		"sortBy" => $this->session->getKey( "sortby" )
	        );
	        /* @phpstan-ignore-line */ $this->view = new View ( "feedback" );   	
	        /* @phpstan-ignore-line */ $this->view->process ( $items );
	        /* @phpstan-ignore-line */ $this->view->output ();
    	}
    	exit;
    }

    
	/** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function stopWatchItem ()
    {
    	$auctionID = $this->registry->__GET['stop'];
    	$count = $this->db->query( "select count(*) from auction_items where auction_id = '$auctionID'" , "one" );    	
  	
    	if( $count < 1 )
    	{
    		header('Location: index.php');    		
    	}
    	else
    	{
    		$userid = $this->session->getCurrentUserID();
    		$watching = $this->db->query( "select count(*) from item_watching where auction_id = '$auctionID' and user_id='$userid'" , "one" );
    		
    		if( $watching < 1)
    		{
    			header('Location: auction.php?watching=all');
    		}
    		else
    		{
    			$this->db->query( "delete from item_watching where auction_id = '$auctionID' and user_id='$userid'" , "one" );    			
    			header('Location: auction.php?watching=all');
    		}    		
    	}
    	exit;
    }
    
    
	/** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function doWatchItem ()
    {
    	$auctionID = $this->registry->__GET['watchitem'];
    	$count = $this->db->query( "select count(*) from auction_items where auction_id = '$auctionID'" , "one" );    	
  	
    	if( $count == "")
    	{
    		header('Location: index.php');    		
    	}
    	else
    	{
    		$userid = $this->session->getCurrentUserID();
    		$auction = $this->db->query( "select cat_id, subcat_id from item_auctions where auction_id = '$auctionID'" , "row" );
    		$watching = $this->db->query( "select count(*) from item_watching where auction_id = '$auctionID' and user_id='$userid'" , "one" );
    		
    		if( $watching < 1)
    		{
    			$this->db->query( "insert into item_watching values( null, '$auctionID', '$userid' )" , "one" );
    			header('Location: viewauction.php?category='.$auction[0].'&subcategory='.$auction[1].'&viewid='.$auctionID);
    		}
    		else
    		{
    			header('Location: viewauction.php?category='.$auction[0].'&subcategory='.$auction[1].'&viewid='.$auctionID);	
    		}    		
    	}
    	exit;
    }
    
    
    /** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function attemptBid()
    {
    	$bid = preg_replace( '/[\$,.]/' , "" , $this->registry->__GET['bid'] );
    	$auctionID = $this->registry->__GET['auctionid'];
    	
    	$item = $this->db->query( "select * from auction_items where auction_id='$auctionID'" , "array" );    	
   	
    	if( count($item) > 0)
    	{    		
    		$item = $item[0];
    		$auction_id = $item[0];	
    		/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $price = $item[1];	 	
    		$postage = $item[2];	 	
    		$bidding_endtime = $item[3];	 	
    		/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $country_from = $item[4];	 	
    		$sellerid = $item[5];	 	
    		$condition = $item[6];	 	
    		$bid_history = $item[7];	 	
    		$starting_bid = $item[8];	 	
    		/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $reserve = $item[9];	 	
    		$item_header_description = $item[10];	 	
    		$item_full_description = $item[11];
    		$item_full_description = str_replace(array("\r\n", "\r", "\n", "\t"), '<br />', $item_full_description);	 	
    		$item_full_description = str_replace(array("\\r\\n", "\\r", "\\n", "\\t"), '<br />', $item_full_description);
    		/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $cat_id = $item[12];	 	
    		/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $subcat_id = $item[13];	 	
    		/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $status = $item[14];	
    		$img = $this->db->query( "select pic_url from auction_pictures where auction_id='$auction_id' order by pic_id ASC limit 0,1 " , "one" );
    		
    		$idlang = $this->session->getLanguage();
    		$langCode = $this->db->query( "select code from locale where id='$idlang'" , "one" );
    		
    		$currentbid = $this->db->query( "select MAX(amount) from auction_bids where auction_id='$auctionID'" , "one" );
    		
    		if( $currentbid == "" )
    		{
    			/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $currentbid = "\\$0.00";
    			$nextbid = $starting_bid;
    		}
    		else 
    		{    		
    			/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $pence = substr($currentbid, strlen($currentbid) -2, 2);
    			/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $pounds = substr($currentbid, 0, strlen($currentbid) -2);	    			

    			$nextbid = $starting_bid + $currentbid;
    			/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $currentbid = "\\$$pounds.$pence";
    		}
    		   		
    		if( $this->convert_datetime( $bidding_endtime ) <= time() )
        	{
        		header('Location: viewauction.php?viewid='.$auctionID);        		
        		exit;
        	}
        	else
        	{        	
        		if( $bid < $nextbid)
        		{    	    				
    				/** @psalm-suppress PossiblyInvalidOperand */ /** @psalm-suppress PossiblyInvalidOperand */ header('Location: viewauction.php?viewid='.$auctionID.'&biderror=true&lastbid='.$bid); 	        		
	        		exit;
    			}
    			else
    			{
    				$now = date('Y-m-d H:i:s', time());
    				$userid = $this->session->getCurrentUserID();
    				/** @psalm-suppress PossiblyInvalidCast */ /** @psalm-suppress PossiblyInvalidCast */ $this->db->query( "insert into auction_bids values(null,'$userid','$bid','$auctionID','$now')" ,"one" );
    				
    				$pence = substr($nextbid, strlen($nextbid) -2, 2);
    				$pounds = substr($nextbid, 0, strlen($nextbid) -2);
    				
    				/** @psalm-suppress PossiblyInvalidCast, PossiblyInvalidArgument, DuplicateArrayKey */ /** @psalm-suppress PossiblyInvalidCast, PossiblyInvalidArgument, DuplicateArrayKey */ $items = array (
		        		"imageList_directive" => array ( "images" , $this , "imageList_directive" ) ,
		            	"title" => $item_header_description,
		    			"img" => "$img",
		    			"item_header_description" => "$item_header_description",
		    			"condition" => "$condition",
		    			"timeleft" => "$bidding_endtime",	
		    			"bidcount" => "$bid_history",
		    			"startingbid" => "$starting_bid",
		    			"postage" => "$postage",
		    			"seller" => "$sellerid",
		    			"description" => nl2br($item_full_description),
		    			/* @phpstan-ignore-line */ "auctionid" => $auction_id,
		    			"langCode" => $langCode,
	        			"bidError" => "%bid% > \$$pounds.$pence",  
    					"auctionid" => $auctionID,
		        	);  
    				
    				/* @phpstan-ignore-line */ $this->view = new View ( "bidaccepted" );
        			/* @phpstan-ignore-line */ $this->view->process ( $items );
        			/* @phpstan-ignore-line */ $this->view->output ();
    			}   
    			
        	}    		 		
    	}
    	else
    	{
    		header('Location: ' . $this->registry->__SERVER['HTTP_REFERER'] );
    	}     	
    }
    
    
	/** @psalm-suppress MissingReturnType */ /** @psalm-suppress MissingReturnType */ private function showWatching()
    {
    	
    	$title = "%my% %bids% " . $this->session->getCurrentUser() ." ";
    	
    	$items = array (
        	"watching_directive" => array ( "auction" , $this , "watching_directive" ) ,
        	"watchingPageination_directive" => array ( "pagination" , $this , "watchingPageination_directive" ) ,
            "title" => "$title",        	
    		"order" => ( $this->session->getKey( "sortorder" ) == "ASC" ) ? "DESC" : "ASC",
    		"itemsperpage" => $this->session->getKey( "itemsperpage" ),
    		"picsize" => $this->session->getKey( "pictureWidth" ) . $this->session->getKey( "pictureHeight" ),
    		"sortOrder" => $this->session->getKey( "sortorder" ), 
    		"sortBy" => $this->session->getKey( "sortby" )
        );
        /* @phpstan-ignore-line */ $this->view = new View ( "watching" );   	
        /* @phpstan-ignore-line */ $this->view->process ( $items );
        /* @phpstan-ignore-line */ $this->view->output ();
        
        exit;
    }
    
    
	/** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function watching_directive ( $match )
    {
    	$itemsperpage = $this->session->getKey( "itemsperpage" );
    	$page = isset( $this->registry->__GET['page'] ) ? (($this->registry->__GET['page'] -1) * $itemsperpage) : 0; 	
    	
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $page) || $page<0)
    		$page = 0;
    		
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $itemsperpage))
    		$itemsperpage = 10;
    	  	
    	/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $now = date('Y-m-d H:i:s', time());
    	
    	$order = $this->session->getKey( "sortorder" );
    	$sortby = $this->session->getKey( "sortby" );
    	
    	$userid = $this->session->getCurrentUserID();     	
    	
    	$query = "SELECT b.auction_id, b.bidding_endtime, b.item_header_description, b.cat_id, b.subcat_id FROM item_watching a, auction_items b WHERE a.user_id = '$userid' AND a.auction_id = b.auction_id ORDER BY $sortby $order limit $page,$itemsperpage";
    	$items = $this->db->query( $query , "array" );
    	    	
    	$width = $this->session->getKey( "pictureWidth" );
        $height = $this->session->getKey( "pictureHeight" );
    	
    	$output = "";
    	
    	$c = 1;
    	
        foreach ( $items as $item )
        {
            $data = $match;
            $auctionid = $item[0];
            $endtime = $item[1];
            $itemdesc = $item[2];
            $cat = $item[3];
            $subcat = $item[4];
         
            $img = $this->db->query( "select MAX(pic_url) from auction_pictures where auction_id='$auctionid'", "one" );
            $img = ($img == "") ? "../blanksmall.jpg" : $img;
            
            list($date, $time) = explode(' ', $endtime);
			list($year, $month, $day) = explode('-', $date);
			list($hour, $minute, $second) = explode(':', $time);
			/* @phpstan-ignore-line */ /** @psalm-suppress InvalidScalarArgument */ /** @psalm-suppress InvalidScalarArgument */ $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
			/** @psalm-suppress PossiblyFalseOperand */ /** @psalm-suppress PossiblyFalseOperand */ $timeleft = $timestamp -  time();			
			
			if( $timeleft < 0)
			{
				$sleft = "%finished%";			
			}
			else
			{			
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $days = floor( $timeleft / 60 / 60 / 24 );
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $hours = $timeleft / 60 / 60 % 24;
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $mins = $timeleft / 60 % 60;
				
				$sleft = "";
				
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $sleft = ($days > 0) ? $days = $days . " %days%, " : "";
				$sleft .= $hours ." %hours%, ";
				$sleft .= $mins ." %minutes% ";
			}
			
			$data = preg_replace ( "/\\[categoryid\\]/s" , $cat , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[subcategoryid\\]/s" , $subcat , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[height\\]/s" , $height , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[width\\]/s" , $width , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[auctid\\]/s" , $auctionid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[img\\]/s" , $img , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[itemShort\\]/s" , $itemdesc , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[timeleft\\]/s" , $sleft , $data );
            /* @phpstan-ignore-line */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[iter\\]/s" , $c , $data );
            /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[question\\]/s" , ($timeleft > 0) ? "" : $c , $data );
            
            /** @psalm-suppress PossiblyInvalidOperand */ /** @psalm-suppress PossiblyInvalidOperand */ $output.= $data;
            $c++;
        }
                
        return $output;
    }
    
    
	/** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function feedback_directive ( $match )
    {
    	$itemsperpage = $this->session->getKey( "itemsperpage" );
    	$page = isset( $this->registry->__GET['page'] ) ? (($this->registry->__GET['page'] -1) * $itemsperpage) : 0; 	
    	
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $page) || $page<0)
    		$page = 0;
    		
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $itemsperpage))
    		$itemsperpage = 10;    	  	
    	
    	$userid = $this->session->getCurrentUserID(); 	
    	
    	$query = "SELECT a.auction_id, b.item_header_description, b.cat_id, b.subcat_id, buyer_id, rating_product, rating_promptness, rating_communication, rating_overall, rating_message from auction_feedback a, auction_items b where b.auction_id = a.auction_id and seller_id='$userid' ORDER BY feed_id DESC limit $page,$itemsperpage";
    	$items = $this->db->query( $query , "array" );
    	    	
    	$width = $this->session->getKey( "pictureWidth" );
        $height = $this->session->getKey( "pictureHeight" );
    	
    	$output = "";
    	
    	$c = 1;
    	$d = 2;
    	
        foreach ( $items as $item )
        {
            $data = $match;
            $auction_id = $item[0];
            $description = $item[1];
            $catid = $item[2];
            $subcatid = $item[3];
            $buyerid = $item[4];
            $rproduct = $item[5];
            $rpromptness = $item[6];
            $rcomm = $item[7];
            $rover = $item[8];
            $rmsg = $item[9];         

            $img = $this->db->query( "select MAX(pic_url) from auction_pictures where auction_id='$auction_id'", "one" );
            $img = ($img == "") ? "../blanksmall.jpg" : $img;            
			
			$data = preg_replace ( "/\\[categoryid\\]/s" , $catid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[subcategoryid\\]/s" , $subcatid , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[height\\]/s" , $height , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[width\\]/s" , $width , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[auctid\\]/s" , $auction_id , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[img\\]/s" , $img , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[itemShort\\]/s" , $description , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[message\\]/s" , $rmsg , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[buyerid\\]/s" , $buyerid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[product\\]/s" , $rproduct , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[promptness\\]/s" , $rpromptness , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[comm\\]/s" , $rcomm , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[over\\]/s" , $rover , $data );
            /* @phpstan-ignore-line */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[iter\\]/s" , $c , $data );
            /* @phpstan-ignore-line */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[iterm\\]/s" , $d , $data ); // codespell:ignore iterm
            
            /** @psalm-suppress PossiblyInvalidOperand */ /** @psalm-suppress PossiblyInvalidOperand */ $output.= $data;
            $c += 2;
            $d += 2;
        }
                
        return $output;
    }      
	
    
    /** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function imageList_directive ( $match )
    {
    	$auctionID = $this->registry->__GET['auctionid'];
    	$imgs = $this->db->query( "select * from auction_pictures where auction_id='$auctionID'" , "array" );
    	
    	$output = "";
    	
        foreach ( $imgs as $img )
        {
            $data = $match;
            $image = $img[2];
            $data = preg_replace ( "/\\[img\\]/s" , $image , $data );
            /** @psalm-suppress PossiblyInvalidOperand */ /** @psalm-suppress PossiblyInvalidOperand */ $output.= $data;            
        }
        
        $output = substr($output, 0, strlen($output) -1);
                
        return $output;
    }
    
    
    /** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function auctionList_directive ( $match )
    {
    	$itemsperpage = $this->session->getKey( "itemsperpage" );
    	$page = isset( $this->registry->__GET['page'] ) ? (($this->registry->__GET['page'] -1) * $itemsperpage) : 0; 	
    	
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $page) || $page<0)
    		$page = 0;
    		
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $itemsperpage))
    		$itemsperpage = 10;
    	  	
    	/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $now = date('Y-m-d H:i:s', time());
    	
    	$order = $this->session->getKey( "sortorder" );
    	$sortby = ($this->session->getKey( "sortby" ) == "amount" ) ? "bidding_endtime" : $this->session->getKey( "sortby" );
    	
    	$userid = $this->session->getCurrentUserID();

    	if( $sortby == "price" )
    	{
    		// DIRTY HACKKKKK
    		
    		$items = $this->db->query( "SELECT * FROM auction_items a, auction_bids b WHERE a.auction_id = b.auction_id	and a.sellerid='$userid' AND b.amount = ( SELECT MAX( amount ) FROM auction_bids WHERE auction_id = a.auction_id ) ORDER BY b.amount $order limit $page,$itemsperpage" , "array" );
    		$titems = $items;
    		$rest = $this->db->query( "select * from auction_items where sellerid='$userid' ORDER BY $sortby $order limit $page,$itemsperpage " , "array" );
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
    		$items = $this->db->query( "select * from auction_items where sellerid='$userid' ORDER BY $sortby $order limit $page,$itemsperpage " , "array" );
    	} 	   	
    	
    	$width = $this->session->getKey( "pictureWidth" );
        $height = $this->session->getKey( "pictureHeight" );
    	
    	$output = "";
    	
    	$c = 1;    	
 	
        foreach ( $items as $item )
        {
            $data = $match;
            $auctionid = $item[0];
            $reserve = ($item[9]==0) ? "000" : $item[2];
            $endtime = $item[3];
            /** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $startprice = ($item[8]==0) ? "000" : $item[8];
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
			
			$pence = substr($maxbid, strlen($maxbid) -2, 2);
    		$pounds = substr($maxbid, 0, strlen($maxbid) -2);
    		$maxbid = "&#36;$pounds.$pence";
    		
    		$pence = substr($reserve, strlen($reserve) -2, 2);
    		$pounds = substr($reserve, 0, strlen($reserve) -2);
    		$reserve = "&#36;$pounds.$pence";
			
			if( $timeleft < 0)
			{
				$sleft = "%finished%";			
			}
			else
			{			
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $days = floor( $timeleft / 60 / 60 / 24 );
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $hours = $timeleft / 60 / 60 % 24;
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $mins = $timeleft / 60 % 60;
				
				$sleft = "";
				
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $sleft = ($days > 0) ? $days = $days . " %days%, " : "";
				$sleft .= $hours ." %hours%, ";
				$sleft .= $mins ." %minutes% ";
			}
			
			$data = preg_replace ( "/\\[categoryid\\]/s" , $item[12] , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[subcategoryid\\]/s" , $item[13] , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[height\\]/s" , $height , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[width\\]/s" , $width , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[auctid\\]/s" , $auctionid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[img\\]/s" , $img , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[itemShort\\]/s" , $itemdesc , $data );
            /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[bids\\]/s" , $bids , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[price\\]/s" , $maxbid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[reserve\\]/s" , $reserve , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[timeleft\\]/s" , $sleft , $data );
            /* @phpstan-ignore-line */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[iter\\]/s" , $c , $data );
            /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[question\\]/s" , ($timeleft > 0) ? "" : $c , $data );
            
            /** @psalm-suppress PossiblyInvalidOperand */ /** @psalm-suppress PossiblyInvalidOperand */ $output.= $data;
            $c++;
        }
                
        return $output;
    }
    
    
	/** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function bidList_directive ( $match )
    {
    	$itemsperpage = $this->session->getKey( "itemsperpage" );
    	$page = isset( $this->registry->__GET['page'] ) ? (($this->registry->__GET['page'] -1) * $itemsperpage) : 0; 	
    	
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $page) || $page<0)
    		$page = 0;
    		
    	/* @phpstan-ignore-line */ /** @psalm-suppress TypeDoesNotContainType */ /** @psalm-suppress TypeDoesNotContainType */ if( !is_int( (int) $itemsperpage))
    		$itemsperpage = 10;
    	  	
    	/** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $now = date('Y-m-d H:i:s', time());
    	
    	$order = $this->session->getKey( "sortorder" );
    	$sortby = $this->session->getKey( "sortby" );
    	
    	$userid = $this->session->getCurrentUserID();   	
    	
    	$query = "SELECT MAX(amount) AS amount, COUNT(amount) as bid_history, user_id, a.auction_id, b.bidding_endtime, b.item_header_description, b.postage, b.cat_id, b.subcat_id FROM auction_bids a, auction_items b WHERE user_id = '$userid' AND a.auction_id = b.auction_id GROUP BY a.auction_id ORDER BY $sortby $order limit $page,$itemsperpage";
    	$items = $this->db->query( $query , "array" );
    	    	
    	$width = $this->session->getKey( "pictureWidth" );
        $height = $this->session->getKey( "pictureHeight" );
    	
    	$output = "";
    	
    	$c = 1;
    	
        foreach ( $items as $item )
        {
            $data = $match;
            $mybid = $item[0];
            /** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $mybidcount = $item[1];
            $auctionid = $item[3];
            $endtime = $item[4];
            $itemdesc = $item[5];
            /** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ $postage = $item[6];
            $cat = $item[7];
            $subcat = $item[8];
         
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
			
			$higherBid = $maxbid - $mybid;
			$won = ( ( $maxbid - $mybid ) > 0 ) ? "%finished%" : "<span class='pageBox' style='background-color:#ffffff' onclick=\"window.location='auction.php?feedback=true&auctionid=$auctionid'\">%leavefeedback%</span>";
			
			if( $higherBid > 0 )
			{
				$higherBid = "higherbid";
				$highestbid = "higherbid";
			}
			else
			{
				$higherBid = "mybid";
				$highestbid = "mybid";
			}
			
			$pence = substr($maxbid, strlen($maxbid) -2, 2);
    		$pounds = substr($maxbid, 0, strlen($maxbid) -2);
    		$maxbid = "&#36;$pounds.$pence";
    		
    		$pence = substr($mybid, strlen($mybid) -2, 2);
    		$pounds = substr($mybid, 0, strlen($mybid) -2);
    		$mybid = "&#36;$pounds.$pence";
			
			if( $timeleft < 0)
			{
				$query = $this->db->query( "select count(*) from auction_feedback where auction_id='$auctionid' and buyer_id='$userid'" , "one" );
				//echo "select count(*) from auction_feedback where auction_id='$auctionid' and buyer_id='$userid'";
				if ( $query == "1" )
				{
					$sleft = "%finished%";
				}
				else
				{
					$sleft = $won;
				}		
			}
			else
			{			
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $days = floor( $timeleft / 60 / 60 / 24 );
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $hours = $timeleft / 60 / 60 % 24;
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $mins = $timeleft / 60 % 60;
				
				$sleft = "";
				
				/** @psalm-suppress InvalidOperand */ /** @psalm-suppress InvalidOperand */ $sleft = ($days > 0) ? $days = $days . " %days%, " : "";
				$sleft .= $hours ." %hours%, ";
				$sleft .= $mins ." %minutes% ";
			}
			
			$data = preg_replace ( "/\\[higherbid\\]/s" , $higherBid , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[highestbid\\]/s" , $highestbid , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[categoryid\\]/s" , $cat , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[subcategoryid\\]/s" , $subcat , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[height\\]/s" , $height , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[width\\]/s" , $width , $data );
			/** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[auctid\\]/s" , $auctionid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[img\\]/s" , $img , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[itemShort\\]/s" , $itemdesc , $data );
            /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[bids\\]/s" , $bids , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[mybid\\]/s" , $mybid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[currentbid\\]/s" , $maxbid , $data );
            /** @psalm-suppress PossiblyNullArgument */ /** @psalm-suppress PossiblyNullArgument */ $data = preg_replace ( "/\\[timeleft\\]/s" , $sleft , $data );
            /* @phpstan-ignore-line */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ /** @psalm-suppress InvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[iter\\]/s" , $c , $data );
            /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ /** @psalm-suppress PossiblyInvalidArgument, PossiblyNullArgument */ $data = preg_replace ( "/\\[question\\]/s" , ($timeleft > 0) ? "" : $c , $data );
            
            /** @psalm-suppress PossiblyInvalidOperand */ /** @psalm-suppress PossiblyInvalidOperand */ $output.= $data;
            $c++;
        }
                
        return $output;
    }
    
    
    /** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function pageination_directive( $match )
    {    	   	
    	$userid = $this->session->getCurrentUserID();

    	$count = $this->db->query( "select count(*) from auction_items where sellerid='$userid'" , "one" );
    	
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
    	
    	$pagination = explode( "|" , $match );
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
    
    
	/** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function userbidsPageination_directive( $match )
    {    	   	
    	$userid = $this->session->getCurrentUserID();

    	$count = $this->db->query( "SELECT MAX(amount) AS amount, COUNT(amount) as bid_history, user_id, a.auction_id, b.bidding_endtime, b.item_header_description, b.postage, b.cat_id, b.subcat_id FROM auction_bids a, auction_items b WHERE user_id = '$userid' AND a.auction_id = b.auction_id GROUP BY a.auction_id" , "array" );
    	$count = count($count);
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
    	
    	$pagination = explode( "|" , $match );
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
    
    
	/** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function feedbackPageination_directive( $match )
    {    	   	
    	$userid = $this->session->getCurrentUserID();
    	$count = $this->db->query( "SELECT count(*) from auction_feedback a, auction_items b where b.auction_id = a.auction_id and seller_id='$userid'" , "one" );
    	
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
    	
    	$pagination = explode( "|" , $match );
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
    
    
	/** @psalm-suppress MissingReturnType, MissingParamType */ /** @psalm-suppress MissingReturnType, MissingParamType */ public function watchingPageination_directive( $match )
    {    	   	
    	$userid = $this->session->getCurrentUserID();
    	$count = $this->db->query( "SELECT count(*) FROM item_watching a, auction_items b WHERE a.user_id = '$userid' AND a.auction_id = b.auction_id" , "one" );
    	
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
    	
    	$pagination = explode( "|" , $match );
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
    

    /** @psalm-suppress MissingOverrideAttribute, MissingReturnType */ /** @psalm-suppress MissingOverrideAttribute, MissingReturnType */ #[\Override] public function index()
    {
        $items = array (
            "title" => "%auction% %item%"            
        );

        /* @phpstan-ignore-line */ $this->view = new View ( "auctionagreement" );
        /* @phpstan-ignore-line */ $this->view->process ( $items );
        /* @phpstan-ignore-line */ $this->view->output ();
    }
}

/** @psalm-suppress MissingDependency */ /** @psalm-suppress MissingDependency */ new Auction;
// jscpd:ignore-end
