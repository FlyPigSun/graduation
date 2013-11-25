<?php
ob_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {
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
    public function loginAction(){            
        if($this->session->userdata('sid')){
           redirect('/student');
        }      
        if($this->session->userdata('tid')){
           redirect('/teacher');
        }
        else{           
            $this->twig->render('login.html.twig');
        }
    }
    public function userlogin(){
        if(!isset($_SESSION)){
          session_start();
        }
        $username=strtolower($this->input->post('username'));
        $password=$this->input->post('password');
        $role=strtolower($this->input->post('role'));
        switch($role){
          case 'teacher':
            $this->teacherLogin($username,$password);
            break;
          case 'student':
            $this->studentLogin($username,$password);
            break;
          default:
            break;
        }
     }
    public function logout(){       
        $this->session->sess_destroy();            
        $this->twig->render('login.html.twig');
        redirect('/login');
        }
    



    public function teacherLogin($username,$password){          
        $this->load->model('teacher_model','teacher');
        $this->teacher->login($username,$password,date("Y-m-d   H:i:s"));
        if(strlen($this->teacher->username)==0){
            $result=102;          
        }else{
            $arr=array("tid"=>$this->teacher->id,"username"=>$this->teacher->username,
              "time"=>$this->teacher->loginTime,"password"=>$this->teacher->password,
              "teachernumber"=>$this->teacher->teachernumber,"grade"=>$this->teacher->grade,
              "class"=>$this->teacher->class,"role"=>'teacher');
            $this->session->set_userdata($arr);
            $result=100;
        }
            $data['errcode']=$result;
            print_r(json_encode($data));
    }
   

    public function studentLogin($username,$password){
        
        $this->load->model("student_model","student");
        $this->student->login($username,$password,date("Y-m-d   H:i:s"));
        if(strlen($this->student->username)==0){
            $result=101;
        }else{
            $arr=array("sid"=>$this->student->id,"username"=>$this->student->username,
            "time"=>$this->student->loginTime,"password"=>$this->student->password,
            "studentnumber"=>$this->student->studentnumber,"grade"=>$this->student->grade,
            "class"=>$this->student->class,"role"=>'student',"notfirst"=>$this->student->notfirst);
            $this->session->set_userdata($arr);
            $result=100;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
            
    }
    
 

 }

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>