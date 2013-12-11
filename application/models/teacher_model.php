<?php

/**
* teacher_tb model
*/
ini_set('date.timezone','Asia/Shanghai');
class Teacher_Model  extends  CI_Model{
    var $id='';
    var $username='';
    var $password='';
    var $logintime='';
    var $grade='';
    var $class='';
    var $teachernumber='';
    var $realname='';
    var $motto='';
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
            $this->logintime=$row->logintime;
            $this->realname=$row->realname;
            $this->gender=$row->gender;
            $this->grade=$row->grade;
            $this->teachernumber=$row->teachernumber;
            $this->motto=$row->motto;
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
    public function insert($username,$password,$realname,$gender,$teachernumber,$grade,$avatar){
        $this->load->database();
        $sql="insert into teacher_tb values(null,?,null,?,?,?,?,null,?,?)";
        $query=$this->db->query($sql,array($realname,$username,$password,$gender,$teachernumber,$grade,$avatar));
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
    //修改信息
    public function updateInfo($realname,$gender,$motto,$teachernumber,$tid,$grade){
        $this->load->database();
        $sql="update teacher_tb set realname=?,gender=?,motto=?,teachernumber=?,grade=? where id=?";
        $query=$this->db->query($sql,array($realname,$gender,$motto,$teachernumber,$grade,$tid));
        $this->db->close();
    }

    //修改密码
    public function updatepassword($id,$password){
        $this->load->database();
        $sql="update teacher_tb set password=? where id=?";
        $data=array($password,$id);
        $query=$this->db->query($sql,$data);
        $this->db->close();
    }
    //验证密码
    public function verifypassword($id,$password){
        $this->load->database();
        $sql="select * from teacher_tb where password=? and id=?";
        $query=$this->db->query($sql,array($password,$id));
        if($query->num_rows()>0){
            $data=100;
        }else{
            $data=102;
        }
        return $data;
    }

    //上传头像地址
    public function updateAvatar($tid,$avatar){
        $this->load->database();
        $sql="update teacher_tb set avatar=? where id=?";
        $query=$this->db->query($sql,array($avatar,$tid));
        $this->db->close();
    }
}

?>