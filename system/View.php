<?php


/**
  @View Used in conjunction with a controller instance

  @author David O Neill
  @version 1.0.0
  @todo implement try catch and throw 

  {@link http://en.wikipedia.org/wiki/Model–view–controller Model–view–controller}
*/

class View extends Base
{
    private $page;
    private $error;
    private $rec_include = 0; // 2 levels of recursion
    private $rec_replace = 0; // 2 levels of recursion
    private $plugin_hooks = array();
    private $hooks = array();
    

    /**
      Constructor
      
      @param $template The template (tpl) file to be loaded
      @param Controller $constroller The controller using this view

      {@link loadPlugins()}
      {@link attachPluginObservers()}
      {@link applyIncludes()}

      {@link Controller}
      {@link SessionManager}
      {@link ViewCommon}
      
      {@link SessionManager::getTheme()}
      {@link Controller::getObservers()}
      {@link ViewCommon::start()}

      {@link http://ie.php.net/manual/en/function.file-exists.php file_exists}
      {@link http://ie.php.net/manual/en/function.join.php join}
      {@link http://ie.php.net/manual/en/function.file.php file}
    */

    public function __construct( $template = "index.html" ) 
    {
        parent::__construct();
     
        $this->registry->__VIEW = $this;
   	
        if ( file_exists( __SITE_ROOT . "/views/" . $this->session->getTheme() . "/" . $template . ".tpl" ) )
        {            
            $this->loadPlugins();            
            $this->page = join( "" , file( __SITE_ROOT . "/views/" . $this->session->getTheme() . "/" . $template . ".tpl" ) );
            $this->applyIncludes();
        }
        else
        {
            $this->error = "views file $template not found.";
        }
    }   


    /**
      Register hook maps a plugin to a tag
      As the page is constructed specific tags
      will fire hook event handlers supplied by Plugins
      
      @param Plugin $plugin The plugin attached to this view
      @param $uniqueid a unique id supplied for each individual hook
      @param $trigger the tag the triggers the hook
      @param $action the function inside the plugin to fire

      {@link http://ie.php.net/manual/en/function.isset.php isset}
      {@link http://ie.php.net/manual/en/function.in-array.php in_array}
    */

    public function registerHook( Plugin $plugin , $uniqueid , $trigger , $action )
    {
        if( ! isset( $this->plugin_hooks[ $uniqueid ] ) )
        {
            if( ! in_array( $trigger , $this->hooks ) )
            {
                $this->hooks[ $trigger ] = $trigger;
            }

            $this->plugin_hooks[ $uniqueid ] = array ( $plugin , $trigger , $action );
        }
    }


    /**
      Replaces tags in the view template with specified 
      information provided by plugins or the view controller
      
      @param $tags An associative array of keys (what to be replaced) => replacement

      {@link loadFile()}

      {@link SessionManager}
      {@link ViewCommon}
      
      {@link SessionManager::getTheme()}
      {@link ViewCommon::globalDirectives()}

      {@link http://ie.php.net/manual/en/function.sizeof.php sizeof}
      {@link http://ie.php.net/manual/en/function.file-exists.php file_exists}
      {@link http://ie.php.net/manual/en/function.unset.php unset}
      {@link http://ie.php.net/manual/en/function.preg-replace.php preg_replace}
      {@link http://ie.php.net/manual/en/function.in-array.php in_array}
      {@link http://ie.php.net/manual/en/function.call-user-func-array.php call_user_func_array}
      {@link http://ie.php.net/manual/en/function.preg-match-all.php preg_match_all}
    */

