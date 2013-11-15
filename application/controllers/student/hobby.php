<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hobby extends CI_Controller{

public function index(){




}

//新增hobby类型
public function insert()
{
$this->load->model('hobby_model','hobby');
$xingqu=$this->input->post("xingqu");
$student=$this->hobby->find($xingqu);
//$data["student"]=$stu;
//var_dump($student);
try {
	if($student['obj']==null){
$this->hobby->insert($xingqu);}
} catch (Exception $e) {
	echo('');
}




}









}
?>