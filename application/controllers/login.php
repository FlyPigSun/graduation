<?php
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
	public function index()
	{
		$this->load->view('login.html');

    if(!isset($_SESSION)){

    session_start();

    }
    
    $account=strtolower($this->input->post('account'));
   // echo $account;
    //$password=md5($this->input->post('password'));
      $password=$this->input->post('password');
    //echo $password;
    $role=strtolower($this->input->post('role'));
    switch ($role) {
      case 'teacher':
        $this->teacherLogin($account,$password);
       
        break;
      case 'student':
        $this->studentLogin($account,$password);
       
        break;
      default:
        break;
                   }
	}
    //登录
   
  /*public function userlogin() {
     if(!isset($_SESSION)){

    session_start();

    }
    
    $account=strtolower($this->input->post('account'));
   // echo $account;
    //$password=md5($this->input->post('password'));
      $password=$this->input->post('password');
    //echo $password;
    $role=strtolower($this->input->post('role'));
    switch ($role) {
    	case 'teacher':
    		$this->teacherLogin($account,$password);
       
    		break;
    	case 'student':
    		$this->studentLogin($account,$password);
       
    		break;
    	default:
    		break;
                   }
   }*/
 

    public function loginout()
 {
   
    if(!isset($_SESSION))
    {
     session_start();
    }
     session_destroy();
 	
   }



    public function teacherLogin($account,$password)
{  
  $this->load->model('teacher_model','teacher');
   $this->teacher->login($account,$password,date("Y-m-d   H:i:s"));
   if(strlen($this->teacher->account)==0)
   {
    //redirect("");
    $result=101;
    json_encode($result);
    $data['errcode']=$result;
    var_dump($data) ;
   
  
    
   }
   else 
   {
   $arr=array("id"=>$this->teacher->id,"account"=>$this->teacher->account,"time"=>$this->teacher->loginTime,"password"=>$this->teacher->password,"gonghao"=>$this->teacher->gonghao,"grade"=>$this->teacher->grade,"class"=>$this->teacher->class);
   // $arr=array("id"=>$this->teacher->id, "account"=>$this->teacher->account,"time"=>$this->teacher->loginTime,"password"=>$this->teacher->password,"grade"=>$this->teacher->grade,"classes"=>$this->teacher->classes); 
     $_SESSION[TEACHER_USER]=$arr;
     //redirect("/teacher/welcome","index");
    // redirect("/teacher/welcome","index");
     header("Location:/teacher/login/index");
   }
}
   

   public function studentLogin($account,$password)
{
   $this->load->model("student_model","student");
   $this->load->login($account,$password,date("Y-m-d   H:i:s"));
   if(strlen($this->student->account)==0) 
   {
   	//redirect("");
   }
   else
   {
   
   $arr=array("id"=>$this->student->id,"account"=>$this->student->account,"time"=>$this->student->loginTime,"password"=>$this->student->password,"xuehao"=>$this->student->xuehao,"grade"=>$this->student->grade,"class"=>$this->student->class);
   $_SESSION[STUDENT_USER]=$arr;
   //redirect("/student/welcome","index");
   }
}
 


 public function teacherredirect(){

  $this->load->view('/teacher/teacherregister');

 }
public function studentredirect(){

  $this->load->view('/student/studentregister');
 }
public function addhobby(){

 $this->load->view('/student/addhobby');

}
   public function teacherregister(){
    
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
  
  /*pubilic function studentZhuce(){
   
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



  }*/
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
   header("Location:/login/index");



  }



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>