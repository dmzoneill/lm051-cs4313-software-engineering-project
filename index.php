<?php

require_once ( dirname( __FILE__ ) . "/conf.php" );

class Index extends Controller
{   

    public function __construct ()
    {
        parent::__construct();         
        $this->index();
    }
    
    
    public function newAuctions_directive( $match )
    {
		$items = $this->db->query( "select * from auction_items order by auction_id DESC limit 0, 10" , "array" );
		
		$output = "";
		
		for( $t = 0; $t < count( $items); $t = $t + 2)
		{
			$data = $match;
			
			$item_odd = $items[$t];
			$item_even = $items[$t+1];
			
			$id_odd = $item_odd[0];
			$id_even = $item_even[0];
			
			$desc_odd = $item_odd[10];
			$desc_even = $item_even[10];
			
			$catid_odd = $item_odd[12];
			$catid_even = $item_even[12];
			
			$subcatid_odd = $item_odd[13];
			$subcatid_even = $item_even[13];
			
			$img_odd = $this->db->query( "select MAX(pic_url) from auction_pictures where auction_id='$id_odd'", "one" );
            $img_odd = ($img_odd == "") ? "../blanksmall.jpg" : $img_odd;
			$img_even = $this->db->query( "select MAX(pic_url) from auction_pictures where auction_id='$id_even'", "one" );
			$img_even = ($img_even == "") ? "../blanksmall.jpg" : $img_even;
			
			$data = preg_replace ( "/\\[img-odd\\]/s" , $img_odd , $data ); 
			$data = preg_replace ( "/\\[catid-odd\\]/s" , $catid_odd , $data ); 
			$data = preg_replace ( "/\\[subcatid-odd\\]/s" , $subcatid_odd , $data ); 
			$data = preg_replace ( "/\\[viewid-odd\\]/s" , $id_odd , $data ); 
			$data = preg_replace ( "/\\[item-desc-odd\\]/s" , $desc_odd , $data ); 
			
			$data = preg_replace ( "/\\[img-even\\]/s" , $img_even , $data ); 
			$data = preg_replace ( "/\\[catid-even\\]/s" , $catid_even , $data ); 
			$data = preg_replace ( "/\\[subcatid-even\\]/s" , $subcatid_even , $data ); 
			$data = preg_replace ( "/\\[viewid-even\\]/s" , $id_even , $data ); 
			$data = preg_replace ( "/\\[item-desc-even\\]/s" , $desc_even , $data ); 
			
			$output .= $data;
		}

		return $output;
    }
    
    
    public function index ()
    {
        $this->status = "hello";
        $this->notify ();
    	
        $items = array(
        	"newAuctions_directive" => array ( "newauctions" , $this , "newAuctions_directive" ) ,
            "title" => "%home%"             
        );

        $this->view = new View( "index" );
        $this->view->process( $items );
        $this->view->output();        
    }   
    
}

new Index;

