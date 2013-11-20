<?php

/**
* teacher_tb model
*/

class Teacher_Model  extends  CI_Model{

var $id='';
var $username='';
var $password='';
var $loginTime='';
var $grade='';
var $class='';
var $teachernumber='';
var $realname='';
function __construct(){
 parent::__construct();

}
/**
*用户登录
*/
function login($username,$password,$time)

{
 $this->load->database();
 $sql="select * from teacher_tb where username=? and password=?";
 $query=$this->db->query($sql,array($username,$password));
 if($query->num_rows()>0){
    $row=$query->row();
    $this->id=$row->id;
    $this->username=$row->username;
    $this->password=$row->password;
    $this->loginTime=$row->logintime;
    $this->grade=$row->grade;
    $this->class=$row->class;
    $this->teachernumber=$row->teachernumber;
    $this->realname=$row->realname;
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
function insert($username,$password,$realname,$teachernumber,$grade,$class){

$this->load->database();
$sql="insert into teacher_tb values(null,?,?,?,?,null,?,?)";
$query=$this->db->query($sql,array($realname,$teachernumber,$grade,$class,$username,$password));
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