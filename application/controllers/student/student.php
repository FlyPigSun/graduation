<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends CI_Controller {

//public function index()
	//{
	//	$this->load->view('login.html');
	//}


public function register(){
   
   $account=$this->input->post('username');
   $password=$this->input->post('password');
   $name=$this->input->post('realname');
   $gonghao=$this->input->post('studentnumber');
   $grade=$this->input->post('grade');
   $class=$this->input->post('class');
   $xingbie=$this->input->post('gender');
   $this->load->model('student_model','student');
   $this->student->insert($account,$password,$name,$xuehao,$grade,$class,$xingbie);
   header("Location:/login/index");



  }






}


?>