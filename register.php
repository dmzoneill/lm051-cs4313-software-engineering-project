<?php

require_once ( dirname( __FILE__ ) . "/conf.php" );

class Register extends Controller
{   

    public function __construct ()
    {
        parent::__construct();        
        
        if($this->session->isLoggedOn() == true)
        {
            header('Location: index.php');
            exit;
        }
        
        if ( isset ( $this->registry->__GET[ 'ajax' ] ) )
        {
            if ( isset ( $this->registry->__GET[ 'username' ] ) )
            {
                $this->ajaxCheckUsernameAvailable( $this->registry->__GET[ 'username' ] );
            }   
            else if ( isset ( $this->registry->__GET[ 'email' ] ) )
            {
                $this->ajaxCheckEmailAvailable( $this->registry->__GET[ 'email' ] );
            }       
        }
        else if( isset ( $this->registry->__GET[ 'submit' ] ) )
        {
            $this->doRegistration();       
        }
        else if( isset ( $this->registry->__GET[ 'act' ] ) )
        {
            $this->doActivation();       
        }
        else 
        {
            $this->index();
        }
        
    }
    
    
    private function doActivation()
    {
        $ml = base64_decode($this->registry->__GET[ 'act' ]);
        $ml = mysql_real_escape_string($ml);
        
        $count = $this->db->query ( "select count(*) from ou_users where email='$ml'" , "one" );
        
        if( $count > 0 )
        {
            $user = $this->db->query ( "select username from ou_users where email='$ml'" , "one" );
            $group = $this->db->query ( "select groupid from ou_users where email='$ml'" , "one" );
            
            if( $group != 6)
            {
                $this->db->query ( "update ou_users set groupid='3' where email='$ml'" , "one" );
            
                $items = array(
            		"title" => "%activation% %complete%",
                	"username" => $user
                );

	            $this->view = new View( "activationcomplete" );
	            $this->view->process( $items );
	            $this->view->output();
            }       
            else
            {
                $items = array(
            		"title" => "%banned%",
                	"username" => $user
                );

	            $this->view = new View( "banned" );
	            $this->view->process( $items );
	            $this->view->output();                
            }        
        }
        else
        {
            $items = array(
            		"title" => "%activation% %complete%",
                	"username" => $user
                );

	        $this->view = new View( "notregistered" );
	        $this->view->process( $items );
	        $this->view->output();                  
        }
    }
    
    
    private function doRegistration()
    {        
        $reg_username = $this->registry->__POST[ 'username' ];
	    $reg_email = $this->registry->__POST[ 'email' ];
	    $reg_pass1 = $this->registry->__POST[ 'pass1' ];
	    $reg_pass2 = $this->registry->__POST[ 'pass2' ];
	    $reg_fname = $this->registry->__POST[ 'fname' ];
	    $reg_sname = $this->registry->__POST[ 'sname' ];
	    $reg_dob = $this->registry->__POST[ 'dob' ];
	    $reg_hnumber = $this->registry->__POST[ 'hnumber' ];
	    $reg_street = $this->registry->__POST[ 'street' ];
	    $reg_county = $this->registry->__POST[ 'county' ];
	    $reg_country = $this->registry->__POST[ 'country' ];
	    $reg_province = $this->registry->__POST[ 'province' ];
	    $reg_postcode = $this->registry->__POST[ 'postcode' ];
	    $reg_file = isset( $this->registry->__POST[ 'file' ] ) ? $this->registry->__POST[ 'file' ] : ""; 
	    	    
        if( $this->db->query ( "select count(*) from ou_users where username='$reg_username'" , "one" ) > 0 )
            $data['reg-username-error'] = "%userused%";
            
	    if( $this->db->query ( "select count(*) from ou_users where email='$reg_email'" , "one" ) > 0 )
	        $data['reg-email-error'] = "%emailused%";
	        
	    // www.php.net email regualr expression
        // http://php.net/manual/en/function.preg-match.php
	    if( preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])*(\.([a-z0-9])([-a-z0-9_-])([a-z0-9])+)*$/i', $reg_email ) < 1 )
            $data['reg-email-error'] = "%typevalidemail%";
        
        if( strlen($reg_pass1) < 6 )
            $data['reg-pass-error'] = "%passwordshort%";    
            
