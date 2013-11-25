<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends MY_Controller {

    public function studentAction(){  
        if($this->session->userdata('sid')){
            if($this->session->userdata('notfirst')==0){
                redirect('/test');
            }else{
                $this->twig->render('student_index.html.twig'); 
            }
        }else{
            redirect('/login');
        }
    }
    public function register(){
        $username=$this->input->post('username');
        $password=$this->input->post('password');
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
            "class"=>$class,"role"=>'student',"character"=>'',"testscore"=>0,"notfirst"=>0);
            $this->session->set_userdata($arr);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }
    public function testSubmit(){
        $sid=$this->input->post('sid');
        $first_result=$this->input->post('first_result');
        $second_result=$this->input->post('second_result');
        $first_style=$this->input->post('first_style');
        $second_style=$this->input->post('second_style');
        $hobby_result=$this->input->post('hobby_result');
        $hobby_result_text=$this->input->post('hobby_result_text');
        $this->load->model('testresult_model','testresult');
        $this->testresult->insert($sid,$first_result,$second_result,$first_style,$second_style,$hobby_result,$hobby_result_text);
        
    }

    public function studentTestAction(){
        $sid=$this->session->userdata('sid');
        $this->twig->render('student_test.html.twig',array('sid'=>$sid)); 
    }

}
?>