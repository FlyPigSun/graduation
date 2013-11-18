<?php

/**
* student_tb model
*/
ini_set('date.timezone','Asia/Shanghai');
class Student_Model  extends  CI_Model{

var $id='';
var $username='';
var $password='';
var $realname='';
var $loginTime='';
var $grade='';
var $class='';
var $studentnumber='';

function __construct(){
 parent::__construct();

}
/**
*用户登录
*/
public function login($username,$password,$time)

{
 $this->load->database();
 $sql="select * from student_tb where username=? and password=?";
 $query=$this->db->query($sql,array($username,$password));
 if($query->num_rows()>0){
    $row=$query->row();
    $this->id=$row->id;
    $this->username=$row->username;
    $this->password=$row->password;
    $this->loginTime=$row->loginTime;
    $this->grade=$row->grade;
    $this->class=$row->class;
    $sql="update student_tb set logintime=? where id=?";
    $this->db->query($sql, array($time,$this->id));
   $this->db->close();
 }
}
//更新密码
 public function password($id,$password){
  $this->load->database();
  $sql="update teacher_tb set password=? where id=?";
  $query=$this->db->query($sql,array($password,id));
  $this->db->close();

  }








  
//添加学生
public function insert($username,$password,$realname,$studentnumber,$grade,$class,$gender){

$this->load->database();
$sql="insert into student_tb values(null,?,?,?,null,?,?,?,null,null,?,null)";
$query=$this->db->query($sql,array($realname,$username,$password,$gender,$grade,$class,$studentnumber));
//$msg=$this->db->_error_message();
$this->db->close();
//return $msg;
}

//修改密码
public function updatepassword($id,$password){
$this->load->database();
$sql="update teacher_tb set password=? where id=?";
$data=array($password,$id);
$query=$this->db->query($sql,$data);
$this->db->close();
}

//


}

?>