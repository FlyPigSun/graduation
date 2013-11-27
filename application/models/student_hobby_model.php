<?php

/**
* hobby_tb model
*/
class Student_Hobby_Model  extends  CI_Model{
    var $id='';
    var $sid='';
    var $hid='';

    public function insert($sid,$hid){
        $this->load->database();
        $sql="insert into student_hobby_tb value(null,?,?) ";
        $query=$this->db->query($sql,array($sid,$hid));
        $this->db->close();
    }

    public function  find($sid,$hid){
        $this->load->database();
        $sql="select * from student_hobby_tb where sid=? and hid=?";
        $query=$this->db->query($sql,array($sid,$hid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else{
            $data=null;
        }
        $this->db->close();
        return $data;
       
    }

    public function delete($sid,$hid){
        $this->load->database();
        $sql="delete * from student_hobby_tb where sid=? and hid=?";
        $query=$this->db->query($sql,array($sid,$hid));
        $this->db->close();
    }



}?>