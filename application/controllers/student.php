<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends MY_Controller {
  
    public function studentAction(){  
        if($this->session->userdata('sid')){
            $sid=$this->session->userdata('sid');
            $this->load->model('student_model','student');
            $student=$this->student->findById($sid);
            $notfirst=$student->notfirst;
            $realname=$student->realname;
            $logintime=$student->loginTime;
            $this->twig->render('student_index.html.twig',array('notfirst'=>$notfirst,'sid'=>$sid,
                'realname'=>$realname,'logintime'=>$logintime));
        }else{
            redirect('/login');
        }
    }

    public function register(){
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $realname=urldecode($this->input->post('realname'));
        $studentnumber=$this->input->post('studentnum');
        $grade=urldecode($this->input->post('grade'));
        $class=$this->input->post('class');
        $gender=urldecode($this->input->post('gender'));
        $this->load->model('student_model','student');
        $judge=$this->student->find($username);
        
        if($judge==null){
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
        $first_style=urldecode($this->input->post('first_style'));
        $second_style=urldecode($this->input->post('second_style'));
        $hobby_result=$this->input->post('hobby_result');
        $hobby_result_text=urldecode($this->input->post('hobby_result_text'));
        $this->load->model('testresult_model','testresult');
        $judge=$this->testresult->findBySid($sid);
        if($judge==null){
            $this->testresult->insert($sid,$first_result,$second_result,$first_style,
            $second_style,$hobby_result,$hobby_result_text);
            $this->load->model('student_model','student');
            $this->student->doTest($sid);
            $result=100;
        }else{
            
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function personalInfo($sid){
        $this->load->model('student_model','student');
        $info=$this->student->findById($sid);
        if($info==null){
            $result=102;
        }else{
            $result=100;   
        }
        $data['data']=$info;
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function updateInfo(){
        $realname = urldecode($this->input->post('realname'));
        $gender = urldecode($this->input->post('gender'));
        $studentnumber = $this->input->post('studentnumber');
        $grade = urldecode($this->input->post('grade'));
        $motto = urldecode($this->input->post('motto'));
        $class='';
        $sid=$this->session->userdata('sid');
        if($realname!=null){
        $this->load->model('student_model','student');
        $this->student->updateInfo($realname,$gender,$grade,$class,$motto,$studentnumber,$sid);
        $result=100;
        }else{
        $result=102;
        } 
        $data['errcode']=$result;
        print_r(json_encode($data)); 
    }

    public function updatepassword(){
        $sid=$this->session->userdata('sid');
        $oldpassword=$this->input->post('oldPassword');
        $newpassword=$this->input->post('newPassword');
        //print_r($password);
        $this->load->model('student_model','student');
        $result=$this->student->verifypassword($sid,$oldpassword);
        //print_r($result);
        if($result==100){
            $this->student->updatepassword($sid,$newpassword);
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function myStyle(){
        $sid=$this->session->userdata('sid');
        $this->load->model('testresult_model','testresult');
        $testresult=$this->testresult->findBySid($sid);
        $data['data']=$testresult;
        $result=100;
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function selectHobby(){
        $sid=$this->session->userdata('sid');
        $hobby=urldecode($this->input->post('hobby'));
        $this->load->model('hobby_model','hobby');
        $judge=$this->hobby->find($sid,$hobby);
        $judgecount=sizeof($this->hobby->findAllHobby($sid));
        if($judge==null){
            $this->hobby->insert($sid,$hobby);
            $result=100;
        }else if($judgecount<=20){
            $result=104;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));

    }

    public function myHobby(){
        $hobby=urldecode($this->input->post('myhobby'));
        $sid=$this->session->userdata('sid');
        $this->load->model('hobby_model','hobby');
        $judge=$this->hobby->find($sid,$hobby);
        if($judge==null){
            $this->hobby->insert($sid,$hobby);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function findAllHobby(){
        $sid=$this->session->userdata('sid');
        $this->load->model('hobby_model','hobby');
        $info=$this->hobby->findAllHobby($sid);
        $data['data']=$info;
        print_r(json_encode($data));
    }

    public function deleteHobby(){
        $sid=$this->session->userdata('sid');
        $hobby=$this->input->post('hobby');
        $this->load->model('hobby_model','hobby');
        $judge=$this->hobby->find($sid,$hobby);
        if($judge!=null){
            $info=$this->hobby->deleteHobby($sid,$hobby);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function upload(){                                
        $this->load->library('upload'); 
        $config['upload_path']='./uploads/';
        $config['allowed_types']='gif|jpg|png';
        $config['max_size']='100';
        $config['max_width']='1024';
        $config['max_height']='768';

        $this->load->library('upload'); 
        $this->upload->initialize($config);
        $field_name = "student_head";       
        if(! $this->upload->do_upload($field_name)){            
            $data= array('error' => $this->upload->display_errors());
            $result=102;
        }else{
            $data=array('upload_data'=>$this->upload->data());
            $head='./uploads/';
            $head=$head.$data->client_name;
            $sid=$this->session->userdata('sid');
            $this->load->model('student_model','student');
            //$this->student->updataHead($sid,$head);
            $result=100;
        }
        print_r($head) ;
    }



    

}
?>