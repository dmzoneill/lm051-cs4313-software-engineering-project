<?php

require_once( dirname( __FILE__ ) . "/conf.php" );

class Sample extends Controller
{

    public function __construct()
    {
         parent::__construct();
         $this->index();
    }
    
    
    public function simpleCallback( $match )
    {
    	$data = "";
    	// logic
    	// $data += $logic
    	// $output = $data;
    	
    	return $output;    	
    }

    
    public function index()
    {
    	// logic
    	
    	$items = array (
    		"simpleCallback" => array ( "callback" , $this , "simpleCallback" ) , // simple callback 
            "title" => "%home%" , // output data for locaization
            "username" => $this->session->getCurrentUser() , // output the username
        );
    	
        $this->view = new View( "Sample" );
        $this->view->process ( $items );
        $this->view->output ();        
    }
}

new Sample;
