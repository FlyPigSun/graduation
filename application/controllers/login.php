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
    public function studentAction(){
        $this->load->helper('url');
        $this->load->library('session');
       // $role=$this->input->post('role');
        if($this->session->userdata('sid')){
         // if(_SESSION[STUDENT_USER]){
            $this->load->library('Twig', array('template_dir' => APPPATH . 'views'), 'twig');
            $this->twig->render('student_index.html.twig'); 
        }else{
            redirect('login');
        }
    }

    public function teacherAction(){
        $this->load->helper('url');
        $this->load->library('session');
       // $role=$this->input->post('role');
        if($this->session->userdata('tid')){
         // if(_SESSION[TEACHER_USER]){
            $this->load->library('Twig', array('template_dir' => APPPATH . 'views'), 'twig');
            $this->twig->render('student_index.html.twig');
            

        }else{
            redirect('login');
        }
    }
    public function loginAction(){
        $this->load->helper('url');
        $this->load->library('session');
       // $role=strtolower($this->input->post('role'));
      //  if($this->session->userdata('role')=='student'){
        if($this->session->userdata('sid')){
           redirect('/student');
         }
        //if ($this->session->userdata('role')=='teacher') {
            if($this->session->userdata('tid')){
           redirect('/teacher');
         }
        else{
            $this->load->library('Twig', array('template_dir' => APPPATH . 'views'), 'twig');
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
        $this->load->library('session');
        $this->load->helper('url');
        //if($this->session->userdata('id')){
           // $this->session->unset_userdata('id');
            $this->session->sess_destroy();
            $this->load->library('Twig', array('template_dir' => APPPATH . 'views'), 'twig');
            $this->twig->render('login.html.twig');
            redirect('/login');
        }
    



    public function teacherLogin($username,$password){  
        $this->load->library('session');
        //$this->load->helper('url');
        $this->load->model('teacher_model','teacher');
        $this->teacher->login($username,$password,date("Y-m-d   H:i:s"));
        if(strlen($this->teacher->username)==0){
            $result=102;
            
        }else{
            $arr=array("tid"=>$this->teacher->id,"username"=>$this->teacher->username,
              "time"=>$this->teacher->loginTime,"password"=>$this->teacher->password,
              "teachernumber"=>$this->teacher->teachernumber,"grade"=>$this->teacher->grade,
              "class"=>$this->teacher->class,"role"=>'teacher');
            //$_SESSION[TEACHER_USER]=$arr;
            //header("Content-Type: text/xml; charset=UTF-8");
            //header("Location:/teacher/index");
            $this->session->set_userdata($arr);
            $result=100;
        }

            $data['errcode']=$result;
            print_r(json_encode($data));
    }
   

    public function studentLogin($username,$password){
        $this->load->library('session');
        $this->load->model("student_model","student");
        $this->student->login($username,$password,date("Y-m-d   H:i:s"));
        if(strlen($this->student->username)==0){
            $result=101;
        }else{
            $arr=array("sid"=>$this->student->id,"username"=>$this->student->username,
            "time"=>$this->student->loginTime,"password"=>$this->student->password,
            "studentnumber"=>$this->student->studentnumber,"grade"=>$this->student->grade,
            "class"=>$this->student->class,"role"=>'student');
            //$_SESSION[STUDENT_USER]=$arr;
            $this->session->set_userdata($arr);
            $result=100;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
            
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