    public function process( $tags = array() ) 
    {
    	$global_directives = $this->registry->__COMMON->globalDirectives();    
    	
    	if( $global_directives )	
    	{
    		$this->process( $tags ); // process tags first to allow override of global directives
    		$this->process( $global_directives );    		
    		return;    		
    	}  
    	
        if ( sizeof( $tags ) > 0 )
        {
            foreach ( $tags as $tag => $data ) 
            {
                if( is_array( $data ) )
                {
                    $this->processDirective( $data[0] , $data[1] , $data[2] );
                    unset( $tags[ $tag ] );
                    $this->process( $tags );
                    return;
                }

                if( file_exists( __SITE_ROOT . "/views/" . $this->session->getTheme() . "/" . $data ) && $data != "" )
                {
                    $data = $this->loadFile( $data );
                    unset( $tags[ $tag ] );
                    $this->page = preg_replace( "/{" . $tag . "}/" , $data , $this->page );
                    $this->process( $tags );
                    return;
                }
                else
                {
                    if( in_array( "{" . $tag . "}" , $this->hooks ) )
                    {
                        foreach( $this->plugin_hooks as $key => $hook )
                        {
                            if( $hook[1] == "{" . $tag . "}" )
                            {                      
                                $plugin_data = call_user_func_array( array( $hook[0] , $hook[2] ) , array( "{" . $tag . "}" , $data ) );
                                $this->page = preg_replace( "/{" . $tag . "}/" , $plugin_data , $this->page );
                            }
                        }
                        unset( $this->hooks[ "{" . $tag . "}" ] );
                    }
                    else
                    {
                        $this->page = preg_replace( "/{" . $tag . "}/" , $data , $this->page );
                    }
                }
            }
        }
        
        if( preg_match_all( "/\\{[a-zA-Z0-9_\\.]+\\}/" , $this->page , $matches ) > 0 )
        {
            if( $this->rec_replace < 2 )
            {
                $this->rec_replace++;
                $this->process( $tags );
            } 
	    else
	    {
                 preg_replace("/\\{[a-zA-Z0-9_\\.]+\\}/", "", $this->page); // cant find (so replace with nothing)
            }
        }
    }


    /**
      Processes a directive given a $match, and the Controller with the callback function
      
      @param $match the directive to process
      @param Controller $controller the controller with the processing function
      @callback the function to call in the controller

      {@link http://ie.php.net/manual/en/function.preg-replace.php preg_replace}
      {@link http://ie.php.net/manual/en/function.call-user-func-array.php call_user_func_array}
      {@link http://ie.php.net/manual/en/function.preg-match.php preg_match_all}      
    */

    private function processDirective( $match , $class , $callback )
    {
        $pattern = "/\#$match\#(.*)\#\/$match\#/s";
        $num = preg_match( $pattern , $this->page , $matches );
        if( $num > 0 )
        {
            $replacement = call_user_func_array( array( $class , $callback ) , array( $matches[1] ) );
            $this->page = preg_replace( $pattern , $replacement , $this->page );
        }
    }


    /**
      Outputs the current view
      Call applyLocale() first as the last action before outputting
      
      {@link applyLocale()}
    */

    public function output() 
    {
        $this->applyLocale();
        echo $this->page;
        
        if( isset ($this->registry->__GET['debug']) || $this->session->getKey("debug") != false )
        {
        	print "<center><div style='width:1000px;padding-left:30px;text-align:left'>";
	        	$this->session->setKey("debug","true");
	        	$this->db->dumpDebugLog();
	        	$this->session->dumpDebugLog();
	        	$this->registry->dumpDebugLog();
        	print "</div><div style='width:1000px;padding-left:30px;text-align:left' id='phpinfo'>";
        	ob_start () ;
			phpinfo () ;
			$pinfo = ob_get_contents () ;
			preg_match ('%<style type="text/css">(.*?)</style>.*?(<body>.*</body>)%s', ob_get_clean(), $matches);
			print $matches[2];
        	print "</div></center>";
        }
    }


    /**
      Loads the plugins available for the current controller 
      
      @param Controller $controller the controller operating the view

      {@link http://ie.php.net/manual/en/function.get-class.php get_class}
      {@link http://ie.php.net/manual/en/function.opendir.php opendir}
      {@link http://ie.php.net/manual/en/function.readdir.php readdir}
      {@link http://ie.php.net/manual/en/function.require-once.php require_once}
      {@link http://ie.php.net/manual/en/function.explode.php explode}
      {@link http://ie.php.net/manual/en/function.is-dir.php is_dir}
      {@link http://ie.php.net/manual/en/function.stristr.php stristr}
    */

