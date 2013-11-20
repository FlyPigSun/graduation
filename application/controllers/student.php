<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends CI_Controller {

    public function index(){
        $this->load->library('Twig', array('template_dir' => APPPATH . 'views'), 'twig');
        $this->twig->render('student_index.html.twig');
    }

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




    public function register(){
        $this->load->helper('url');
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $realname=$this->input->post('realname');
        $studentnumber=$this->input->post('studentnumber');
        $grade=$this->input->post('grade');
        $class=$this->input->post('class');
        $gender=$this->input->post('gender');
        $this->load->model('student_model','student');
        $this->student->insert($username,$password,$realname,$studentnumber,$grade,$class,$gender);
        $result=100;
        
        redirect('/student');
       // $data['errcode']=$result;
        //print_r(json_encode($data));

    }






}


?>