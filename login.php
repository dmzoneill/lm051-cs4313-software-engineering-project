<?php

require_once ( dirname( __FILE__ ) . "/conf.php" );

class Login extends Controller
{
 
    public function __construct ()
    {
        parent::__construct ();

        if ( $this->session->isLoggedOn () )
        {
            header ( "Location: index.php" );
        }

        if ( isset ( $this->registry->__POST[ 'username' ] ) && isset ( $this->registry->__POST[ 'password' ] ) )
        {
            $this->processLogin ();
        }
        else
        {
            $this->index ();
        }
    }


    private function processLogin ()
    {
        if ( $this->session->loginUser ( $this->registry->__POST[ 'username' ] , $this->registry->__POST[ 'password' ] ) )
        {
            $redirect = isset ( $this->registry->__POST[ 'redirectUrl' ] ) ? $this->registry->__POST[ 'redirectUrl' ] : "";
            $userid = $this->session->getCurrentUserID();
        	$groupid = $this->db->query( "select groupid from ou_users where userid='$userid'" , "one" );
        	
        	if( $groupid == 5 )
        	{
        		$refer = "admin.php";
        	}
        	else
        	{
        		$refer = isset( $this->registry->__POST['refer'] ) ? $this->registry->__POST['refer'] : "";
        	}
        	            
            if( $groupid == 6 )
            {   
            	$this->session->endSession(); 
            	$this->session->reconstruct();        
            	
                $items = array(
            		"title" => "%banned%",
                	"username" => $this->registry->__POST[ 'username' ]
                );

	            $this->view = new View( "banned" );
	            $this->view->process( $items );
	            $this->view->output();
	            exit;
	            
            }      
           
        	
            $items = array (
                "title" => "%login% %successful%... %redirecting%" ,
                "username" => $this->registry->__POST[ 'username' ] ,
                "refer" => $refer
            );

            $this->view = new View ( "loggedin" , $this );
            $this->view->process ( $items );
            $this->view->output ();
        }
        else
        {
            $items = array(
                "title" => "%login%" ,
                "username" => $this->registry->__POST[ 'username' ] ,
                "loginfailed" => "%loginfailed%",
            	"refer" => isset( $this->registry->__POST['refer'] ) ? $this->registry->__POST['refer'] : ""
            );

            $this->view = new View ( "login" , $this );
            $this->view->process ( $items );
            $this->view->output ();            
        }
    }


    public function index()
    {
        $items = array (
            "title" => "%home%" ,
            "username" => "" ,
            "loginfailed" => "",
        	"refer" => isset( $this->registry->__SERVER['HTTP_REFERER'] ) ? $this->registry->__SERVER['HTTP_REFERER'] : ""
        );

        $this->view = new View ( "login" );
        $this->view->process ( $items );
        $this->view->output ();
    }
}

new Login;