    private function loadPlugins()
    {
        $controller_name = get_class( $this->registry->__CONTROLLER );

        $plugins_dir = __SITE_ROOT . "/system/plugins/" . $controller_name . "/";

        $dh = @opendir( $plugins_dir );
        
        if( $dh  )
        {
            while( ( $file = readdir( $dh ) ) !== false )
            {
                if( !is_dir( $file ) && !stristr( $file , "~" ) )
                {
                    require_once( __SITE_ROOT . "/system/plugins/" . $controller_name . "/" . $file );
                    $plugin = explode( "." , $file ); 
                    $obs = new $plugin[0]( $this , $this->registry->__CONTROLLER );  
                }
            }   
        } 
    }


    /**
      Loads a file and returns it to the caller

      @return string the contents of the file
      
      @param $file the file to be loaded

      {@link SessionManager}
      {@link SessionManager::getTheme()}
      
      {@link http://ie.php.net/manual/en/function.ob-start.php ob_start}
      {@link http://ie.php.net/manual/en/function.ob-get-contents.php ob_get_contents}
      {@link http://ie.php.net/manual/en/function.ob-end-clean.php ob_end_clean}
      {@link http://ie.php.net/manual/en/function.include.php include}
    */

    private function loadFile( $file ) 
    {
        ob_start();
        include( __SITE_ROOT . "/views/" . $this->session->getTheme() . "/" . $file );
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }    


    /**
      Includes the %language% translation specific to the session language
      and replaces the %language% specific replacements

      {@link SessionManager}
      {@link SessionManager::getLanguage()}
      
      {@link http://ie.php.net/manual/en/function.include.php include}
      {@link http://ie.php.net/manual/en/function.get-class.php get_class}     
      {@link http://ie.php.net/manual/en/function.preg-replace.php preg_replace}
      {@link http://ie.php.net/manual/en/function.in-array.php in_array}
      {@link http://ie.php.net/manual/en/function.array-merge.php array_merge}
    */

    private function applyLocale()
    {
        $pluginLocalesMerged = array();
        
        $lang = $this->registry->__DB->query("select code from locale where id='".$this->session->getLanguage()."'","one");
        
        if($lang=="")
        {
			$lang = "en";
        }
        
        include( __SITE_ROOT . "/system/locale/" . $lang . ".php" );

        foreach( $this->plugin_hooks as $key => $plugin )
        {
            if( ! in_array( get_class( $plugin[0] ) , $pluginLocalesMerged ) )
            {
                $pluginLocalesMerged[] = get_class( $plugin[0] );
                $locale = array_merge( $locale , $plugin[0]->getLocale() );   
            }
        }
        
		foreach ( $locale as $word => $replacement ) 
        {
            $this->page = preg_replace( "/%" . $word . "%/" , $replacement , $this->page );
        }
    }


    /**
      Applys the @includes@ specified in the template views

      {@link loadfile()}      

      {@link SessionManager}
      {@link SessionManager::getLanguage()}
      
      {@link http://ie.php.net/manual/en/function.preg-match-all.php preg_match_all}
      {@link http://ie.php.net/manual/en/function.count.php count}
      {@link http://ie.php.net/manual/en/function.substr.php substr}
      {@link http://ie.php.net/manual/en/function.strlen.php strlen}
      {@link http://ie.php.net/manual/en/function.preg-replace.php preg_replace}
      {@link http://ie.php.net/manual/en/function.preg-match-all.php preg_match_all}
    */

    private function applyIncludes()
    {
        preg_match_all( "/\\@[a-zA-Z0-9_\\.]+\\@/" , $this->page , $matches );

        foreach( $matches as $match )
        {
            for ( $i = 0; $i < count( $match ); $i++ ) 
            {
                $include_name = substr( $match[$i] , 1 , strlen( $match[$i] ) - 2 );
                $data = $this->loadFile( $include_name . ".tpl" );
                $this->page = preg_replace( "/@" . $include_name . "@/" , $data , $this->page );         
            }
        }
        
        if( preg_match_all( "/\\@[a-zA-Z0-9_\\.]+\\@/" , $this->page , $matches ) > 0 )
        {
            if( $this->rec_include < 2 )
            {
                $this->rec_include++;
                $this->applyIncludes();
            }             
        }
    }

}

