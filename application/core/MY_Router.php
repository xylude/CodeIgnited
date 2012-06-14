<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* Router Class
*
* Extends CI Router
*
* @author     Original by EllisLab - extension by CleverIgniters
* @see        http://codeigniter.com
*/

class MY_Router extends CI_Router {
    
    /**
     * Constructor
     *
     * @access    public
     */
    function MY_Router()
    {
        parent::__construct();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Validate Routing Request
     *
     * @access    public
     */
    function _validate_request($segments)
    {
        
        // CHECK TO SEE IF SUBDOMAIN AND GO ON FROM THERE
        $http = explode('.', $_SERVER['HTTP_HOST']);
        if(count($http) > 2)
        {
            if($http[0] != 'www')
            {
                if(is_dir(APPPATH.'controllers/'.$http[0]))
                {
                    // Set the directory and remove it from the segment array
                    $this->set_directory($http[0]);
                    //$segments = array_slice($segments, 1);
                    
                    if (count($segments) > 0)
                    {
                        // Does the requested controller exist in the sub-folder?
                        if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
                        {
                            show_404($this->fetch_directory().$segments[0]);
                        }
                    }
                    else
                    {
                        $this->set_class($this->default_controller);
                        $this->set_method('index');
                    
                        // Does the default controller exist in the sub-folder?
                        if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
                        {
                            $this->directory = '';
                            return array();
                        }
                    
                    }
        
                    return $segments;
                }
                else
                {
                    if (file_exists(APPPATH.'controllers/'.$http[0].EXT))
                    {
                         if($segments[0] == $this->default_controller)
                         {
                              array_shift($segments);
                         }
                         array_unshift($segments, $http[0]);
                         return $segments;
                    }
                }
            }
        }
        //END SUBDOMAIN LOGIC HERE
        
        // Does the requested controller exist in the root folder?
        if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
        {
            return $segments;
        }
        

        // Is the controller in a sub-folder?
        if (is_dir(APPPATH.'controllers/'.$segments[0]))
        {        
            // Set the directory and remove it from the segment array
            $this->set_directory($segments[0]);
            $segments = array_slice($segments, 1);
            
            if (count($segments) > 0)
            {
                // Does the requested controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
                {
                    show_404($this->fetch_directory().$segments[0]);
                }
            }
            else
            {
                $this->set_class($this->default_controller);
                $this->set_method('index');
            
                // Does the default controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
                {
                    $this->directory = '';
                    return array();
                }
            
            }

            return $segments;
        }
        
        // Can't find the requested controller...
        show_404($segments[0]);
    }
}