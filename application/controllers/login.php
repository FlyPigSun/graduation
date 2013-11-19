<?php
ob_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

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

  	public function index(){
        $this->load->library('Twig', array('template_dir' => APPPATH . 'views'), 'twig');
        $this->twig->render('login.html.twig');
    }
    
    public function studentindex()
    {
        $this->load->library('Twig', array('template_dir' => APPPATH . 'views'), 'twig');
        $this->twig->render('student_index.html.twig');
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
    public function loginout(){
        if(!isset($_SESSION)){
            session_start();
        }
        session_destroy();
    }



    public function teacherLogin($username,$password){  
        $this->load->model('teacher_model','teacher');
        $this->teacher->login($username,$password,date("Y-m-d   H:i:s"));
        if(strlen($this->teacher->username)==0){
            $result=102;
            json_encode($result);
            $data['errcode']=$result;
            var_dump($data) ;
        }else{
            $arr=array("id"=>$this->teacher->id,"account"=>$this->teacher->account,"time"=>$this->teacher->loginTime,"password"=>$this->teacher->password,"gonghao"=>$this->teacher->gonghao,"grade"=>$this->teacher->grade,"class"=>$this->teacher->class);
            $_SESSION[TEACHER_USER]=$arr;
            header("Content-Type: text/xml; charset=UTF-8");
            header("Location:/teacher/index");
        }
    }
   

    public function studentLogin($username,$password){
        $this->load->model("student_model","student");
        $this->student->login($username,$password,date("Y-m-d   H:i:s"));
        if(strlen($this->student->username)==0){
            $result=101; 
        }else{
            $arr=array("id"=>$this->student->id,"username"=>$this->student->username,
            "time"=>$this->student->loginTime,"password"=>$this->student->password,
            "studentnumber"=>$this->student->studentnumber,"grade"=>$this->student->grade,
            "class"=>$this->student->class);
            $_SESSION[STUDENT_USER]=$arr;
            $result=100;
        }
            $data['errcode']=$result;
            print_r(json_encode($data));
    }
 


    public function teacherredirect(){
        $this->load->view('/teacher/teacherregister');
    }
    public function studentredirect(){

    }
    public function addhobby(){
        $this->load->view('/student/addhobby');
    }
 /*  public function teacherregister(){
    
   $account=$this->input->post('account');
   $password=$this->input->post('password');
   $name=$this->input->post('name');
   $gonghao=$this->input->post('gonghao');
   $grade=$this->input->post('grade');
   $class=$this->input->post('class');

   $this->load->model('teacher_model','teacher');
   $this->teacher->insert($account,$password,$name,$gonghao,$grade,$class);
   header("Location:/login/index");

   }
  
  pubilic function studentZhuce(){
   
   $account=$this->input->post('account');
   $password=$this->input->post('password');
   $name=$this->input->post('name');
   $gonghao=$this->input->post('gonghao');
   $grade=$this->input->post('grade');
   $class=$this->input->post('class');
   $xingbie=$this->input->post('xingbie');
   $this->load->model('student_model','student');
   $this->student->insert($account,$password,$name,$xuehao,$grade,$class,$xingbie);
   header("Location:/welcome/index");



  }
public function studentregister(){
   
   $account=$this->input->post('account');
   $password=$this->input->post('password');
   $name=$this->input->post('name');
   $gonghao=$this->input->post('xuehao');
   $grade=$this->input->post('grade');
   $class=$this->input->post('class');
   $xingbie=$this->input->post('xingbie');
   $this->load->model('student_model','student');
   $this->student->insert($account,$password,$name,$xuehao,$grade,$class,$xingbie);
   //header("Location:/login/index");



  }*/




}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>