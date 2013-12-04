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
    var $logintime='';
    var $grade='';
    var $class='';
    var $studentnumber='';
    var $notfirst='';
    var $mood='';
    

    function __construct(){
        parent::__construct();

    }
    /**
    *用户登录
    */
    public function login($username,$password,$mood,$time){

        $this->load->database();
        $sql="select * from student_tb where username=? and password=?";
        $query=$this->db->query($sql,array($username,$password));
        if($query->num_rows()>0){
            $row=$query->row();
            $this->id=$row->id;
            $this->username=$row->username;
            $this->password=$row->password;
            $this->logintime=$row->logintime;
            $this->grade=$row->grade;
            $this->class=$row->class;
            $this->notfirst=$row->notfirst;
            $this->mood=$row->mood;
            $sql="update student_tb set logintime=?,mood=? where id=?";
            $this->db->query($sql, array($time,$mood,$this->id));
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
    public function insert($username,$password,$realname,$studentnumber,$grade,$class,$gender,$avatar){
        $this->load->database();
        $sql="insert into student_tb values(null,?,?,?,null,?,?,?,null,null,?,null,0,?,null)";
        $query=$this->db->query($sql,array($realname,$username,$password,$gender,$grade,$class,$studentnumber,$avatar));
        $this->db->close();
    }
    //验证密码
    public function verifypassword($id,$password){
        $this->load->database();
        $sql="select * from student_tb where password=? and id=?";
        $query=$this->db->query($sql,array($password,$id));
        if($query->num_rows()>0){
            $data=100;
        }else{
            $data=102;
        }
        return $data;
    }

    //修改密码
    public function updatepassword($id,$password){
        $this->load->database();
        $sql="update student_tb set password=? where id=?";
        $data=array($password,$id);
        $query=$this->db->query($sql,$data);
        $this->db->close();
    }

    //按用户名查找
    public function find($username){
        $this->load->database();
        $sql="select * from student_tb where username=?";
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
    public function findById($sid){
        $this->load->database();
        $sql="select * from student_tb where id=?";
        $query=$this->db->query($sql,array($sid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data;
    }

     public function findByName($realname){
        $this->load->database();
        $sql="select * from student_tb where realname=?";
        $query=$this->db->query($sql,array($realname));
        if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data;
    }
    //是否做过测试
    public function doTest($sid){
        $this->load->database();
        $sql="update student_tb set notfirst=1 where id=? ";
        $query=$this->db->query($sql,array($sid));
        $this->db->close();

    }
    //个人信息修改
    public function updateInfo($realname,$gender,$grade,$class,$motto,$studentnumber,$sid){
        $this->load->database();
        $sql="update student_tb set realname=?,gender=?,grade=?,class=?,motto=?,studentnumber=? where id=?";
        $query=$this->db->query($sql,array($realname,$gender,$grade,$class,$motto,$studentnumber,$sid));
        $this->db->close();
    }
    
    //上传头像地址
    public function updateAvatar($sid,$avatar){
        $this->load->database();
        $sql="update student_tb set avatar=? where id=?";
        $query=$this->db->query($sql,array($avatar,$sid));
        $this->db->close();
    }
    //查找所有学生信息
    public function findAll(){
        $this->load->database();
        $sql="select * from student_tb";
        $query=$this->db->query($sql);
        $result=$query->result();
        $data=array();
        foreach ($result as $row) {
           $data[]= array('id' =>$row->id ,'username'=>$row->username,"studentnumber"=>$row->studentnumber,
            "motto"=>$row->motto,"realname"=>$row->realname,"avatar"=>$row->avatar );
        }        
        $this->db->close();
        return $data;
    }




}

?>