<?php

/**
* class_tb model
*/
class Class_Model  extends  CI_Model{
    var $tid='';
    var $sid='';

    public function insert($tid,$sid){
        $this->load->database();
        $sql="insert into class_tb value(null,?,?)";
        $query=$this->db->query($sql,array($tid,$sid));
        $this->db->close();
    }

    public function find($tid,$sid){
        $this->load->database();
        $sql="select * from class_tb where tid=? and sid=?";
        $query=$this->db->query($sql,array($tid,$sid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data;
    }
    
    public function findAllStudents($tid){
        $this->load->database();
        $sql="select * from class_tb c left join teacher_tb s on c.fid=t.id where c.tid=?";
        $query=$this->db->query($sql,array($tid));
        $result=$query->result();
        $data=array();
        foreach ($result as $row) {
           $data[]= array('id' =>$row->id ,'username'=>$row->username,"studentnumber"=>$row->studentnumber,
            "motto"=>$row->motto,"realname"=>$row->realname,"avatar"=>$row->avatar );
        }        
        $this->db->close();
        return $data;
        
    }

    public function deleteStudents($tid,$sid){
        $this->load->database();
        $sql="delete from class_tb where tid=? and sid=?";
        $query=$this->db->query($sql,array($tid,$sid));
        $this->db->close();
    }

    public function isStudents($tid,$sid){
        $this->load->database();
        $sql="select * from class_tb where tid=? and sid=?";
        $query=$this->db->query($sql,array($tid,$sid));
        if($query->num_rows()>0){
            $result=102;
        }else{
            $result=100;
        }
        return $result;
    }

}?>