        if( $reg_pass1 != $reg_pass2 )
            $data['reg-pass-error'] = "%passwordmatch%";
            
        if( strlen($reg_fname) < 3 )
            $data['reg-fname-error'] = "%tooshort%";
        
        if( strlen($reg_sname) < 3 )
            $data['reg-sname-error'] = "%tooshort%";

        // regex http://dotnetslackers.com/Regex/re-16542_Regex_matches_a_date_in_dd_mm_yyyy_format.aspx
        if( preg_match('/^(((((0[1-9])|(1\d)|(2[0-8]))-((0[1-9])|(1[0-2])))|((31\/((0[13578])|(1[02])))|((29|30)\/((0[1,3-9])|(1[0-2])))))-((20[0-9][0-9])|(19[0-9][0-9])))|((29\/02\/(19|20)(([02468][048])|([13579][26]))))$/i',$reg_dob) < 1 )
            $data['reg-dob-error'] = "%incorrectdate%";
            
        if( strlen($reg_hnumber) < 1 )
            $data['reg_hnumber-error'] = "%tooshort%";
            
        if( strlen($reg_street) < 3 )
            $data['reg_street-error'] = "%tooshort%";
                        
        if( strlen($reg_county) < 3 )
            $data['reg_county-error'] = "%tooshort%";
            
        if( $reg_country == 0 )
            $data['reg_country-error'] = "select a country";
            
        if( strlen($reg_province) < 3 )
            $data['reg_province-error'] = "%tooshort%";
        
        if( strlen($reg_postcode) < 3 )
            $data['reg_postcode-error'] = "%tooshort%";
                            
