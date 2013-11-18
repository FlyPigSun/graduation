<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends CI_Controller {

//public function index()
	//{
	//	$this->load->view('login.html');
	//}


public function register(){
   
   $username=$this->input->post('username');
   $password=$this->input->post('password');
   $realname=$this->input->post('realname');
   $studentnumber=$this->input->post('studentnumber');
   $grade=$this->input->post('grade');
   $class=$this->input->post('class');
   $gender=$this->input->post('gender');
   $this->load->model('student_model','student');
   $this->student->insert($username,$password,$realname,$studentnumber,$grade,$class,$gender);
   //header("Location:/login/index");



  }






}


?>