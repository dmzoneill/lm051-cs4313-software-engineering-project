<?php

require_once ( dirname( __FILE__ ) . "/conf.php" );

class Memberlist extends Controller
{

    public function __construct ()
    {
        parent::__construct (); 

        if( isset( $this->registry->__GET['memberid']) )
        {
        	$this->showUser();
        }
        else
        {
        	$this->index ();
        }
    }
    
    
    private function showUser()
    {
    	$userid = $this->registry->__GET['memberid'];
    	$userdetails = $this->db->query( "select * from ou_users a, addresses b where a.userid = b.user_id and a.userid='$userid'" , "row" );    

    	if( !(count($userdetails) > 0) )
    	{
    		header('Location: memberlist.php');
    	}
  	
    	$this->view = new View ( "memberview" );
    	
    	$items = array (    
    		"theuser" => "{$userdetails[2]}",
            "title" => "%member% {$userdetails[2]}",
    		"fname" => "{$userdetails[5]}",
    		"lname" => "{$userdetails[6]}",
    		"locale" => $this->db->query( "select locale from locale where id='{$userdetails[9]}'" , "one"),
    		"hnumber" => "{$userdetails[17]}",
    		"street" =>  "{$userdetails[18]}",
    		"county" => "{$userdetails[19]}",
    		"state" =>  "{$userdetails[20]}",
    		"country" => $this->db->query( "select country from countries where id='{$userdetails[21]}'" , "one"),
    		"postcode" => "{$userdetails[22]}",
    		"userid" => "{$userdetails[0]}",
    		"picture" => "{$userdetails[13]}",
        );

        $this->view->process ( $items );
        $this->view->output ();
    }


    public function memberlist_directive ( $match )
    {
        $query = "select * from ou_users where userid > 1 order by userid ASC";

        if( isset ( $this->registry->__GET[ 'sortby' ] ) && isset ( $this->registry->__GET[ 'sortorder' ] ) )
        {
            $fields = array ();
            $column_fields = $this->db->query ( "show columns from ou_users" , "array" );

            foreach ( $column_fields as $field )
            {
                $fields[] = $field[ 0 ];
            }

            if( in_array ( $this->registry->__GET[ 'sortby' ] , $fields ) && ( $this->registry->__GET[ 'sortorder' ] == "asc" || $this->registry->__GET[ 'sortorder' ] == "desc" ) )
            {
                $query .= " order by " . $this->registry->__GET[ 'sortby' ];
                $query .= " " . $this->registry->__GET[ 'sortorder' ];
            }
        }

        $members = $this->db->query ( $query , "array" );

        $memberlist = "";

        foreach ( $members as $member )
        {
            $data = $match;
            $userid = $member[0];
            $groupid = $member[1];
            $username = $member[2];
            $password = $member[3];
            $alias = $member[4];
            $fname = $member[5];
            $lname = $member[6];
            $dob = $member[7];
            $country = $member[8];
            $language = $member[9];
            $theme = $member[10];
            $regdate = $member[11];
            $lastaccess = $member[12];
            $picture = $member[13];
            $email = $member[14];

            $data = preg_replace ( "/\\[userid\\]/s" , $userid , $data );
            $data = preg_replace ( "/\\[groupid\\]/s" , $groupid , $data );
            $data = preg_replace ( "/\\[username\\]/s" , $username , $data );
            $data = preg_replace ( "/\\[alias\\]/s" , $alias , $data );
            $data = preg_replace ( "/\\[fname\\]/s" , $fname , $data );
            $data = preg_replace ( "/\\[lname\\]/s" , $lname , $data );
            $data = preg_replace ( "/\\[dob\\]/s" , $dob , $data );
            $data = preg_replace ( "/\\[country\\]/s" , $this->db->query( "select country from countries where id='$country'" , "one") , $data );
            $data = preg_replace ( "/\\[language\\]/s" , $this->db->query( "select locale from locale where id='$language'" , "one") , $data );
            $data = preg_replace ( "/\\[theme\\]/s" , $theme , $data ); 
            $data = preg_replace ( "/\\[regdate\\]/s" , $regdate , $data );
            $data = preg_replace ( "/\\[lastaccess\\]/s" , $lastaccess , $data );  
            $data = preg_replace ( "/\\[picture\\]/s" , $picture , $data ); 
            $data = preg_replace ( "/\\[email\\]/s" , $email , $data ); 

            $memberlist .= $data;
        }

        return $memberlist;
    }


    public function index ()
    {        
        $this->view = new View ( "memberlist" );

        $items = array (
            "memberlist_directive" => array ( "members" , $this , "memberlist_directive" ) ,
            "title" => "%home%" ,
            "descript" => "%welcome% %to% %my% %website%!" ,
            "something" => $this->session->getCurrentUser () ,
            "time" => date ( "D M Y" , time () )
        );

        $this->view->process ( $items );
        $this->view->output ();
    }
}

new Memberlist;


