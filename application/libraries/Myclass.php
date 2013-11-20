<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Myclass {

    public function mylibraries()
    {
      
       $CI =& get_instance(); 
       $CI->load->helper('url');
       $CI->load->library('session');
       $CI->config->item('base_url');

    	
    }
}

/* End of file Myclass.php */