	    if( !isset($data['reg-username-error']) && !isset($data['reg-email-error']) && !isset($data['reg-pass-error']) && !isset($data['reg-fname-error']) && !isset($data['reg-sname-error']) && !isset($data['reg-dob-error']) && !isset($data['reg_hnumber-error']) && !isset($data['reg_street-error']) && !isset($data['reg_county-error']) && !isset($data['reg_country-error']) && !isset($data['reg_province-error']) && !isset($data['reg_postcode-error']) )
        {       
            
            $pass = sha1($reg_pass1);
            $dob = explode("-",$reg_dob);
            $year = $dob[2];
            $month = $dob[1];
            $day = $dob[0];
            $now = date('Y-m-d H:i:s', time());
            $file = "";
            
            $query = "insert into ou_users VALUES( null, '3', '$reg_username', '$pass', '$reg_username', '$reg_fname', '$reg_sname', '$year-$month-$day 00:00:00', '$reg_country', '". $this->session->getLanguage() ."', 'smoothness', '$now', '$now', '$file', '$reg_email')";
            $queryid = "select userid from ou_users where username='$reg_username'";                        
            $adduser = $this->db->query ( $query , "one" );
            $theuser = $this->db->query ( $queryid , "one" );            
            $addr = "insert into addresses VALUES( null ,'$theuser','$reg_hnumber','$reg_street','$reg_county','$reg_province','$reg_country','$reg_postcode')"; 
            $addAddr = $this->db->query ( $addr , "one" );               
            
            $locality = $this->db->query( "select code from locale where id='". $this->session->getLanguage() ."'" ,"one" );
            include_once ( __SITE_ROOT . "/system/locale/".$locality.".php" ); 

            // inbox welcome message
            $insert = $this->db->query( "insert into messages values ( null , '1' , '$theuser' , '" . $locale['welcome'] . "' , '" . $locale['welcome'] . " $reg_username' , '0' , '0' ) " , "one" );
            
            //$msg = $locale['hello'] . " $reg_username\n\n" . $locale['clicklink'] . "\n\n http://testweb2.csis.ul.ie/modules/CS4313/group5/register.php?act=" . base64_encode($reg_email) . "\n\nCharity Bay";
			//$data .= "to=" . $reg_email;
			//$data .= "|subject=" . $locale['registration'];
			//$data .= "|message=" .$msg;
			//$data .= "|from=webmaster@charitybay.com";	
			//$data = base64_encode($data);					
			//$mailhack = "http://www.feeditout.com/mail.php";									
			//$result = $this->proxy_url( $mailhack );			
			//echo $result;
           
            $items = array(
            	"title" => "%registration% %complete%",
                "username" => $reg_username,
            	"email" => $reg_email
            );

	        $this->view = new View( "registercomplete" );
	        $this->view->process( $items );
	        $this->view->output();                   
        }
        else 
        {
            $items = array(
	            "title" => "%register%",
	            "reg-username" => $reg_username,
	            "reg-email" => $reg_email,
	            "reg-pass1" => $reg_pass1,
	        	"reg-pass2" => $reg_pass2,
	            "reg-fname" => $reg_fname,
	            "reg-sname" => $reg_sname,
	            "reg-dob" => $reg_dob,
	            "reg-hnumber" => $reg_hnumber,
	            "reg-street" => $reg_street,
	            "reg-county" => $reg_county,
	            "reg-province" => $reg_province,
	            "reg-postcode" => $reg_postcode,
                "reg-username-error" => isset($data['reg-username-error']) ? $data['reg-username-error'] : "",
	            "reg-email-error" => isset($data['reg-email-error']) ? $data['reg-email-error'] : "",
	            "reg-pass1-error" => isset($data['reg-pass-error']) ? $data['reg-pass-error'] : "",
	        	"reg-pass2-error" => isset($data['reg-pass-error']) ? $data['reg-pass-error'] : "",
	            "reg-fname-error" => isset($data['reg-fname-error']) ? $data['reg-fname-error'] : "",
	            "reg-sname-error" => isset($data['reg-sname-error']) ? $data['reg-sname-error'] : "",
	            "reg-dob-error" => isset($data['reg-dob-error']) ? $data['reg-dob-error'] : "",
	            "reg-hnumber-error" => isset($data['reg_hnumber-error']) ? $data['reg_hnumber-error'] : "",
	            "reg-street-error" => isset($data['reg_street-error']) ? $data['reg_street-error'] : "",
	            "reg-county-error" => isset($data['reg_county-error']) ? $data['reg_county-error'] : "",
                "reg-country-error" => isset($data['reg_country-error']) ? $data['reg_country-error'] : "",
	            "reg-province-error" => isset($data['reg_province-error']) ? $data['reg_province-error'] : "",
	            "reg-postcode-error" => isset($data['reg_postcode-error']) ? $data['reg_postcode-error'] : ""
            );

            $this->view = new View( "register" );
            $this->view->process( $items );
            $this->view->output();   
        }             
    }
	    
    
    private function ajaxCheckUsernameAvailable( $user )
    {
        if( strlen($user) < 6 )
        {
            print "false";
            return;
        }
        else
        {
            $count = $this->db->query ( "select count(*) from ou_users where username='$user'" , "one" );    
            
            if( $count > 0)
            {
                print "false";
                return;
            }        
            else
            {
                print "true";  
                return;              
            }
        }
    }
    
    
    private function ajaxCheckEmailAvailable( $email )
    {
        // www.php.net email regualr expression
        // http://php.net/manual/en/function.preg-match.php
        
        if( preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])*(\.([a-z0-9])([-a-z0-9_-])([a-z0-9])+)*$/i', $email ) < 1 )
        {
            print "false";
            return;
        }
        else
        {
            $count = $this->db->query ( "select count(*) from ou_users where email='$email'" , "one" );    
            
            if( $count > 0)
            {
                print "false";
                return;
            }        
            else
            {
                print "true";  
                return;              
            }
        }
    }
    
    
    public function index ()
    {

        $items = array(
            "title" => "%register%",
            "reg-username" => "",
            "reg-email" => "",
            "reg-pass1" => "",
        	"reg-pass2" => "",
            "reg-fname" => "",
            "reg-sname" => "",
            "reg-dob" => "",
            "reg-hnumber" => "",
            "reg-street" => "",
            "reg-county" => "",
            "reg-province" => "",
            "reg-postcode" => "",
            "reg-username-error" => "",
            "reg-email-error" => "",
            "reg-pass1-error" => "",
        	"reg-pass2-error" => "",
            "reg-fname-error" => "",
            "reg-sname-error" => "",
            "reg-dob-error" => "",
            "reg-hnumber-error" => "",
            "reg-street-error" => "",
            "reg-county-error" => "",
            "reg-country-error" => "",
            "reg-province-error" => "",
            "reg-postcode-error" => ""
        );

        $this->view = new View( "register" );
        $this->view->process( $items );
        $this->view->output();   

    }   

}

new Register;


