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
         $this->load->helper('form','url');
    }
    /**
    *用户登录
    */
    public function login($username,$password,$time){
        $this->load->database();
        $sql="select * from teacher_tb where username=? and password=?";
        $query=$this->db->query($sql,array($username,$password));
        if($query->num_rows()>0){
            $row=$query->row();
            $this->id=$row->id;
            $this->username=$row->username;
            $this->password=$row->password;
            $this->loginTime=$row->logintime;
            $this->realname=$row->realname;
            $this->gender=$row->gender;
            $sql="update teacher_tb set logintime=? where id=?";
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
  
    //添加教师
    public function insert($username,$password,$realname,$gender){
        $this->load->database();
        $sql="insert into teacher_tb values(null,?,null,?,?,?)";
        $query=$this->db->query($sql,array($realname,$username,$password,$gender));
        $this->db->close();

    }
    
    public function find($username){
        $this->load->database();
        $sql="select * from teacher_tb where username=?";
        $query=$this->db->query($sql,array($username));
        if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data;
    }

    //按id查找
    public function findById($tid){
        $this->load->database();
        $sql="select * from teacher_tb where id=?";
        $query=$this->db->query($sql,array($tid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data;
    }
}

?>