<?php

/**
* teacher_tb model
*/

class Teacher_Model  extends  CI_Model{

var $id='';
var $account='';
var $password='';
var $loginTime='';
var $grade='';
var $class='';
var $gonghao='';
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




  
//添加教师
function insert($account,$password,$name,$gonghao,$grade,$class){

$this->load->database();
$sql="insert into teacher_tb values(null,?,?,?,?,null,?,?)";
$query=$this->db->query($sql,array($name,$gonghao,$grade,$class,$account,$password));
//$msg=$this->db->_error_message();
$this->db->close();
//return $msg;
}

//修改密码
function password($id,$password){
$this->load->database();
$sql="update teacher_tb set password=? where id=?";
$data=array($password,$id);
$query=$this->db->query($sql,$data);
$this->db->close();
}

//


}

?>