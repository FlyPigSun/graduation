<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends MY_Controller {

    //public function __construct(){
       //parent::__construct();
        //$this->load->library('my_class');
    //}   
    public function studentAction(){  
        if($this->session->userdata('sid')){
            $this->load->library('Twig', array('template_dir' => APPPATH . 'views'), 'twig');
            $this->twig->render('student_index.html.twig'); 
        }else{
            redirect('/login');
        }
    }
    public function register(){
        $username=$this->input->post('username');
        $username = mysql_real_escape_string($username);
        $password=$this->input->post('password');
        $password = mysql_real_escape_string($password);
        $realname=$this->input->post('realname');
        $studentnumber=$this->input->post('studentnumber');
        $grade=$this->input->post('grade');
        $class=$this->input->post('class');
        $gender=$this->input->post('gender');
        $this->load->model('student_model','student');
        $judge=$this->student->find($username);
        
        if($judge['obj']==null){
            $this->student->insert($username,$password,$realname,$studentnumber,$grade,$class,$gender);
            $this->student->login($username,$password,date("Y-m-d   H:i:s"));
            $arr=array("sid"=>$this->student->id,"username"=>$username,
            "time"=>$this->student->loginTime,"password"=>$password,
            "teachernumber"=>$studentnumber,"grade"=>$grade,
            "class"=>$class,"role"=>'student');
            $this->session->set_userdata($arr);
            $result=100;
        
        }else{
            $result=102;
        }
    $data['errcode']=$result;
    print_r(json_encode($data));       
              
    }






}


?>