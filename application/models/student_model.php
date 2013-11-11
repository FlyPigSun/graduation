<?php

/**
* teacher_tb model
*/

class Student_Model  extends  CI_Model{

var $id='';
var $account='';
var $password='';
var $loginTime='';
var $grade='';
var $class='';
var $xuehao='';
var teamid='';
var xingbie='';

function __construct(){
 parent::__construct();

}
/**
*用户登录
*/
function login($account,$password,$time)

{
 $this->load->database();
 $sql="select * from teacher_tb where account=? and password=?";
 $query=$this->db->query($sql,array($account,$password));
 if($query->num_rows()>0){
    $row=$query->row();
    $this->id=$row->id;
    $this->account=$row->account;
    $this->password=$row->password;
    $this->loginTime=$row->logintime;
    $this->grade=$row->grade;
    $this->class=$row->class;
    $sql="update teacher_tb set logintime=? where id=?";
    $this->db->query($sql, array($time,$this->id));
   $this->db->close();
 }

//更新密码
  function password($id,$password){
  $this->load->database();
  $sql="update teacher_tb set password=? where id=?";
  $query=$this->db->query($sql,array($password,id));
  $this->db->close();

  }



}




  
//添加学生
function insert($account,$password,$name,$xuehao,$grade,$class,$xingbie){

$this->load->database();
$sql="insert into teacher_tb values(null,?,?,?,null,?,?,?,null,,null,?,null)";
$query=$this->db->query($sql,array($name,$account,$password,$xingbie,$grade,$class,$xuehao));
//$msg=$this->db->_error_message();
$this->db->close();
//return $msg;
}





}

?>