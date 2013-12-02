<?php
ini_set('date.timezone','Asia/Shanghai');
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher extends MY_Controller {
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
    public function register(){   
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $realname=urldecode($this->input->post('realname'));
        $gender=urldecode($this->input->post('gender'));
        $this->load->model('teacher_model','teacher');
        $judge=$this->teacher->find($username);
        
        if($judge==null){
            $this->teacher->insert($username,$password,$realname,$gender);
            $this->teacher->login($username,$password,date("Y-m-d   H:i:s"));
            $arr=array("tid"=>$this->teacher->id,"username"=>$username,
            "time"=>$this->teacher->loginTime,"password"=>$password,
            "role"=>'teacher');
            $this->session->set_userdata($arr);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));               
    }
        
    public function teacherAction(){  
        if($this->session->userdata('tid')){
            $tid=$this->session->userdata('tid');
            $this->load->model('teacher_model','teacher');
            $teacher=$this->teacher->findById($tid);
            $realname=$teacher->realname;
            $logintime=$teacher->logintime;
            $this->twig->render('teacher_index.html.twig',array('tid'=>$tid,
                'realname'=>$realname,'logintime'=>$logintime));     
        }else{
            redirect('/login');
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
            