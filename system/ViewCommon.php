<?php

class ViewCommon implements Singleton
{
	private $registry = false;
	private $globals_processed = false;
	private static $instance = false;
	
	
    private function __construct( $args = array() ) 
    { 
        $this->registry = Registry::getInstance(); 
    } 

    
    private function __clone() { }    
    public function __destruct() { }
	
	
	public static function getInstance( $args = array() )
	{
		if( ! self::$instance )
		{
			self::$instance = new ViewCommon( $args );			
			return self::$instance;
		}	
		else
		{			
			return self::$instance;
		}	
	}
	
	
    public function globalDirectives()
    {    	
    	if( $this->globals_processed == false)
    	{
    	    $query = "select code from locale where id='".$this->registry->__SESSION->getLanguage()."'";
    	    $lang = $this->registry->__DB->query( $query , "one" );
    	       	    
    		$global_directives = array(
    	        "mainmenu_directive" => array ( "mainmenu" , $this , "mainmenu_directive" ) ,    
    			"breadcrumb_directive" => array ( "breadcrumbs" , $this , "breadcrumb_directive" ) , 
    		    "languageselect_directive" => array ( "selectLanguage" , $this , "languageselect_directive" ) ,    
    		    "countryselect_directive" => array ( "selectCountry" , $this , "countryselect_directive" ) ,   
    		    "categoryselect_directive" => array ( "categorySelect" , $this , "categoryselect_directive" ) ,    
    			"categoryMenu_directive" => array ( "categoryMenu" , $this , "categoryMenu_directive" ) ,
    			"subcategoryMenu_directive" => array ( "subcategoryMenu" , $this , "subcategoryMenu_directive" ) ,
    			"sellerselect_directive" => array ( "selectSeller" , $this , "sellerselect_directive" ) ,
    			"categoryselectminimal_directive" => array ( "categorySelectMinimal" , $this , "categoryselectminimal_directive" ) ,
                "lang" => $lang ,
                "theme" => $this->registry->__SESSION->getTheme() ,
                "username" => $this->registry->__SESSION->getCurrentUser()
    	    );  
    	    
    	    $this->globals_processed = true;
    	    
    	  	return $global_directives;
    	}
    	else
    	{
    		return false;    		
    	}
    }
    
    
	public function subcategoryMenu_directive( $match )
	{
		$cats = $this->registry->__DB->query( "select distinct name from auction_sub_categories order by name ASC" , "array" ); 

		$output = "";
		
        foreach( $cats as $cat )
        {
            $data = $match;
            $name = $cat[0];                          

            $data = preg_replace( "/\\[category\\]/s" , $name , $data );
            $output .= $data;
        }

        return $output;	
	}
	
	
	public function breadcrumb_directive( $match )
	{		
		$urls['self'] = $this->registry->__SERVER['SCRIPT_NAME'];
		
		$output = "";
		
		if( stristr( $urls['self'] , "viewauction.php") )
		{
			if( isset( $this->registry->__GET['viewid'] ) )
			{				
				$catinf = $this->registry->__DB->query( "select cat_id, subcat_id, item_header_description from auction_items where auction_id='" . $this->registry->__GET['viewid'] . "'" , "row");
				$urls['category'] = $catinf[0];
				$urls['subcategory'] = $catinf[1];		
				
				$catname = $this->registry->__DB->query( "select name from auction_categories where cat_id='" . $urls['category'] . "'" , "one" );
				$subcatname = $this->registry->__DB->query( "select name from auction_sub_categories where subcat_id='" . $urls['subcategory'] . "'" , "one" );	

				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?category=" . $urls['category'] , $data );
				$data = preg_replace( "/\\[name\\]/s" , $catname , $data );				
				$output .= $data;	
				
				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?category=" . $urls['subcategory'] ."&subcategory=" . $urls['subcategory'] , $data );
				$data = preg_replace( "/\\[name\\]/s" , $subcatname , $data );				
				$output .= $data;	
				
				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?viewid=" . $this->registry->__GET['viewid']  , $data );
				$data = preg_replace( "/\\[name\\]/s" , $catinf[2] , $data );				
				$output .= $data;
			}
			else
			{
				if( !isset( $this->registry->__GET['subcategory'] ) )
				{
					$urls['category'] = isset( $this->registry->__GET['category'] ) ? $this->registry->__GET['category'] : "";
					$catname = $this->registry->__DB->query( "select name from auction_categories where cat_id='" . $urls['category'] . "'" , "one" );

					$data = $match;
					$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?category=" . $urls['category'] , $data );
					$data = preg_replace( "/\\[name\\]/s" , $catname , $data );				
					$output .= $data;	
				}
				else
				{
					$urls['category'] = isset( $this->registry->__GET['category'] ) ? $this->registry->__GET['category'] : "";
					$urls['subcategory'] = isset( $this->registry->__GET['subcategory'] ) ? $this->registry->__GET['subcategory'] : "";	
					$catname = $this->registry->__DB->query( "select name from auction_categories where cat_id='" . $urls['category'] . "'" , "one" );
					$subcatname = $this->registry->__DB->query( "select name from auction_sub_categories where subcat_id='" . $urls['subcategory'] . "'" , "one" );	

					$data = $match;
					$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?category=" . $urls['category'] , $data );
					$data = preg_replace( "/\\[name\\]/s" , $catname , $data );				
					$output .= $data;	
					
					$data = $match;
					$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?category=" . $urls['subcategory'] ."&subcategory=" . $urls['subcategory'] , $data );
					$data = preg_replace( "/\\[name\\]/s" , $subcatname , $data );				
					$output .= $data;	
				}
			}			
		}	
		else if( stristr( $urls['self'] , "register.php") )
		{
			$data = $match;
			$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}" , $data );
			$data = preg_replace( "/\\[name\\]/s" , "%register%" , $data );				
			$output .= $data;				
		}
		else if( stristr( $urls['self'] , "login.php") )
		{
			$data = $match;
			$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}" , $data );
			$data = preg_replace( "/\\[name\\]/s" , "%login%" , $data );				
			$output .= $data;				
		}
		else if( stristr( $urls['self'] , "auction.php") )
		{					
			if( isset( $this->registry->__GET['show'] ) )
			{
				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?show=all" , $data );
				$data = preg_replace( "/\\[name\\]/s" , "%my% %auctions%" , $data );				
				$output .= $data;
			}
			else if( isset( $this->registry->__GET['feedback'] ) )
			{
				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?feedback=true" , $data );
				$data = preg_replace( "/\\[name\\]/s" , "%feedback%" , $data );				
				$output .= $data;
			}
			else if( isset( $this->registry->__GET['watching'] ) )
			{
				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?watching=all" , $data );
				$data = preg_replace( "/\\[name\\]/s" , "%watching%" , $data );				
				$output .= $data;
			}
			else if( isset( $this->registry->__GET['mybids'] ) )
			{
				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?mybids=all" , $data );
				$data = preg_replace( "/\\[name\\]/s" , "%my% %bids%" , $data );				
				$output .= $data;
			}
			else
			{
				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}" , $data );
				$data = preg_replace( "/\\[name\\]/s" , "%auction% %item%" , $data );				
				$output .= $data;				
			}
		}
		else if( stristr( $urls['self'] , "profile.php") )
		{		
			if( isset( $this->registry->__GET['messages'] ) )
			{
				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}?messages=true" , $data );
				$data = preg_replace( "/\\[name\\]/s" , "%messages%" , $data );				
				$output .= $data;
			}
			else
			{
				$data = $match;
				$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}" , $data );
				$data = preg_replace( "/\\[name\\]/s" , "%profile%" , $data );				
				$output .= $data;	
			}
		}
		else
		{				
			$data = $match;
			$data = preg_replace( "/\\[url\\]/s" , "{$urls['self']}" , $data );
			$data = preg_replace( "/\\[name\\]/s" , "%welcome%" , $data );				
			$output .= $data;
		}
		
		return substr( $output , 0, strlen( $output ) - 41 );
	}
    
    
	public function categoryMenu_directive( $match )
	{
		$cats = $this->registry->__DB->query( "select * from auction_categories order by name ASC" , "array" ); 
		
		$now = date('Y-m-d H:i:s', time());		
		$count = $this->registry->__DB->query( "SELECT cat_id, COUNT( * ) FROM auction_items where bidding_endtime > '$now' GROUP BY cat_id ORDER by CAT_ID ASC" , "array");
		
		$catcount = array();
		foreach( $count as $cnt )
		{
			$catcount[$cnt[0]] = $cnt[1];
		}		

		$output = "";
		$pattern = "/\#subcat\#(.*)\#\/subcat\#/s";		

        foreach( $cats as $cat )
        {
            $data = $match;
            $id = $cat[0];
            $name = $cat[1];                          
            
            $data = preg_replace( "/\\[categoryid\\]/s" , $id , $data );
            $data = preg_replace( "/\\[category\\]/s" , $name , $data );
            $data = preg_replace( "/\\[catcount\\]/s" , isset( $catcount[$id] ) ? $catcount[$id] : 0 , $data );            
            
            if( isset($this->registry->__GET['category']) && $this->registry->__GET['category'] == $id )
            {
            	$num = preg_match( $pattern , $data , $matches );
            	$data = preg_replace( $pattern , $this->subcategory( $matches[1] ) , $data );  	
            }
            else
            {
            	$data = preg_replace( $pattern , "" , $data );
            }

            $output .= $data;
        }

        return $output;	
	}
	
	
	private function subcategory( $match )
	{
		$cat = $this->registry->__GET['category'];
        $subcats = $this->registry->__DB->query( "select * from auction_sub_categories where cat_id='$cat' order by name ASC" , "array" ); 
        $now = date('Y-m-d H:i:s', time());	    
        $count = $this->registry->__DB->query( "SELECT subcat_id, COUNT( * ) FROM auction_items where cat_id='$cat' and bidding_endtime > '$now' GROUP BY subcat_id ORDER by subcat_id ASC" , "array");
		
		$catcount = array();
		foreach( $count as $cnt )
		{
			$catcount[$cnt[0]] = $cnt[1];
		}	
        
        $output = "";
        
        $now = date('Y-m-d H:i:s', time());
        
        if(	count($subcats) < 1	)
        	return "";
        
        foreach( $subcats as $subcat )
        {        	
        	$data = $match;        	
        	$count = isset( $catcount[$subcat[0]] ) ? $catcount[$subcat[0]] : 0; 
            $data = preg_replace( "/\\[subcategoryid\\]/s" , $subcat[0] , $data );
            $data = preg_replace( "/\\[subcategory\\]/s" , $subcat[1] , $data );
            $data = preg_replace( "/\\[subcatcount\\]/s" , $count , $data );
            $output .= $data;                       
        }	

        return $output;	
	}
	
	
	public function mainmenu_directive( $match )
	{
	    $userid = $this->registry->__SESSION->getCurrentUser();
	    $userid = $this->registry->__SESSION->getCurrentUserID();
	    $menu = array();
	    
	    $menu[0]['icon'] = "person";
	    $menu[0]['link'] = ($this->registry->__SESSION->isLoggedOn()) ? "profile.php" : "register.php";
	    $menu[0]['linkname'] = ($this->registry->__SESSION->isLoggedOn()) ? "%profile%" : "%register%";
	    
	    $menu[1]['icon'] = "key";
	    $menu[1]['link'] = ($this->registry->__SESSION->isLoggedOn()) ? "logout.php" : "login.php";
	    $menu[1]['linkname'] = ($this->registry->__SESSION->isLoggedOn()) ? "%logout%" : "%login%";
	    
	    $output = "";
	    $num = 0;
	    
	    if( $this->registry->__SESSION->isLoggedOn() )
        {
            $data = $match;
            $icon = "pin-w";
            $link = "auction.php?create=true";
            $linkname = "%auction% %item%";
            
            $num++;
            $data = preg_replace( "/\\[num\\]/s" , $num , $data );
            $data = preg_replace( "/\\[icon\\]/s" , $icon , $data );
            $data = preg_replace( "/\\[link\\]/s" , $link , $data );
            $data = preg_replace( "/\\[linkname\\]/s" , $linkname , $data );

            $output .= $data;            
            
            $data = $match;
            $myAuctions = $this->registry->__DB->query( "select count(*) from auction_items where sellerid='$userid'" , "one" );
            $icon = "pin-s";
            $link = "auction.php?show=all";
            $linkname = "%my% %auctions% ($myAuctions)";
            
            $num++;                    
            $data = preg_replace( "/\\[num\\]/s" , $num , $data );
            $data = preg_replace( "/\\[icon\\]/s" , $icon , $data );
            $data = preg_replace( "/\\[link\\]/s" , $link , $data );
            $data = preg_replace( "/\\[linkname\\]/s" , $linkname , $data );

            $output .= $data;
            
            $data = $match;
            $myBids = $this->registry->__DB->query( "SELECT count(DISTINCT auction_id) FROM auction_bids WHERE user_id = '$userid'" , "one" );   
            $icon = "gear";
            $link = "auction.php?mybids=all";
            $linkname = "%my% %bids% ($myBids)";
            
            $num++;
            $data = preg_replace( "/\\[num\\]/s" , $num , $data );
            $data = preg_replace( "/\\[icon\\]/s" , $icon , $data );
            $data = preg_replace( "/\\[link\\]/s" , $link , $data );
            $data = preg_replace( "/\\[linkname\\]/s" , $linkname , $data );

            $output .= $data;
            
            $data = $match;
            $myWatches = $this->registry->__DB->query( "SELECT count(DISTINCT auction_id) FROM item_watching WHERE user_id = '$userid'" , "one" );
            $icon = "star";
            $link = "auction.php?watching=all";
            $linkname = "%watching% ($myWatches)";
            
            $num++;
            $data = preg_replace( "/\\[num\\]/s" , $num , $data );
            $data = preg_replace( "/\\[icon\\]/s" , $icon , $data );
            $data = preg_replace( "/\\[link\\]/s" , $link , $data );
            $data = preg_replace( "/\\[linkname\\]/s" , $linkname , $data );

            $output .= $data;
            
            $data = $match;
            $myFeedback = $this->registry->__DB->query( "SELECT count(DISTINCT auction_id) FROM auction_feedback WHERE seller_id = '$userid'" , "one" );
            $icon = "comment";
            $link = "auction.php?feedback=true";
            $linkname = "%feedback% ($myFeedback)";
            
            $num++;
            $data = preg_replace( "/\\[num\\]/s" , $num , $data );
            $data = preg_replace( "/\\[icon\\]/s" , $icon , $data );
            $data = preg_replace( "/\\[link\\]/s" , $link , $data );
            $data = preg_replace( "/\\[linkname\\]/s" , $linkname , $data );

            $output .= $data;
            
            $data = $match;     
            $myMessages = $this->registry->__DB->query( "SELECT count(*) FROM messages WHERE to_id = '$userid'" , "one" );       
            $icon = "mail-closed";
            $link = "profile.php?messages=true";
            $linkname = "%messages% ($myMessages)";
            
            $num++;
            $data = preg_replace( "/\\[num\\]/s" , $num , $data );
            $data = preg_replace( "/\\[icon\\]/s" , $icon , $data );
            $data = preg_replace( "/\\[link\\]/s" , $link , $data );
            $data = preg_replace( "/\\[linkname\\]/s" , $linkname , $data );

            $output .= $data;
        }
	    
	    foreach( $menu as $menuItem )
        {
            $data = $match;
            $icon = $menuItem['icon'];
            $link = $menuItem['link'];
            $linkname = $menuItem['linkname'];
            
            $num++;
            $data = preg_replace( "/\\[num\\]/s" , $num , $data );
            $data = preg_replace( "/\\[icon\\]/s" , $icon , $data );
            $data = preg_replace( "/\\[link\\]/s" , $link , $data );
            $data = preg_replace( "/\\[linkname\\]/s" , $linkname , $data );

            $output .= $data;
        }        
	    
	    return $output;		
	}
	
	
    public function languageselect_directive( $match )
	{
	    $query = "select * from locale where available=1 order by code ASC";
	    
		$langs = $this->registry->__DB->query( $query , "array" ); 

		$output = "";

        foreach( $langs as $lang )
        {
            $data = $match;
            $id = $lang[0];
            $locale = $lang[1];
            $language = $lang[2];
            $code = $lang[3];
            $available = $lang[4];
            
            if( $this->registry->__SESSION->getLanguage() == $id )
            {
                $data = preg_replace( "/\\[selected\\]/s" , "selected=\"selected\"" , $data );
            }
            else
            {
                $data = preg_replace( "/\\[selected\\]/s" , "" , $data );
            }
            
            $data = preg_replace( "/\\[countryCode\\]/s" , $id , $data );
            $data = preg_replace( "/\\[countryLanguage\\]/s" , $locale , $data );

            $output .= $data;
        }

        return $output;		
	}
	
	
    public function countryselect_directive( $match )
	{
		$countries = $this->registry->__DB->query( "select * from countries order by id ASC" , "array" ); 

		$output = "";	
		$user =  ($this->registry->__SESSION->isLoggedOn()) ? $this->registry->__SESSION->getCurrentUser() : "";
		$usercountry = ( $user!="" ) ? $this->registry->__DB->query( "select country from ou_users where username='$user'" , "one" ) : "";	

        foreach( $countries as $country )
        {
            $data = $match;
            $id = $country[0];
            $name = $country[1]; 
         
            if( $usercountry == $id )
            {
                $data = preg_replace( "/\\[selected\\]/s" , "selected='selected'", $data );
            }
            else
            {                
                $data = preg_replace( "/\\[selected\\]/s" , "" , $data );
            }
            $data = preg_replace( "/\\[id\\]/s" , $id , $data );
            $data = preg_replace( "/\\[country\\]/s" , "%$name%" , $data );

            $output .= $data;
        }

        return $output;	
	}
	
	
	public function sellerselect_directive( $match )
	{
		$users = $this->registry->__DB->query( "select userid, username from ou_users where userid > '1' order by username ASC" , "array" ); 

		$output = "";

		$replyto = isset( $this->registry->__GET['replyto'] ) ?  $this->registry->__GET['replyto'] : -10;

        foreach( $users as $user )
        {
            $data = $match;
            $id = $user[0];
            $name = $user[1];                             

            $data = preg_replace( "/\\[selected\\]/s" , ( $replyto != -10 && $replyto == $id ) ? " selected='selected' " : "" , $data );
            $data = preg_replace( "/\\[id\\]/s" , $id , $data );
            $data = preg_replace( "/\\[seller\\]/s" , $name , $data );

            $output .= $data;
        }

        return $output;	
	}
		
	
	public function categoryselectminimal_directive( $match )
	{
		$cats = $this->registry->__DB->query( "select * from auction_categories order by name ASC" , "array" ); 

		$output = "";

        foreach( $cats as $cat )
        {
            $data = $match;
            $id = $cat[0];
            $name = $cat[1];                          

            $data = preg_replace( "/\\[catid\\]/s" , $id , $data );
            $data = preg_replace( "/\\[category\\]/s" , $name , $data );

            $output .= $data;
        }

        return $output;	
	}
	
	
	public function categoryselect_directive( $match )
	{
		$cats = $this->registry->__DB->query( "select * from auction_categories order by name ASC" , "array" ); 

		$output = "";

        foreach( $cats as $cat )
        {
            $data = $match;
            $id = $cat[0];
            $name = $cat[1];                          

            $data = preg_replace( "/\\[catid\\]/s" , $id , $data );
            $data = preg_replace( "/\\[category\\]/s" , $name , $data );

            $output .= $data;
        }

        return $output;	
	}
}

