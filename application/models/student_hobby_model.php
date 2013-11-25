<?php

/**
* hobby_tb model
*/
class Student_Hobby_Model  extends  CI_Model{
    var $id='';
    var $studentid='';
    var $hobbyid='';

    public function insert($studentid,$hobbyid){
        $this->load->database();
        $sql="insert into student_hobby_tb value(null,?,?) ";
        $query=$this->db->query($sql,array($studentid,$hobbyid));
        $this->db->close();
    }

    public function  find($studentid,$hobbyid){
        $this->load->database();
        $sql="select * from student_hobby_tb where studentid=? and hobbyid=?";
        $query=$this->db->query($sql,array($studentid,$hobbyid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else{
            $data=null;
        }
        $this->db->close();
        return $data;
       
    }



}?>