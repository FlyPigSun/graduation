<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class My_Class {

    public function mylibraries()
    {
      
       $CI =& get_instance(); 
       $CI->load->helper('url');
       $CI->load->library('session');
       $CI->config->item('base_url');
       $CI->load->library('Twig', array('template_dir' => APPPATH . 'views'), 'twig');

    	
    }
}

/* End of file Myclass.php */