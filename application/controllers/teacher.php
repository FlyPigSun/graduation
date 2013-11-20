<?php
ob_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher extends CI_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    public function teacherregister(){
        
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $realname=$this->input->post('realname');
        $teachernumber=$this->input->post('teachernumber');
        $grade=$this->input->post('grade');
        $class=$this->input->post('class');
        $this->load->model('teacher_model','teacher');
        $this->teacher->insert($username,$password,$name,$teachernumber,$grade,$class);
        

    }
  






}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
            