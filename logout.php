<?php

require_once ( dirname( __FILE__ ) . "/conf.php" );

class Logout extends Controller
{    

    public function __construct()
    {
        parent::__construct();
        
        if($this->session->isLoggedOn() == true)
        {
            $this->session->endSession ();
        }

        $this->index ();        
    }

    
    public function index ()
    {
        header ( "Location: index.php" );
    }
}

new Logout;


